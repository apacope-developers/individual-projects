const { DataTypes } = require('sequelize');
const { sequelize } = require('../config/database');

const Doctor = sequelize.define('Doctor', {
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
  specialty: {
    type: DataTypes.STRING(100),
    allowNull: false
  },
  qualification: {
    type: DataTypes.STRING(255),
    allowNull: false
  },
  experience: {
    type: DataTypes.INTEGER,
    allowNull: false,
    comment: 'Years of experience'
  },
  bio: {
    type: DataTypes.TEXT,
    allowNull: true
  },
  consultation_fee: {
    type: DataTypes.DECIMAL(10, 2),
    allowNull: false,
    defaultValue: 0.00
  },
  available: {
    type: DataTypes.BOOLEAN,
    defaultValue: true
  },
  rating: {
    type: DataTypes.DECIMAL(3, 2),
    defaultValue: 0.00,
    validate: {
      min: 0,
      max: 5
    }
  },
  total_reviews: {
    type: DataTypes.INTEGER,
    defaultValue: 0
  },
  license_number: {
    type: DataTypes.STRING(100),
    allowNull: true
  },
  medical_school: {
    type: DataTypes.STRING(255),
    allowNull: true
  },
  is_approved: {
    type: DataTypes.BOOLEAN,
    defaultValue: false
  }
}, {
  tableName: 'doctors',
  timestamps: true
});

module.exports = Doctor;
