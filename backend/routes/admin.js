const express = require('express');
const router = express.Router();
const adminController = require('../controllers/adminController');
const { authenticate, isAdmin } = require('../middleware/auth');

// All routes require admin access
router.get('/dashboard', authenticate, isAdmin, adminController.getDashboardStats);
router.get('/users', authenticate, isAdmin, adminController.getAllUsers);
router.get('/doctors', authenticate, isAdmin, adminController.getAllDoctorsAdmin);
router.post('/doctors', authenticate, isAdmin, adminController.createDoctor);
router.put('/doctors/:id/approve', authenticate, isAdmin, adminController.approveDoctor);
router.put('/doctors/:id/suspend', authenticate, isAdmin, adminController.suspendDoctor);
router.put('/users/:id/deactivate', authenticate, isAdmin, adminController.deactivateUser);
router.put('/users/:id/activate', authenticate, isAdmin, adminController.activateUser);
router.get('/appointments', authenticate, isAdmin, adminController.getAllAppointmentsAdmin);
router.get('/analytics', authenticate, isAdmin, adminController.getAnalytics);

module.exports = router;
