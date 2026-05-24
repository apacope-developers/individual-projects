const jwt = require('jsonwebtoken');
const { User } = require('../models');
require('dotenv').config();

/**
 * Verify JWT token and attach user to request
 */
const authenticate = async (req, res, next) => {
  try {
    const token = req.header('Authorization')?.replace('Bearer ', '');
    
    if (!token) {
      return res.status(401).json({ 
        success: false, 
        message: 'Access denied. No token provided.' 
      });
    }

    const decoded = jwt.verify(token, process.env.JWT_SECRET);
    
    const user = await User.findByPk(decoded.id, {
      attributes: { exclude: ['password', 'refresh_token'] }
    });

    if (!user || !user.is_active) {
      return res.status(401).json({ 
        success: false, 
        message: 'Invalid token or user not active.' 
      });
    }

    req.user = user;
    next();
  } catch (error) {
    if (error.name === 'TokenExpiredError') {
      return res.status(401).json({ 
        success: false, 
        message: 'Token expired.' 
      });
    }
    return res.status(401).json({ 
      success: false, 
      message: 'Invalid token.' 
    });
  }
};

/**
 * Check if user has required role
 */
const authorize = (...roles) => {
  return (req, res, next) => {
    if (!roles.includes(req.user.role)) {
      return res.status(403).json({ 
        success: false, 
        message: 'Access denied. Insufficient permissions.' 
      });
    }
    next();
  };
};

/**
 * Check if user is admin
 */
const isAdmin = authorize('admin');

/**
 * Check if user is doctor
 */
const isDoctor = authorize('doctor');

/**
 * Check if user is patient
 */
const isPatient = authorize('patient');

/**
 * Check if user is doctor or admin
 */
const isDoctorOrAdmin = authorize('doctor', 'admin');

/**
 * Check if user is patient or admin
 */
const isPatientOrAdmin = authorize('patient', 'admin');

module.exports = {
  authenticate,
  authorize,
  isAdmin,
  isDoctor,
  isPatient,
  isDoctorOrAdmin,
  isPatientOrAdmin
};
