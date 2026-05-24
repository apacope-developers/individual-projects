const { DataTypes } = require('sequelize');
const { sequelize } = require('../config/database');

const Appointment = sequelize.define('Appointment', {
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
  doctor_id: {
    type: DataTypes.INTEGER,
    allowNull: false,
    references: {
      model: 'doctors',
      key: 'id'
    }
  },
  date: {
    type: DataTypes.DATEONLY,
    allowNull: false
  },
  time_slot: {
    type: DataTypes.STRING(20),
    allowNull: false,
    comment: 'Format: HH:MM-HH:MM'
  },
  type: {
    type: DataTypes.ENUM('in-person', 'video'),
    allowNull: false,
    defaultValue: 'in-person'
  },
  status: {
    type: DataTypes.ENUM('pending', 'confirmed', 'completed', 'cancelled', 'no-show'),
    allowNull: false,
    defaultValue: 'pending'
  },
  reason: {
    type: DataTypes.TEXT,
    allowNull: true
  },
  notes: {
    type: DataTypes.TEXT,
    allowNull: true
  },
  fee: {
    type: DataTypes.DECIMAL(10, 2),
    allowNull: false
  },
  payment_status: {
    type: DataTypes.ENUM('pending', 'paid', 'refunded'),
    defaultValue: 'pending'
  },
  cancellation_reason: {
    type: DataTypes.TEXT,
    allowNull: true
  },
  cancelled_at: {
    type: DataTypes.DATE,
    allowNull: true
  },
  reminder_sent: {
    type: DataTypes.BOOLEAN,
    defaultValue: false
  }
}, {
  tableName: 'appointments',
  timestamps: true,
  indexes: [
    {
      fields: ['patient_id', 'status']
    },
    {
      fields: ['doctor_id', 'date', 'time_slot']
    },
    {
      fields: ['date']
    }
  ]
});

module.exports = Appointment;
