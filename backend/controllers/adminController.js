const { User, Doctor, Patient, Appointment, Payment, Review } = require('../models');
const { Op } = require('sequelize');

/**
 * Get admin dashboard statistics
 */
const getDashboardStats = async (req, res, next) => {
  try {
    const totalUsers = await User.count();
    const totalDoctors = await Doctor.count();
    const totalPatients = await Patient.count();
    const totalAppointments = await Appointment.count();
    const completedAppointments = await Appointment.count({ where: { status: 'completed' } });
    const pendingDoctors = await Doctor.count({ where: { is_approved: false } });

    const totalRevenue = await Payment.sum('amount', {
      where: { status: 'completed' }
    }) || 0;

    const recentAppointments = await Appointment.findAll({
      limit: 10,
      order: [['createdAt', 'DESC']],
      include: [
        {
          model: Doctor,
          as: 'doctor',
          include: [
            {
              model: User,
              as: 'user',
              attributes: ['id', 'name', 'email', 'profile_photo']
            }
          ]
        },
        {
          model: Patient,
          as: 'patient',
          include: [
            {
              model: User,
              as: 'user',
              attributes: ['id', 'name', 'email', 'profile_photo']
            }
          ]
        }
      ]
    });

    res.status(200).json({
      success: true,
      data: {
        totalUsers,
        totalDoctors,
        totalPatients,
        totalAppointments,
        completedAppointments,
        pendingDoctors,
        totalRevenue,
        recentAppointments
      }
    });
  } catch (error) {
    next(error);
  }
};

/**
 * Get all users
 */
const getAllUsers = async (req, res, next) => {
  try {
    const { role, is_active } = req.query;

    const whereClause = {};
    if (role) whereClause.role = role;
    if (is_active !== undefined) whereClause.is_active = is_active === 'true';

    const users = await User.findAll({
      where: whereClause,
      attributes: { exclude: ['password', 'refresh_token'] },
      order: [['createdAt', 'DESC']]
    });

    res.status(200).json({
      success: true,
      data: users
    });
  } catch (error) {
    next(error);
  }
};

/**
 * Get all doctors (admin view)
 */
const getAllDoctorsAdmin = async (req, res, next) => {
  try {
    const { is_approved } = req.query;

    const whereClause = {};
    if (is_approved !== undefined) whereClause.is_approved = is_approved === 'true';

    const doctors = await Doctor.findAll({
      where: whereClause,
      include: [
        {
          model: User,
          as: 'user',
          attributes: ['id', 'name', 'email', 'phone', 'profile_photo', 'is_active', 'createdAt']
        }
      ],
      order: [['createdAt', 'DESC']]
    });

    res.status(200).json({
      success: true,
      data: doctors
    });
  } catch (error) {
    next(error);
  }
};

/**
 * Create doctor account (admin only)
 */
const createDoctor = async (req, res, next) => {
  try {
    const {
      name,
      email,
      password,
      phone,
      specialty,
      qualification,
      experience,
      consultation_fee,
      bio
    } = req.body;

    const existingUser = await User.findOne({ where: { email } });
    if (existingUser) {
      return res.status(409).json({
        success: false,
        message: 'Email already registered'
      });
    }

    const sanitizedPhone = phone && String(phone).trim() ? String(phone).replace(/\D/g, '') : null;

    const user = await User.create({
      name,
      email,
      password,
      role: 'doctor',
      phone: sanitizedPhone
    });

    const doctor = await Doctor.create({
      user_id: user.id,
      specialty: specialty || 'General Practice',
      qualification: qualification || 'MBBS',
      experience: experience ?? 0,
      consultation_fee: consultation_fee ?? 50,
      bio: bio || null,
      is_approved: true,
      available: true
    });

    const doctorWithUser = await Doctor.findByPk(doctor.id, {
      include: [{ model: User, as: 'user', attributes: { exclude: ['password', 'refresh_token'] } }]
    });

    res.status(201).json({
      success: true,
      message: 'Doctor account created successfully',
      data: doctorWithUser
    });
  } catch (error) {
    next(error);
  }
};

/**
 * Approve doctor
 */
