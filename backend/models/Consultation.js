const { DataTypes } = require('sequelize');
const { sequelize } = require('../config/database');

const Consultation = sequelize.define('Consultation', {
  id: {
    type: DataTypes.INTEGER,
    primaryKey: true,
    autoIncrement: true
  },
  appointment_id: {
    type: DataTypes.INTEGER,
    allowNull: false,
    references: {
      model: 'appointments',
      key: 'id'
    },
    unique: true
  },
  started_at: {
    type: DataTypes.DATE,
    allowNull: true
  },
  ended_at: {
    type: DataTypes.DATE,
    allowNull: true
  },
  duration: {
    type: DataTypes.INTEGER,
    allowNull: true,
    comment: 'Duration in minutes'
  },
  recording_url: {
    type: DataTypes.STRING(500),
    allowNull: true
  },
  diagnosis: {
    type: DataTypes.TEXT,
    allowNull: true
  },
  notes: {
    type: DataTypes.TEXT,
    allowNull: true,
    comment: 'Doctor consultation notes'
  },
  follow_up_required: {
    type: DataTypes.BOOLEAN,
    defaultValue: false
  },
  follow_up_date: {
    type: DataTypes.DATEONLY,
    allowNull: true
  }
}, {
  tableName: 'consultations',
  timestamps: true
});

module.exports = Consultation;
