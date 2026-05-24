const express = require('express');
const router = express.Router();
const doctorController = require('../controllers/doctorController');
const { authenticate, isDoctor, isDoctorOrAdmin } = require('../middleware/auth');
const { validate, doctorProfileValidation } = require('../middleware/validation');

// Public routes
router.get('/', doctorController.getAllDoctors);

// Protected routes (doctor only) - MUST come before /:id routes
router.get('/my/stats', authenticate, isDoctor, doctorController.getDoctorStats);
router.get('/my/appointments', authenticate, isDoctor, doctorController.getDoctorAppointments);
router.put('/profile', authenticate, isDoctor, doctorProfileValidation, validate, doctorController.updateDoctorProfile);
router.put('/time-slots', authenticate, isDoctor, doctorController.setTimeSlots);

// Public routes - specific ID routes
router.get('/:id/time-slots', doctorController.getTimeSlots);
router.get('/:id', doctorController.getDoctorById);

module.exports = router;
