const { DataTypes } = require('sequelize');
const { sequelize } = require('../config/database');

const Notification = sequelize.define('Notification', {
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
    }
  },
  title: {
    type: DataTypes.STRING(255),
    allowNull: false
  },
  message: {
    type: DataTypes.TEXT,
    allowNull: false
  },
  type: {
    type: DataTypes.ENUM('appointment', 'prescription', 'payment', 'system', 'consultation'),
    allowNull: false
  },
  is_read: {
    type: DataTypes.BOOLEAN,
    defaultValue: false
  },
  action_url: {
    type: DataTypes.STRING(500),
    allowNull: true
  },
  related_id: {
    type: DataTypes.INTEGER,
    allowNull: true,
    comment: 'ID of related entity (appointment, prescription, etc.)'
  }
}, {
  tableName: 'notifications',
  timestamps: true,
  indexes: [
    {
      fields: ['user_id', 'is_read']
    },
    {
      fields: ['createdAt']
    }
  ]
});

module.exports = Notification;
