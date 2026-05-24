const { Doctor, User, Appointment, Review, TimeSlot } = require('../models');
const { uploadSingle } = require('../middleware/upload');

/**
 * Get all doctors with filters
 */
const getAllDoctors = async (req, res, next) => {
  try {
    const { specialty, available, minRating, search } = req.query;

    const whereClause = { is_approved: true };
    
    if (specialty) whereClause.specialty = specialty;
    if (available !== undefined) whereClause.available = available === 'true';
    if (minRating) {
      whereClause.rating = { [require('sequelize').Op.gte]: parseFloat(minRating) };
    }

    const approvedCount = await Doctor.count({ where: { is_approved: true } });

    const doctors = await Doctor.findAll({
      where: whereClause,
      include: [
        {
          model: User,
          as: 'user',
          attributes: ['id', 'name', 'email', 'phone', 'profile_photo']
        }
      ],
      order: [['rating', 'DESC']]
    });

    // Filter by search term if provided
    let filteredDoctors = doctors;
    if (search) {
      const searchLower = search.toLowerCase();
      filteredDoctors = doctors.filter(doctor => 
        doctor.user?.name?.toLowerCase().includes(searchLower) ||
        doctor.specialty.toLowerCase().includes(searchLower)
      );
    }

    if (approvedCount === 0) {
      const sampleDoctors = [
        {
          id: 101,
          specialty: 'General Practice',
          qualification: 'MBBS, Family Medicine',
          experience: 12,
          bio: 'Experienced GP helping patients manage chronic conditions and preventive care.',
          consultation_fee: 45.00,
          available: true,
          rating: 4.8,
          total_reviews: 128,
          hospital: 'City Care Medical Center',
          location: 'Nairobi',
          user: {
            id: 101,
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
          consultation_fee: 55.00,
          available: true,
          rating: 4.7,
          total_reviews: 94,
          hospital: 'Wellness Dermatology Clinic',
          location: 'Nairobi',
          user: {
            id: 102,
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
          consultation_fee: 75.00,
          available: true,
          rating: 4.9,
          total_reviews: 142,
          hospital: 'Central Heart Institute',
          location: 'Nairobi',
          user: {
            id: 103,
            name: 'Dr. Paul Njoroge',
            email: 'paul.njoroge@example.com',
            phone: '+254 700 987654',
            profile_photo: ''
          }
        }
      ];

      return res.status(200).json({
        success: true,
        data: sampleDoctors,
        meta: { sample: true }
      });
    }

    res.status(200).json({
      success: true,
      data: filteredDoctors
    });
  } catch (error) {
    next(error);
  }
};

/**
 * Get doctor by ID
 */
const getDoctorById = async (req, res, next) => {
  try {
    const { id } = req.params;

let doctor = await Doctor.findByPk(id, {
    include: [
      {
        model: User,
        as: 'user',
        attributes: ['id', 'name', 'email', 'phone', 'profile_photo']
      },
      {
        model: Review,
        as: 'reviews',
        include: [
          {
            model: require('../models').Patient,
            as: 'patient',
            include: [
              {
                model: User,
                as: 'user',
                attributes: ['id', 'name', 'email', 'phone']
              }
            ]
          }
        ]
      },
      {
        model: TimeSlot,
        as: 'timeSlots',
        where: { is_available: true },
        required: false
      }
    ]
  });

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
          id: 101,
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
          id: 102,
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
          id: 103,
          name: 'Dr. Paul Njoroge',
          email: 'paul.njoroge@example.com',
          phone: '+254 700 987654',
          profile_photo: ''
        }
      }
    ];

    doctor = sampleDoctors.find((sample) => sample.id === parseInt(id, 10));
    if (!doctor) {
      return res.status(404).json({
        success: false,
        message: 'Doctor not found'
      });
    }
    }

    res.status(200).json({
      success: true,
      data: doctor
    });
  } catch (error) {
    next(error);
  }
};

/**
 * Update doctor profile
 */
