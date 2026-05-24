const express = require('express');
const router = express.Router();
const patientController = require('../controllers/patientController');
const { authenticate, isPatient } = require('../middleware/auth');
const { validate, patientProfileValidation } = require('../middleware/validation');
const { uploadSingle } = require('../middleware/upload');

// Protected routes (patient only)
router.put('/profile', authenticate, isPatient, patientProfileValidation, validate, patientController.updatePatientProfile);
router.get('/records', authenticate, isPatient, patientController.getMedicalRecords);
router.post('/records/upload', authenticate, isPatient, uploadSingle('file'), patientController.uploadMedicalRecord);
router.delete('/records/:id', authenticate, isPatient, patientController.deleteMedicalRecord);
router.get('/ai-checks', authenticate, isPatient, patientController.getAICheckHistory);
router.get('/dashboard', authenticate, isPatient, patientController.getPatientDashboard);

module.exports = router;
