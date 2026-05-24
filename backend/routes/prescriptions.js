const express = require('express');
const router = express.Router();
const prescriptionController = require('../controllers/prescriptionController');
const { authenticate, isDoctor, isPatient } = require('../middleware/auth');
const { validate, prescriptionValidation } = require('../middleware/validation');

// Protected routes
router.post('/', authenticate, isDoctor, prescriptionValidation, validate, prescriptionController.createPrescription);
router.get('/my', authenticate, isPatient, prescriptionController.getPatientPrescriptions);
router.get('/:id', authenticate, prescriptionController.getPrescriptionById);

module.exports = router;