const approveDoctor = async (req, res, next) => {
  try {
    const { id } = req.params;

    const doctor = await Doctor.findByPk(id);
    if (!doctor) {
      return res.status(404).json({
        success: false,
        message: 'Doctor not found'
      });
    }

    doctor.is_approved = true;
    await doctor.save();

    res.status(200).json({
      success: true,
      message: 'Doctor approved successfully',
      data: doctor
    });
  } catch (error) {
    next(error);
  }
};

/**
 * Suspend doctor
 */
const suspendDoctor = async (req, res, next) => {
  try {
    const { id } = req.params;

    const doctor = await Doctor.findByPk(id);
    if (!doctor) {
      return res.status(404).json({
        success: false,
        message: 'Doctor not found'
      });
    }

    const user = await User.findByPk(doctor.user_id);
    user.is_active = false;
    await user.save();

    res.status(200).json({
      success: true,
      message: 'Doctor suspended successfully'
    });
  } catch (error) {
    next(error);
  }
};

/**
 * Deactivate user
 */
const deactivateUser = async (req, res, next) => {
  try {
    const { id } = req.params;

    const user = await User.findByPk(id);
    if (!user) {
      return res.status(404).json({
        success: false,
        message: 'User not found'
      });
    }

    if (user.id === req.user.id) {
      return res.status(400).json({
        success: false,
        message: 'Cannot deactivate your own account'
      });
    }

    user.is_active = false;
    await user.save();

    res.status(200).json({
      success: true,
      message: 'User deactivated successfully'
    });
  } catch (error) {
    next(error);
  }
};

/**
 * Activate user
 */
const activateUser = async (req, res, next) => {
  try {
    const { id } = req.params;

    const user = await User.findByPk(id);
    if (!user) {
      return res.status(404).json({
        success: false,
        message: 'User not found'
      });
    }

    user.is_active = true;
    await user.save();

    res.status(200).json({
      success: true,
      message: 'User activated successfully'
    });
  } catch (error) {
    next(error);
  }
};

/**
 * Get all appointments (admin view)
 */
const getAllAppointmentsAdmin = async (req, res, next) => {
  try {
    const { status, date } = req.query;

    const whereClause = {};
    if (status) whereClause.status = status;
    if (date) whereClause.date = date;

    const appointments = await Appointment.findAll({
      where: whereClause,
      include: [
        {
          model: Doctor,
          as: 'doctor',
          include: [
            {
              model: User,
              as: 'user',
              attributes: ['id', 'name', 'email', 'profile_photo']
            }
          ]
        },
        {
          model: Patient,
          as: 'patient',
          include: [
            {
              model: User,
              as: 'user',
              attributes: ['id', 'name', 'email', 'profile_photo']
            }
          ]
        }
      ],
      order: [['date', 'DESC'], ['time_slot', 'DESC']]
    });

    res.status(200).json({
      success: true,
      data: appointments
    });
  } catch (error) {
    next(error);
  }
};

/**
 * Get analytics data
 */
const getAnalytics = async (req, res, next) => {
  try {
    const { period } = req.query;
    const startDate = period === 'week' 
      ? new Date(Date.now() - 7 * 24 * 60 * 60 * 1000)
      : period === 'month'
      ? new Date(Date.now() - 30 * 24 * 60 * 60 * 1000)
      : new Date(Date.now() - 90 * 24 * 60 * 60 * 1000);

    // Appointments by status
    const appointmentsByStatus = await Appointment.findAll({
      attributes: [
        'status',
        [require('sequelize').fn('COUNT', require('sequelize').col('id')), 'count']
      ],
      where: { created_at: { [Op.gte]: startDate } },
      group: ['status']
    });

    // Revenue over time
    const revenueOverTime = await Payment.findAll({
      attributes: [
        [require('sequelize').fn('DATE', require('sequelize').col('paid_at')), 'date'],
        [require('sequelize').fn('SUM', require('sequelize').col('amount')), 'total']
      ],
      where: {
        status: 'completed',
        paid_at: { [Op.gte]: startDate }
      },
      group: [require('sequelize').fn('DATE', require('sequelize').col('paid_at'))],
      order: [[require('sequelize').fn('DATE', require('sequelize').col('paid_at')), 'ASC']]
    });

    // Top doctors by appointments
    const topDoctors = await Doctor.findAll({
      attributes: [
        'id',
        [require('sequelize').fn('COUNT', require('sequelize').col('appointments.id')), 'appointment_count']
      ],
      include: [
        {
          model: User,
          attributes: ['name']
        },
        {
          model: Appointment,
          attributes: []
        }
      ],
      where: { created_at: { [Op.gte]: startDate } },
      group: ['Doctor.id', 'User.id'],
      order: [[require('sequelize').literal('appointment_count'), 'DESC']],
      limit: 10
    });

    res.status(200).json({
      success: true,
      data: {
        appointmentsByStatus,
        revenueOverTime,
        topDoctors
      }
    });
  } catch (error) {
    next(error);
  }
};

