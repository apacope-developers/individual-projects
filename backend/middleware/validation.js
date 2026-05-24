const { body, validationResult } = require('express-validator');

/**
 * Validation middleware
 */
const validate = (req, res, next) => {
  const errors = validationResult(req);
  if (!errors.isEmpty()) {
    return res.status(400).json({
      success: false,
      message: 'Validation error',
      errors: errors.array()
    });
  }
  next();
};

/**
 * Registration validation rules
 */
const registerValidation = [
  body('name')
    .trim()
    .notEmpty().withMessage('Name is required')
    .isLength({ min: 2, max: 100 }).withMessage('Name must be between 2 and 100 characters'),
  body('email')
    .trim()
    .notEmpty().withMessage('Email is required')
    .isEmail().withMessage('Invalid email format')
    .normalizeEmail(),
  body('password')
    .notEmpty().withMessage('Password is required')
    .isLength({ min: 6 }).withMessage('Password must be at least 6 characters'),
  body('role')
    .optional()
    .isIn(['admin', 'doctor', 'patient']).withMessage('Invalid role'),
  body('phone')
    .optional({ values: 'falsy' })
    .trim()
    .custom((value) => !value || /^\d+$/.test(value))
    .withMessage('Phone must contain only numbers')
];

/**
 * Login validation rules
 */
const loginValidation = [
  body('email')
    .trim()
    .notEmpty().withMessage('Email is required')
    .isEmail().withMessage('Invalid email format')
    .normalizeEmail(),
  body('password')
    .notEmpty().withMessage('Password is required')
];

/**
 * Doctor profile validation
 */
const doctorProfileValidation = [
  body('specialty')
    .trim()
    .notEmpty().withMessage('Specialty is required'),
  body('qualification')
    .trim()
    .notEmpty().withMessage('Qualification is required'),
  body('experience')
    .isInt({ min: 0 }).withMessage('Experience must be a positive number'),
  body('consultation_fee')
    .isFloat({ min: 0 }).withMessage('Consultation fee must be a positive number')
];

/**
 * Patient profile validation
 */
const patientProfileValidation = [
  body('dob')
    .optional()
    .isISO8601().withMessage('Invalid date of birth'),
  body('gender')
    .optional()
    .isIn(['male', 'female', 'other']).withMessage('Invalid gender'),
  body('blood_group')
    .optional()
    .isIn(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']).withMessage('Invalid blood group')
];

/**
 * Appointment booking validation
 */
const appointmentValidation = [
  body('doctor_id')
    .notEmpty().withMessage('Doctor ID is required')
    .isInt().withMessage('Invalid doctor ID'),
  body('date')
    .notEmpty().withMessage('Date is required')
    .isISO8601().withMessage('Invalid date format'),
  body('time_slot')
    .notEmpty().withMessage('Time slot is required')
    .matches(/^\d{2}:\d{2}-\d{2}:\d{2}$/).withMessage('Invalid time slot format (HH:MM-HH:MM)'),
  body('type')
    .optional()
    .isIn(['in-person', 'video']).withMessage('Invalid appointment type'),
  body('reason')
    .optional()
    .trim()
    .isLength({ max: 500 }).withMessage('Reason must be less than 500 characters')
];

/**
 * Review validation
 */
const reviewValidation = [
  body('doctor_id')
    .notEmpty().withMessage('Doctor ID is required')
    .isInt().withMessage('Invalid doctor ID'),
  body('appointment_id')
    .notEmpty().withMessage('Appointment ID is required')
    .isInt().withMessage('Invalid appointment ID'),
  body('rating')
    .notEmpty().withMessage('Rating is required')
    .isInt({ min: 1, max: 5 }).withMessage('Rating must be between 1 and 5'),
  body('comment')
    .optional()
    .trim()
    .isLength({ max: 1000 }).withMessage('Comment must be less than 1000 characters')
];

/**
 * Prescription validation
 */
const prescriptionValidation = [
  body('consultation_id')
    .notEmpty().withMessage('Consultation ID is required')
    .isInt().withMessage('Invalid consultation ID'),
  body('medications')
    .isArray({ min: 1 }).withMessage('At least one medication is required'),
  body('medications.*.name')
    .notEmpty().withMessage('Medication name is required'),
  body('medications.*.dosage')
    .notEmpty().withMessage('Dosage is required'),
  body('medications.*.duration')
    .notEmpty().withMessage('Duration is required'),
  body('instructions')
    .optional()
    .trim()
    .isLength({ max: 1000 }).withMessage('Instructions must be less than 1000 characters')
];

module.exports = {
  validate,
  registerValidation,
  loginValidation,
  doctorProfileValidation,
  patientProfileValidation,
  appointmentValidation,
  reviewValidation,
  prescriptionValidation
};
