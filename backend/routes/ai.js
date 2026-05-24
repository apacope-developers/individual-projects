const express = require('express');
const router = express.Router();
const aiController = require('../controllers/aiController');
const { authenticate, isPatient } = require('../middleware/auth');

// Protected routes (patient only)
router.post('/check', authenticate, isPatient, aiController.submitSymptoms);
router.get('/check/:id', authenticate, aiController.getAICheckById);

module.exports = router;
