const express = require('express');
const router = express.Router();
const reviewController = require('../controllers/reviewController');
const { authenticate, isPatient } = require('../middleware/auth');
const { validate, reviewValidation } = require('../middleware/validation');

// Public routes
router.get('/doctor/:doctor_id', reviewController.getDoctorReviews);

// Protected routes
router.post('/', authenticate, isPatient, reviewValidation, validate, reviewController.createReview);
router.get('/my', authenticate, isPatient, reviewController.getPatientReviews);

module.exports = router;
