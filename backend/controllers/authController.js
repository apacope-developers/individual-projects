const { User, Doctor, Patient } = require('../models');
const { generateAccessToken, generateRefreshToken } = require('../utils/jwt');
const { sendWelcomeEmail } = require('../utils/email');

/**
 * Register new user
 */
const register = async (req, res, next) => {
  try {
    const { name, email, password, role, phone } = req.body;
    const normalizedEmail = String(email).trim().toLowerCase();

    // Only patients can self-register
    if (role && role !== 'patient') {
      return res.status(403).json({
        success: false,
        message: 'Only patient registration is allowed. Doctor and admin accounts must be created by an administrator.'
      });
    }

    // Check if user already exists
    const existingUser = await User.findOne({ where: { email: normalizedEmail } });
    if (existingUser) {
      return res.status(409).json({
        success: false,
        message: 'Email already registered'
      });
    }

    const sanitizedPhone = phone && String(phone).trim() ? String(phone).replace(/\D/g, '') : null;

    // Create user
    const user = await User.create({
      name,
      email: normalizedEmail,
      password,
      role: 'patient',
      phone: sanitizedPhone
    });

    const userRole = 'patient';

    // Create role-specific profile
    if (userRole === 'patient') {
      await Patient.create({
        user_id: user.id
      });
    }

    // Generate tokens
    const accessToken = generateAccessToken(user);
    const refreshToken = generateRefreshToken(user);

    // Save refresh token to database
    user.refresh_token = refreshToken;
    await user.save();

    // Send welcome email
    try {
      await sendWelcomeEmail(normalizedEmail, name);
    } catch (emailError) {
      console.error('Email error:', emailError);
    }

    res.status(201).json({
      success: true,
      message: 'Registration successful',
      data: {
        user: user.toJSON(),
        accessToken,
        refreshToken
      }
    });
  } catch (error) {
    next(error);
  }
};

/**
 * Login user
 */
const login = async (req, res, next) => {
  try {
    const { email, password } = req.body;
    const normalizedEmail = String(email).trim().toLowerCase();

    // Find user with email (always lowercase for consistency with registration)
    const user = await User.findOne({ where: { email: normalizedEmail } });
    if (!user) {
      return res.status(401).json({
        success: false,
        message: 'Invalid credentials'
      });
    }

    // Check if user is active
    if (!user.is_active) {
      return res.status(401).json({
        success: false,
        message: 'Account is deactivated'
      });
    }

    // Verify password
    const isPasswordValid = await user.comparePassword(password);
    if (!isPasswordValid) {
      return res.status(401).json({
        success: false,
        message: 'Invalid credentials'
      });
    }

    // Generate tokens
    const accessToken = generateAccessToken(user);
    const refreshToken = generateRefreshToken(user);

    // Save refresh token to database
    user.refresh_token = refreshToken;
    await user.save();

    res.status(200).json({
      success: true,
      message: 'Login successful',
      data: {
        user: user.toJSON(),
        accessToken,
        refreshToken
      }
    });
  } catch (error) {
    next(error);
  }
};

/**
 * Refresh access token
 */
const refreshToken = async (req, res, next) => {
  try {
    const { refreshToken } = req.body;

    if (!refreshToken) {
      return res.status(400).json({
        success: false,
        message: 'Refresh token is required'
      });
    }

    // Find user with refresh token
    const user = await User.findOne({ where: { refresh_token: refreshToken } });
    if (!user) {
      return res.status(401).json({
        success: false,
        message: 'Invalid refresh token'
      });
    }

    // Generate new tokens
    const newAccessToken = generateAccessToken(user);
    const newRefreshToken = generateRefreshToken(user);

    // Update refresh token
    user.refresh_token = newRefreshToken;
    await user.save();

    res.status(200).json({
      success: true,
      data: {
        accessToken: newAccessToken,
        refreshToken: newRefreshToken
      }
    });
  } catch (error) {
    next(error);
  }
};

