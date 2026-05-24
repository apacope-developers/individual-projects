const { Patient, User, MedicalRecord, AICheck, Appointment } = require('../models');
const { uploadSingle } = require('../middleware/upload');

const getOrCreatePatient = async (user) => {
  if (user.role !== 'patient') return null;
  let patient = await Patient.findOne({ where: { user_id: user.id } });
  if (!patient) {
    patient = await Patient.create({ user_id: user.id });
  }
  return patient;
};

/**
 * Update patient profile
 */
const updatePatientProfile = async (req, res, next) => {
  try {
    const user = req.user;
    const { dob, gender, blood_group, allergies, chronic_conditions, emergency_contact_name, emergency_contact_phone, address } = req.body;

    const patient = await getOrCreatePatient(user);
    if (!patient) {
      return res.status(404).json({
        success: false,
        message: 'Patient profile not found'
      });
    }

    if (dob) patient.dob = dob;
    if (gender) patient.gender = gender;
    if (blood_group) patient.blood_group = blood_group;
    if (allergies) patient.allergies = typeof allergies === 'string' ? allergies : JSON.stringify(allergies);
    if (chronic_conditions) patient.chronic_conditions = typeof chronic_conditions === 'string' ? chronic_conditions : JSON.stringify(chronic_conditions);
    if (emergency_contact_name) patient.emergency_contact_name = emergency_contact_name;
    if (emergency_contact_phone) patient.emergency_contact_phone = emergency_contact_phone;
    if (address) patient.address = address;

    await patient.save();

    res.status(200).json({
      success: true,
      message: 'Patient profile updated successfully',
      data: patient
    });
  } catch (error) {
    next(error);
  }
};

/**
 * Get patient medical records
 */
const getMedicalRecords = async (req, res, next) => {
  try {
    const user = req.user;

    const patient = await getOrCreatePatient(user);
    if (!patient) {
      return res.status(404).json({
        success: false,
        message: 'Patient profile not found'
      });
    }

    const records = await MedicalRecord.findAll({
      where: { patient_id: patient.id },
      order: [['createdAt', 'DESC']]
    });

    res.status(200).json({
      success: true,
      data: records
    });
  } catch (error) {
    next(error);
  }
};

/**
 * Upload medical record
 */
const uploadMedicalRecord = async (req, res, next) => {
  try {
    const user = req.user;
    const { title, description, record_type } = req.body;

    if (!req.file) {
      return res.status(400).json({
        success: false,
        message: 'No file uploaded'
      });
    }

    const patient = await getOrCreatePatient(user);
    if (!patient) {
      return res.status(404).json({
        success: false,
        message: 'Patient profile not found'
      });
    }

    const record = await MedicalRecord.create({
      patient_id: patient.id,
      title,
      description,
      file_url: `/uploads/documents/${req.file.filename}`,
      file_type: req.file.mimetype,
      file_size: req.file.size,
      uploaded_by: user.id,
      record_type: record_type || 'other'
    });

    res.status(201).json({
      success: true,
      message: 'Medical record uploaded successfully',
      data: record
    });
  } catch (error) {
    next(error);
  }
};

/**
 * Delete medical record
 */
const deleteMedicalRecord = async (req, res, next) => {
  try {
    const { id } = req.params;

    const record = await MedicalRecord.findByPk(id);
    if (!record) {
      return res.status(404).json({
        success: false,
        message: 'Medical record not found'
      });
    }

    // Check authorization
    const patient = await getOrCreatePatient(req.user);
    if (record.patient_id !== patient?.id && req.user.role !== 'admin') {
      return res.status(403).json({
        success: false,
        message: 'Not authorized to delete this record'
      });
    }

    await record.destroy();

    res.status(200).json({
      success: true,
      message: 'Medical record deleted successfully'
    });
  } catch (error) {
    next(error);
  }
};

/**
 * Get patient AI check history
 */
const getAICheckHistory = async (req, res, next) => {
  try {
    const user = req.user;

    const patient = await getOrCreatePatient(user);
    if (!patient) {
      return res.status(404).json({
        success: false,
        message: 'Patient profile not found'
      });
    }

    const aiChecks = await AICheck.findAll({
      where: { patient_id: patient.id },
      order: [['createdAt', 'DESC']]
    });

    res.status(200).json({
      success: true,
      data: aiChecks
    });
  } catch (error) {
    next(error);
  }
};

/**
 * Get patient dashboard summary
 */
const getPatientDashboard = async (req, res, next) => {
  try {
    const user = req.user;

    const patient = await getOrCreatePatient(user);
    if (!patient) {
      return res.status(404).json({
        success: false,
        message: 'Patient profile not found'
      });
    }

    const upcomingAppointments = await Appointment.count({
      where: {
        patient_id: patient.id,
        status: 'confirmed',
        date: { [require('sequelize').Op.gte]: new Date() }
      }
    });

    const completedAppointments = await Appointment.count({
      where: {
        patient_id: patient.id,
        status: 'completed'
      }
    });

    const totalRecords = await MedicalRecord.count({
      where: { patient_id: patient.id }
    });

    const totalAIChecks = await AICheck.count({
      where: { patient_id: patient.id }
    });

    res.status(200).json({
      success: true,
      data: {
        upcomingAppointments,
        completedAppointments,
        totalRecords,
        totalAIChecks,
        profile: patient
      }
    });
  } catch (error) {
    next(error);
  }
};

module.exports = {
  updatePatientProfile,
  getMedicalRecords,
  uploadMedicalRecord,
  deleteMedicalRecord,
  getAICheckHistory,
  getPatientDashboard
};
