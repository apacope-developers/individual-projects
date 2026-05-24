const { DataTypes } = require('sequelize');
const { sequelize } = require('../config/database');

const TimeSlot = sequelize.define('TimeSlot', {
  id: {
    type: DataTypes.INTEGER,
    primaryKey: true,
    autoIncrement: true
  },
  doctor_id: {
    type: DataTypes.INTEGER,
    allowNull: false,
    references: {
      model: 'doctors',
      key: 'id'
    }
  },
  day_of_week: {
    type: DataTypes.ENUM('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'),
    allowNull: false
  },
  start_time: {
    type: DataTypes.STRING(10),
    allowNull: false,
    comment: 'Format: HH:MM'
  },
  end_time: {
    type: DataTypes.STRING(10),
    allowNull: false,
    comment: 'Format: HH:MM'
  },
  is_available: {
    type: DataTypes.BOOLEAN,
    defaultValue: true
  },
  break_start: {
    type: DataTypes.STRING(10),
    allowNull: true,
    comment: 'Break start time if any'
  },
  break_end: {
    type: DataTypes.STRING(10),
    allowNull: true,
    comment: 'Break end time if any'
  }
}, {
  tableName: 'time_slots',
  timestamps: true,
  indexes: [
    {
      unique: true,
      fields: ['doctor_id', 'day_of_week', 'start_time']
    }
  ]
});

module.exports = TimeSlot;