/**
 * Logout user
 */
const logout = async (req, res, next) => {
  try {
    const user = req.user;
    user.refresh_token = null;
    await user.save();

    res.status(200).json({
      success: true,
      message: 'Logout successful'
    });
  } catch (error) {
    next(error);
  }
};

/**
 * Logout without requiring valid token (clears refresh token if user is found)
 */
const logoutOptional = async (req, res, next) => {
  try {
    const token = req.header('Authorization')?.replace('Bearer ', '');
    if (token) {
      try {
        const jwt = require('jsonwebtoken');
        const decoded = jwt.verify(token, process.env.JWT_SECRET);
        const user = await User.findByPk(decoded.id);
        if (user) {
          user.refresh_token = null;
          await user.save();
        }
      } catch {
        // Token invalid or expired — still return success for client cleanup
      }
    }
    res.status(200).json({ success: true, message: 'Logout successful' });
  } catch (error) {
    next(error);
  }
};

/**
 * Get current user profile
 */
const getProfile = async (req, res, next) => {
  try {
    const user = req.user;
    let profileData = user.toJSON();

    // Include role-specific data
    if (user.role === 'doctor') {
      const doctor = await Doctor.findOne({ where: { user_id: user.id } });
      if (doctor) {
        profileData = { ...profileData, doctorProfile: doctor };
      }
    } else if (user.role === 'patient') {
      const patient = await Patient.findOne({ where: { user_id: user.id } });
      if (patient) {
        profileData = { ...profileData, patientProfile: patient };
      }
    }

    res.status(200).json({
      success: true,
      data: profileData
    });
  } catch (error) {
    next(error);
  }
};

/**
 * Update user profile
 */
const updateProfile = async (req, res, next) => {
  try {
    const user = req.user;
    const { name, phone } = req.body;

    if (name) user.name = name;
    if (phone !== undefined) {
      user.phone = phone && String(phone).trim() ? String(phone).replace(/\D/g, '') : null;
    }

    await user.save();

    res.status(200).json({
      success: true,
      message: 'Profile updated successfully',
      data: user.toJSON()
    });
  } catch (error) {
    next(error);
  }
};

/**
 * Upload profile photo
 */
const uploadProfilePhoto = async (req, res, next) => {
  try {
    if (!req.file) {
      return res.status(400).json({
        success: false,
        message: 'No image file provided'
      });
    }

    const user = req.user;
    const photoUrl = `/uploads/profiles/${req.file.filename}`;
    user.profile_photo = photoUrl;
    await user.save();

    res.status(200).json({
      success: true,
      message: 'Profile photo updated',
      data: { profile_photo: photoUrl }
    });
  } catch (error) {
    next(error);
  }
};

/**
 * Change user password
 */
const changePassword = async (req, res, next) => {
  try {
    const user = req.user;
    const { currentPassword, newPassword, confirmPassword } = req.body;

    if (!currentPassword || !newPassword || !confirmPassword) {
      return res.status(400).json({
        success: false,
        message: 'All password fields are required'
      });
    }

    if (newPassword !== confirmPassword) {
      return res.status(400).json({
        success: false,
        message: 'New passwords do not match'
      });
    }

    if (newPassword.length < 6) {
      return res.status(400).json({
        success: false,
        message: 'New password must be at least 6 characters long'
      });
    }

    // Verify current password
    const isPasswordValid = await user.comparePassword(currentPassword);
    if (!isPasswordValid) {
      return res.status(401).json({
        success: false,
        message: 'Current password is incorrect'
      });
    }

    // Update password
    user.password = newPassword;
    await user.save();

    res.status(200).json({
      success: true,
      message: 'Password changed successfully'
    });
  } catch (error) {
    next(error);
  }
};

module.exports = {
  register,
  login,
  refreshToken,
  logout,
  logoutOptional,
  getProfile,
  updateProfile,
  uploadProfilePhoto,
  changePassword
};
