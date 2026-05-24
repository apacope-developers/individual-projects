const { Appointment, Doctor, Patient, User, Payment, Notification } = require('../models');
const { sendAppointmentConfirmation, sendDoctorNewAppointmentEmail } = require('../utils/email');
const { Op } = require('sequelize');

/**
 * Book new appointment
 */
const bookAppointment = async (req, res, next) => {
  try {
    const user = req.user;
    const { doctor_id, date, time_slot, type, reason } = req.body;

    // Get patient profile
    const patient = await Patient.findOne({ where: { user_id: user.id } });
    if (!patient) {
      return res.status(404).json({
        success: false,
        message: 'Patient profile not found'
      });
    }

    // Get doctor
    let doctor = await Doctor.findByPk(doctor_id);

    // If doctor not found in DB, check if it's a sample fallback doctor and create one
    if (!doctor) {
      const sampleDoctors = [
        {
          id: 101,
          specialty: 'General Practice',
          qualification: 'MBBS, Family Medicine',
          experience: 12,
          bio: 'Experienced GP helping patients manage chronic conditions and preventive care.',
          consultation_fee: 45.0,
          available: true,
          rating: 4.8,
          total_reviews: 128,
          hospital: 'City Care Medical Center',
          location: 'Nairobi',
          user: {
            name: 'Amina Hassan',
            email: 'amina.hassan@example.com',
            phone: '+254 700 123456',
            profile_photo: ''
          }
        },
        {
          id: 102,
          specialty: 'Dermatology',
          qualification: 'MD, Dermatology',
          experience: 9,
          bio: 'Skin specialist offering modern treatment for eczema, acne, and rashes.',
          consultation_fee: 55.0,
          available: true,
          rating: 4.7,
          total_reviews: 94,
          hospital: 'Wellness Dermatology Clinic',
          location: 'Nairobi',
          user: {
            name: 'James Mwangi',
            email: 'james.mwangi@example.com',
            phone: '+254 700 654321',
            profile_photo: ''
          }
        },
        {
          id: 103,
          specialty: 'Cardiology',
          qualification: 'MD, Cardiology',
          experience: 15,
          bio: 'Cardiologist with expertise in heart health, hypertension, and preventive cardiac care.',
          consultation_fee: 75.0,
          available: true,
          rating: 4.9,
          total_reviews: 142,
          hospital: 'Central Heart Institute',
          location: 'Nairobi',
          user: {
            name: 'Dr. Paul Njoroge',
            email: 'paul.njoroge@example.com',
            phone: '+254 700 987654',
            profile_photo: ''
          }
        }
      ];

      const sample = sampleDoctors.find((s) => s.id === parseInt(doctor_id));
      if (sample) {
        // Try to find existing user by email first
        let doctorUser = await User.findOne({ where: { email: sample.user.email } });
        if (!doctorUser) {
          // sanitize phone to digits only to satisfy model validation
          const phone = sample.user.phone ? sample.user.phone.replace(/\D/g, '') : null;
          doctorUser = await User.create({
            name: sample.user.name,
            email: sample.user.email,
            password: Math.random().toString(36).slice(-8),
            role: 'doctor',
            phone
          });
        }

        doctor = await Doctor.create({
          user_id: doctorUser.id,
          specialty: sample.specialty,
          qualification: sample.qualification,
          experience: sample.experience,
          bio: sample.bio,
          consultation_fee: sample.consultation_fee,
          available: sample.available,
          rating: sample.rating,
          total_reviews: sample.total_reviews,
          is_approved: true
        });
      }
    }

    if (!doctor) {
      return res.status(404).json({
        success: false,
        message: 'Doctor not found'
      });
    }

    if (!doctor.available) {
      return res.status(400).json({
        success: false,
        message: 'Doctor is not available for bookings'
      });
    }

    // Use the actual doctor id (in case we created a DB record from a sample)
    const selectedDoctorId = doctor.id || doctor_id;

    // Check if slot is already booked
    const existingAppointment = await Appointment.findOne({
      where: {
        doctor_id: selectedDoctorId,
        date,
        time_slot,
        status: { [Op.notIn]: ['cancelled', 'no-show'] }
      }
    });

    if (existingAppointment) {
      return res.status(409).json({
        success: false,
        message: 'This time slot is already booked'
      });
    }

    // Create appointment
    const appointment = await Appointment.create({
      patient_id: patient.id,
      doctor_id: selectedDoctorId,
      date,
      time_slot,
      type: type || 'in-person',
      reason,
      fee: doctor.consultation_fee,
      status: 'pending'
    });

    // Get doctor user details for email
    const doctorUser = await User.findByPk(doctor.user_id);
    const patientUser = await User.findByPk(user.id);

    // Send confirmation email to patient
    try {
      await sendAppointmentConfirmation(
        patientUser.email,
        patientUser.name,
        doctorUser.name,
        date,
        time_slot
      );
    } catch (emailError) {
      console.error('Patient email error:', emailError);
    }

    // Notify doctor by email
    if (doctorUser?.email) {
      try {
        await sendDoctorNewAppointmentEmail(
          doctorUser.email,
          doctorUser.name,
          patientUser.name,
          date,
          time_slot,
          type || 'in-person'
        );
      } catch (emailError) {
        console.error('Doctor email error:', emailError);
      }
    }

    // Create notifications for patient and doctor
    await Notification.create({
      user_id: user.id,
      title: 'Appointment Booked',
      message: `Your appointment with Dr. ${doctorUser.name} has been booked for ${date} at ${time_slot}.`,
      type: 'appointment',
      related_id: appointment.id
    });

    await Notification.create({
      user_id: doctorUser.id,
      title: 'New Appointment',
      message: `${patientUser.name} booked an appointment for ${date} at ${time_slot}.`,
      type: 'appointment',
      related_id: appointment.id
    });

    // Real-time notification via socket
    const io = req.app.get('io');
    if (io) {
      io.to(`user-${doctorUser.id}`).emit('new-appointment', {
        appointmentId: appointment.id,
        patientName: patientUser.name,
        date,
        time_slot
      });
    }

    res.status(201).json({
      success: true,
      message: 'Appointment booked successfully',
      data: appointment
    });
  } catch (error) {
    next(error);
  }
};

