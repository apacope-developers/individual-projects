const { DataTypes } = require('sequelize');
const { sequelize } = require('../config/database');

const Patient = sequelize.define('Patient', {
  id: {
    type: DataTypes.INTEGER,
    primaryKey: true,
    autoIncrement: true
  },
  user_id: {
    type: DataTypes.INTEGER,
    allowNull: false,
    references: {
      model: 'users',
      key: 'id'
    },
    unique: true
  },
  dob: {
    type: DataTypes.DATEONLY,
    allowNull: true,
    comment: 'Date of birth'
  },
  gender: {
    type: DataTypes.ENUM('male', 'female', 'other'),
    allowNull: true
  },
  blood_group: {
    type: DataTypes.ENUM('A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'),
    allowNull: true
  },
  allergies: {
    type: DataTypes.TEXT,
    allowNull: true,
    comment: 'JSON string of allergies'
  },
  chronic_conditions: {
    type: DataTypes.TEXT,
    allowNull: true,
    comment: 'JSON string of chronic conditions'
  },
  emergency_contact_name: {
    type: DataTypes.STRING(100),
    allowNull: true
  },
  emergency_contact_phone: {
    type: DataTypes.STRING(20),
    allowNull: true
  },
  address: {
    type: DataTypes.TEXT,
    allowNull: true
  }
}, {
  tableName: 'patients',
  timestamps: true
});

module.exports = Patient;