/**
 * Delete doctor (with record tracking)
 */
const deleteDoctor = async (req, res, next) => {
  try {
    const { id } = req.params;

    const doctor = await Doctor.findByPk(id, {
      include: [{ model: User, as: 'user' }]
    });

    if (!doctor) {
      return res.status(404).json({
        success: false,
        message: 'Doctor not found'
      });
    }

    // Check for active appointments
    const activeAppointments = await Appointment.count({
      where: {
        doctor_id: id,
        status: { [Op.in]: ['pending', 'confirmed', 'in-progress'] }
      }
    });

    if (activeAppointments > 0) {
      return res.status(400).json({
        success: false,
        message: `Cannot delete doctor with ${activeAppointments} active appointments. Please cancel or complete all appointments first.`
      });
    }

    // Store deletion record
    const deletionRecord = {
      deleted_by_admin_id: req.user.id,
      doctor_id: id,
      doctor_name: doctor.user.name,
      doctor_email: doctor.user.email,
      specialty: doctor.specialty,
      deleted_at: new Date(),
      reason: req.body.reason || 'Admin deletion'
    };

    // Delete associated records
    await Appointment.destroy({ where: { doctor_id: id } });
    await Review.destroy({ where: { doctor_id: id } });

    // Delete doctor record
    const user = doctor.user;
    await doctor.destroy();
    await user.destroy();

    res.status(200).json({
      success: true,
      message: 'Doctor deleted successfully',
      deletionRecord
    });
  } catch (error) {
    next(error);
  }
};

/**
 * Delete user (with record tracking)
 */
const deleteUser = async (req, res, next) => {
  try {
    const { id } = req.params;

    const user = await User.findByPk(id);
    if (!user) {
      return res.status(404).json({
        success: false,
        message: 'User not found'
      });
    }

    // Prevent self-deletion
    if (user.id === req.user.id) {
      return res.status(400).json({
        success: false,
        message: 'Cannot delete your own account'
      });
    }

    // Store deletion record
    const deletionRecord = {
      deleted_by_admin_id: req.user.id,
      user_id: id,
      user_name: user.name,
      user_email: user.email,
      user_role: user.role,
      deleted_at: new Date(),
      reason: req.body.reason || 'Admin deletion'
    };

    // Delete associated records based on role
    if (user.role === 'doctor') {
      const doctor = await Doctor.findOne({ where: { user_id: id } });
      if (doctor) {
        await Appointment.destroy({ where: { doctor_id: doctor.id } });
        await Review.destroy({ where: { doctor_id: doctor.id } });
        await doctor.destroy();
      }
    } else if (user.role === 'patient') {
      const patient = await Patient.findOne({ where: { user_id: id } });
      if (patient) {
        await Appointment.destroy({ where: { patient_id: patient.id } });
        await patient.destroy();
      }
    }

    // Delete user
    await user.destroy();

    res.status(200).json({
      success: true,
      message: 'User deleted successfully',
      deletionRecord
    });
  } catch (error) {
    next(error);
  }
};

module.exports = {
  getDashboardStats,
  getAllUsers,
  getAllDoctorsAdmin,
  createDoctor,
  approveDoctor,
  suspendDoctor,
  deactivateUser,
  activateUser,
  getAllAppointmentsAdmin,
  getAnalytics,
  deleteDoctor,
  deleteUser