/**
 * Get patient's appointments
 */
const getPatientAppointments = async (req, res, next) => {
  try {
    const user = req.user;
    const { status } = req.query;

    const patient = await Patient.findOne({ where: { user_id: user.id } });
    if (!patient) {
      return res.status(404).json({
        success: false,
        message: 'Patient profile not found'
      });
    }

    const whereClause = { patient_id: patient.id };
    if (status) whereClause.status = status;

    const appointments = await Appointment.findAll({
      where: whereClause,
      include: [
        {
          model: Doctor,
          as: 'doctor',
          include: [
            {
              model: User,
              as: 'user',
              attributes: ['id', 'name', 'email', 'phone', 'profile_photo']
            }
          ]
        }
      ],
      order: [['date', 'DESC'], ['time_slot', 'DESC']]
    });

    res.status(200).json({
      success: true,
      data: appointments
    });
  } catch (error) {
    next(error);
  }
};

/**
 * Get appointment by ID
 */
const getAppointmentById = async (req, res, next) => {
  try {
    const { id } = req.params;

    const appointment = await Appointment.findByPk(id, {
      include: [
        {
          model: Patient,
          as: 'patient',
          include: [
            {
              model: User,
              as: 'user',
              attributes: ['id', 'name', 'email', 'phone']
            }
          ]
        },
        {
          model: Doctor,
          as: 'doctor',
          include: [
            {
              model: User,
              as: 'user',
              attributes: ['id', 'name', 'email', 'phone', 'profile_photo']
            }
          ]
        }
      ]
    });

    if (!appointment) {
      return res.status(404).json({
        success: false,
        message: 'Appointment not found'
      });
    }

    res.status(200).json({
      success: true,
      data: appointment
    });
  } catch (error) {
    next(error);
  }
};

/**
 * Cancel appointment
 */