const updateDoctorProfile = async (req, res, next) => {
  try {
    const user = req.user;
    const { specialty, qualification, experience, bio, consultation_fee } = req.body;

    const doctor = await Doctor.findOne({ where: { user_id: user.id } });
    if (!doctor) {
      return res.status(404).json({
        success: false,
        message: 'Doctor profile not found'
      });
    }

    if (specialty) doctor.specialty = specialty;
    if (qualification) doctor.qualification = qualification;
    if (experience !== undefined) doctor.experience = experience;
    if (bio !== undefined) doctor.bio = bio;
    if (consultation_fee !== undefined) doctor.consultation_fee = consultation_fee;

    await doctor.save();

    res.status(200).json({
      success: true,
      message: 'Profile updated successfully',
      data: doctor
    });
  } catch (error) {
    next(error);
  }
};

/**
 * Get doctor's appointments
 */
const getDoctorAppointments = async (req, res, next) => {
  try {
    const user = req.user;
    const { status, date } = req.query;

    const doctor = await Doctor.findOne({ where: { user_id: user.id } });
    if (!doctor) {
      return res.status(404).json({
        success: false,
        message: 'Doctor profile not found'
      });
    }

    const whereClause = { doctor_id: doctor.id };
    if (status) whereClause.status = status;
    if (date) whereClause.date = date;

    const appointments = await Appointment.findAll({
      where: whereClause,
      include: [
        {
          model: require('../models').Patient,
          as: 'patient',
          include: [
            {
              model: User,
              as: 'user',
              attributes: ['id', 'name', 'email', 'phone']
            }
          ]
        }
      ],
      order: [['date', 'ASC'], ['time_slot', 'ASC']]
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
 * Set doctor availability (time slots)
 */
const setTimeSlots = async (req, res, next) => {
  try {
    const user = req.user;
    const { timeSlots } = req.body;

    const doctor = await Doctor.findOne({ where: { user_id: user.id } });
    if (!doctor) {
      return res.status(404).json({
        success: false,
        message: 'Doctor profile not found'
      });
    }

    // Delete existing time slots
    await TimeSlot.destroy({ where: { doctor_id: doctor.id } });

    // Create new time slots
    const slots = await Promise.all(
      timeSlots.map(slot =>
        TimeSlot.create({
          doctor_id: doctor.id,
          day_of_week: slot.day_of_week,
          start_time: slot.start_time,
          end_time: slot.end_time,
          is_available: true,
          break_start: slot.break_start || null,
          break_end: slot.break_end || null
        })
      )
    );

    res.status(200).json({
      success: true,
      message: 'Time slots updated successfully',
      data: slots
    });
  } catch (error) {
    next(error);
  }
};

/**
 * Get doctor's time slots
 */
const getTimeSlots = async (req, res, next) => {
  try {
    const { id } = req.params;

    const timeSlots = await TimeSlot.findAll({
      where: { 
        doctor_id: id,
        is_available: true
      },
      order: [['day_of_week', 'ASC']]
    });

    res.status(200).json({
      success: true,
      data: timeSlots
    });
  } catch (error) {
    next(error);
  }
};

/**
 * Get doctor statistics
 */
const getDoctorStats = async (req, res, next) => {
  try {
    const user = req.user;

    const doctor = await Doctor.findOne({ where: { user_id: user.id } });
    if (!doctor) {
      return res.status(404).json({
        success: false,
        message: 'Doctor profile not found'
      });
    }

    const totalAppointments = await Appointment.count({
      where: { doctor_id: doctor.id }
    });

    const completedAppointments = await Appointment.count({
      where: { doctor_id: doctor.id, status: 'completed' }
    });

    const upcomingAppointments = await Appointment.count({
      where: { 
        doctor_id: doctor.id, 
        status: 'confirmed',
        date: { [require('sequelize').Op.gte]: new Date() }
      }
    });

    const totalReviews = await Review.count({
      where: { doctor_id: doctor.id }
    });

    const totalEarnings = await require('../models').Payment.sum('amount', {
      where: { 
        status: 'completed',
        '$appointment.doctor_id$': doctor.id
      },
      include: [
        {
          model: Appointment,
          as: 'appointment',
          attributes: [],
          required: false
        }
      ]
    }) || 0;

    res.status(200).json({
      success: true,
      data: {
        totalAppointments,
        completedAppointments,
        upcomingAppointments,
        totalReviews,
        rating: parseFloat(doctor.rating) || 0,
        totalEarnings: parseFloat(totalEarnings) || 0
      }
    });
  } catch (error) {
    next(error);
  }
};

module.exports = {
  getAllDoctors,
  getDoctorById,
  updateDoctorProfile,
  getDoctorAppointments,
  setTimeSlots,
  getTimeSlots,
  getDoctorStats
};
