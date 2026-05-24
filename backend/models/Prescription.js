const { DataTypes } = require('sequelize');
const { sequelize } = require('../config/database');

const Prescription = sequelize.define('Prescription', {
  id: {
    type: DataTypes.INTEGER,
    primaryKey: true,
    autoIncrement: true
  },
  consultation_id: {
    type: DataTypes.INTEGER,
    allowNull: false,
    references: {
      model: 'consultations',
      key: 'id'
    }
  },
  doctor_id: {
    type: DataTypes.INTEGER,
    allowNull: false,
    references: {
      model: 'doctors',
      key: 'id'
    }
  },
  patient_id: {
    type: DataTypes.INTEGER,
    allowNull: false,
    references: {
      model: 'patients',
      key: 'id'
    }
  },
  medications: {
    type: DataTypes.JSON,
    allowNull: false,
    comment: 'Array of medication objects with name, dosage, duration, instructions'
  },
  instructions: {
    type: DataTypes.TEXT,
    allowNull: true
  },
  pdf_url: {
    type: DataTypes.STRING(500),
    allowNull: true
  },
  issued_at: {
    type: DataTypes.DATE,
    defaultValue: DataTypes.NOW
  },
  is_delivered: {
    type: DataTypes.BOOLEAN,
    defaultValue: false
  }
}, {
  tableName: 'prescriptions',
  timestamps: true
});

module.exports = Prescription;
