const express = require('express');
const router = express.Router();
const authController = require('../controllers/authController');
const { authenticate } = require('../middleware/auth');
const { validate, registerValidation, loginValidation } = require('../middleware/validation');
const { uploadSingle } = require('../middleware/upload');

// Public routes
router.post('/register', registerValidation, validate, authController.register);
router.post('/login', loginValidation, validate, authController.login);
router.post('/refresh-token', authController.refreshToken);

// Protected routes
router.post('/logout', authController.logoutOptional);
router.get('/profile', authenticate, authController.getProfile);
router.put('/profile', authenticate, authController.updateProfile);
router.post('/profile-photo', authenticate, uploadSingle('photo'), authController.uploadProfilePhoto);
router.post('/change-password', authenticate, authController.changePassword);

module.exports = router;
