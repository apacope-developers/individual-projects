const express = require('express');
const router = express.Router();
const appointmentController = require('../controllers/appointmentController');
const { authenticate, isDoctor, isPatientOrAdmin } = require('../middleware/auth');
const { validate, appointmentValidation } = require('../middleware/validation');

// Protected routes
router.post('/', authenticate, isPatientOrAdmin, appointmentValidation, validate, appointmentController.bookAppointment);
router.get('/my', authenticate, isPatientOrAdmin, appointmentController.getPatientAppointments);
router.get('/:id', authenticate, appointmentController.getAppointmentById);
router.put('/:id/cancel', authenticate, appointmentController.cancelAppointment);
router.put('/:id/reschedule', authenticate, appointmentController.rescheduleAppointment);
router.put('/:id/confirm', authenticate, isDoctor, appointmentController.confirmAppointment);
router.put('/:id/complete', authenticate, isDoctor, appointmentController.completeAppointment);

module.exports = router;