const cancelAppointment = async (req, res, next) => {
  try {
    const { id } = req.params;
    const { reason } = req.body;

    const appointment = await Appointment.findByPk(id);
    if (!appointment) {
      return res.status(404).json({
        success: false,
        message: 'Appointment not found'
      });
    }

    // Check if user is authorized to cancel
    const patient = await Patient.findOne({ where: { user_id: req.user.id } });
    const doctor = await Doctor.findOne({ where: { user_id: req.user.id } });

    if (appointment.patient_id !== patient?.id && appointment.doctor_id !== doctor?.id && req.user.role !== 'admin') {
      return res.status(403).json({
        success: false,
        message: 'Not authorized to cancel this appointment'
      });
    }

    // Check if appointment can be cancelled
    if (['completed', 'cancelled', 'no-show'].includes(appointment.status)) {
      return res.status(400).json({
        success: false,
        message: 'Cannot cancel this appointment'
      });
    }

    appointment.status = 'cancelled';
    appointment.cancellation_reason = reason;
    appointment.cancelled_at = new Date();
    await appointment.save();

    // Create notification
    await Notification.create({
      user_id: req.user.id,
      title: 'Appointment Cancelled',
      message: `Your appointment has been cancelled.`,
      type: 'appointment',
      related_id: appointment.id
    });

    res.status(200).json({
      success: true,
      message: 'Appointment cancelled successfully',
      data: appointment
    });
  } catch (error) {
    next(error);
  }
};

/**
 * Reschedule appointment
 */
const rescheduleAppointment = async (req, res, next) => {
  try {
    const { id } = req.params;
    const { date, time_slot } = req.body;

    const appointment = await Appointment.findByPk(id);
    if (!appointment) {
      return res.status(404).json({
        success: false,
        message: 'Appointment not found'
      });
    }

    // Check if user is authorized
    const patient = await Patient.findOne({ where: { user_id: req.user.id } });
    if (appointment.patient_id !== patient?.id && req.user.role !== 'admin') {
      return res.status(403).json({
        success: false,
        message: 'Not authorized to reschedule this appointment'
      });
    }

    // Check if new slot is available
    const existingAppointment = await Appointment.findOne({
      where: {
        doctor_id: appointment.doctor_id,
        date,
        time_slot,
        status: { [Op.notIn]: ['cancelled', 'no-show'] },
        id: { [Op.ne]: id }
      }
    });

    if (existingAppointment) {
      return res.status(409).json({
        success: false,
        message: 'This time slot is already booked'
      });
    }

    appointment.date = date;
    appointment.time_slot = time_slot;
    appointment.status = 'pending';
    await appointment.save();

    res.status(200).json({
      success: true,
      message: 'Appointment rescheduled successfully',
      data: appointment
    });
  } catch (error) {
    next(error);
  }
};

/**
 * Confirm appointment (for doctors)
 */
const confirmAppointment = async (req, res, next) => {
  try {
    const { id } = req.params;

    const appointment = await Appointment.findByPk(id);
    if (!appointment) {
      return res.status(404).json({
        success: false,
        message: 'Appointment not found'
      });
    }

    if (appointment.status !== 'pending') {
      return res.status(400).json({
        success: false,
        message: 'Cannot confirm this appointment'
      });
    }

    appointment.status = 'confirmed';
    await appointment.save();

    res.status(200).json({
      success: true,
      message: 'Appointment confirmed successfully',
      data: appointment
    });
  } catch (error) {
    next(error);
  }
};

/**
 * Complete appointment
 */
const completeAppointment = async (req, res, next) => {
  try {
    const { id } = req.params;

    const appointment = await Appointment.findByPk(id);
    if (!appointment) {
      return res.status(404).json({
        success: false,
        message: 'Appointment not found'
      });
    }

    if (appointment.status !== 'confirmed') {
      return res.status(400).json({
        success: false,
        message: 'Cannot complete this appointment'
      });
    }

    appointment.status = 'completed';
    await appointment.save();

    res.status(200).json({
      success: true,
      message: 'Appointment completed successfully',
      data: appointment
    });
  } catch (error) {
    next(error);
  }
};

module.exports = {
  bookAppointment,
  getPatientAppointments,
  getAppointmentById,
  cancelAppointment,
  rescheduleAppointment,
  confirmAppointment,
  completeAppointment
};
