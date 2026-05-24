const { Review, Doctor, Patient, User, Appointment } = require('../models');

/**
 * Create review
 */
const createReview = async (req, res, next) => {
  try {
    const { doctor_id, appointment_id, rating, comment } = req.body;

    const patient = await Patient.findOne({ where: { user_id: req.user.id } });
    if (!patient) {
      return res.status(404).json({
        success: false,
        message: 'Patient profile not found'
      });
    }

    // Check if appointment exists and belongs to patient
    const appointment = await Appointment.findOne({
      where: {
        id: appointment_id,
        patient_id: patient.id,
        doctor_id
      }
    });

    if (!appointment) {
      return res.status(404).json({
        success: false,
        message: 'Appointment not found or does not belong to this patient'
      });
    }

    // Check if appointment is completed
    if (appointment.status !== 'completed') {
      return res.status(400).json({
        success: false,
        message: 'Can only review completed appointments'
      });
    }

    // Check if review already exists
    const existingReview = await Review.findOne({
      where: {
        patient_id: patient.id,
        appointment_id
      }
    });

    if (existingReview) {
      return res.status(409).json({
        success: false,
        message: 'Review already exists for this appointment'
      });
    }

    // Create review
    const review = await Review.create({
      patient_id: patient.id,
      doctor_id,
      appointment_id,
      rating,
      comment
    });

    // Update doctor rating
    const doctor = await Doctor.findByPk(doctor_id);
    const reviews = await Review.findAll({ where: { doctor_id } });
    
    const totalRating = reviews.reduce((sum, r) => sum + r.rating, 0);
    const avgRating = totalRating / reviews.length;

    doctor.rating = parseFloat(avgRating.toFixed(2));
    doctor.total_reviews = reviews.length;
    await doctor.save();

    res.status(201).json({
      success: true,
      message: 'Review created successfully',
      data: review
    });
  } catch (error) {
    next(error);
  }
};

/**
 * Get doctor's reviews
 */
const getDoctorReviews = async (req, res, next) => {
  try {
    const { doctor_id } = req.params;

    const reviews = await Review.findAll({
      where: { 
        doctor_id,
        is_approved: true,
        is_flagged: false
      },
      include: [
        {
          model: Patient,
          include: [
            {
              model: User,
              attributes: ['id', 'name']
            }
          ]
        }
      ],
      order: [['created_at', 'DESC']]
    });

    res.status(200).json({
      success: true,
      data: reviews
    });
  } catch (error) {
    next(error);
  }
};

/**
 * Get patient's reviews
 */
const getPatientReviews = async (req, res, next) => {
  try {
    const user = req.user;

    const patient = await Patient.findOne({ where: { user_id: user.id } });
    if (!patient) {
      return res.status(404).json({
        success: false,
        message: 'Patient profile not found'
      });
    }

    const reviews = await Review.findAll({
      where: { patient_id: patient.id },
      include: [
        {
          model: Doctor,
          include: [
            {
              model: User,
              attributes: ['id', 'name']
            }
          ]
        }
      ],
      order: [['created_at', 'DESC']]
    });

    res.status(200).json({
      success: true,
      data: reviews
    });
  } catch (error) {
    next(error);
  }
};

module.exports = {
  createReview,
  getDoctorReviews,
  getPatientReviews
};
