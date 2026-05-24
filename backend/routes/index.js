const express = require('express');
const router = express.Router();

// Import route modules
const authRoutes = require('./auth');
const doctorRoutes = require('./doctors');
const appointmentRoutes = require('./appointments');
const patientRoutes = require('./patients');
const aiRoutes = require('./ai');
const prescriptionRoutes = require('./prescriptions');
const reviewRoutes = require('./reviews');
const notificationRoutes = require('./notifications');
const adminRoutes = require('./admin');

// Mount routes
router.use('/auth', authRoutes);
router.use('/doctors', doctorRoutes);
router.use('/appointments', appointmentRoutes);
router.use('/patients', patientRoutes);
router.use('/ai', aiRoutes);
router.use('/prescriptions', prescriptionRoutes);
router.use('/reviews', reviewRoutes);
router.use('/notifications', notificationRoutes);
router.use('/admin', adminRoutes);

module.exports = router;
