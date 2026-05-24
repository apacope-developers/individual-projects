const { DataTypes } = require('sequelize');
const { sequelize } = require('../config/database');

const MedicalRecord = sequelize.define('MedicalRecord', {
  id: {
    type: DataTypes.INTEGER,
    primaryKey: true,
    autoIncrement: true
  },
  patient_id: {
    type: DataTypes.INTEGER,
    allowNull: false,
    references: {
      model: 'patients',
      key: 'id'
    }
  },
  title: {
    type: DataTypes.STRING(255),
    allowNull: false
  },
  description: {
    type: DataTypes.TEXT,
    allowNull: true
  },
  file_url: {
    type: DataTypes.STRING(500),
    allowNull: false
  },
  file_type: {
    type: DataTypes.STRING(50),
    allowNull: false,
    comment: 'pdf, image, document, etc.'
  },
  file_size: {
    type: DataTypes.INTEGER,
    allowNull: true,
    comment: 'File size in bytes'
  },
  uploaded_by: {
    type: DataTypes.INTEGER,
    allowNull: true,
    comment: 'User ID who uploaded the file'
  },
  record_type: {
    type: DataTypes.ENUM('lab_report', 'prescription', 'imaging', 'discharge_summary', 'other'),
    defaultValue: 'other'
  },
  is_confidential: {
    type: DataTypes.BOOLEAN,
    defaultValue: false
  }
}, {
  tableName: 'medical_records',
  timestamps: true
});

module.exports = MedicalRecord;
