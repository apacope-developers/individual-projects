const { Prescription, Consultation, Doctor, Patient, User } = require('../models');
const { generatePrescriptionPDF } = require('../utils/pdf');
const { sendPrescriptionReady } = require('../utils/email');

/**
 * Create prescription
 */
const createPrescription = async (req, res, next) => {
  try {
    const { consultation_id, medications, instructions } = req.body;

    const consultation = await Consultation.findByPk(consultation_id, {
      include: [
        {
          model: require('../models').Appointment,
          as: 'appointment',
          include: [
            {
              model: Doctor,
              as: 'doctor'
            },
            {
              model: Patient,
              as: 'patient'
            }
          ]
        }
      ]
    });

    if (!consultation) {
      return res.status(404).json({
        success: false,
        message: 'Consultation not found'
      });
    }

    // Check if user is the doctor
    const doctor = await Doctor.findOne({ where: { user_id: req.user.id } });
    if (consultation.appointment.doctor_id !== doctor?.id && req.user.role !== 'admin') {
      return res.status(403).json({
        success: false,
        message: 'Not authorized to create prescription for this consultation'
      });
    }

    // Create prescription
    const prescription = await Prescription.create({
      consultation_id,
      doctor_id: consultation.appointment.doctor_id,
      patient_id: consultation.appointment.patient_id,
      medications,
      instructions
    });

    // Generate PDF
    try {
      const doctorUser = await User.findByPk(consultation.appointment.doctor.user_id);
      const patientUser = await User.findByPk(consultation.appointment.patient.user_id);

      const pdfFileName = await generatePrescriptionPDF({
        patientName: patientUser.name,
        doctorName: doctorUser.name,
        doctorSpecialty: consultation.appointment.doctor.specialty,
        medications,
        instructions,
        date: new Date()
      });

      prescription.pdf_url = `/uploads/prescriptions/${pdfFileName}`;
      await prescription.save();

      // Send email notification
      try {
        await sendPrescriptionReady(patientUser.email, patientUser.name, prescription.id);
      } catch (emailError) {
        console.error('Email error:', emailError);
      }
    } catch (pdfError) {
      console.error('PDF generation error:', pdfError);
    }

    res.status(201).json({
      success: true,
      message: 'Prescription created successfully',
      data: prescription
    });
  } catch (error) {
    next(error);
  }
};

/**
 * Get patient's prescriptions
 */
const getPatientPrescriptions = async (req, res, next) => {
  try {
    const user = req.user;

    const patient = await Patient.findOne({ where: { user_id: user.id } });
    if (!patient) {
      return res.status(404).json({
        success: false,
        message: 'Patient profile not found'
      });
    }

    const prescriptions = await Prescription.findAll({
      where: { patient_id: patient.id },
      include: [
        {
          model: Doctor,
          as: 'doctor',
          include: [
            {
              model: User,
              as: 'user',
              attributes: ['id', 'name']
            }
          ]
        },
        {
          model: Consultation,
          as: 'consultation',
          include: [
            {
              model: require('../models').Appointment,
              as: 'appointment'
            }
          ]
        }
      ],
      order: [['createdAt', 'DESC']]
    });

    res.status(200).json({
      success: true,
      data: prescriptions
    });
  } catch (error) {
    next(error);
  }
};

/**
 * Get prescription by ID
 */
const getPrescriptionById = async (req, res, next) => {
  try {
    const { id } = req.params;

    const prescription = await Prescription.findByPk(id, {
      include: [
        {
          model: Doctor,
          as: 'doctor',
          include: [
            {
              model: User,
              as: 'user',
              attributes: ['id', 'name']
            }
          ]
        },
        {
          model: Patient,
          as: 'patient',
          include: [
            {
              model: User,
              as: 'user',
              attributes: ['id', 'name']
            }
          ]
        },
        {
          model: Consultation,
          as: 'consultation'
        }
      ]
    });

    if (!prescription) {
      return res.status(404).json({
        success: false,
        message: 'Prescription not found'
      });
    }

    // Check authorization
    const patient = await Patient.findOne({ where: { user_id: req.user.id } });
    const doctor = await Doctor.findOne({ where: { user_id: req.user.id } });

    if (prescription.patient_id !== patient?.id && 
        prescription.doctor_id !== doctor?.id && 
        req.user.role !== 'admin') {
      return res.status(403).json({
        success: false,
        message: 'Not authorized to view this prescription'
      });
    }

    res.status(200).json({
      success: true,
      data: prescription
    });
  } catch (error) {
    next(error);
  }
};

module.exports = {
  createPrescription,
  getPatientPrescriptions,
  getPrescriptionById
};
