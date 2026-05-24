const { DataTypes } = require('sequelize');
const { sequelize } = require('../config/database');

const Payment = sequelize.define('Payment', {
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
  amount: {
    type: DataTypes.DECIMAL(10, 2),
    allowNull: false
  },
  method: {
    type: DataTypes.ENUM('credit_card', 'debit_card', 'paypal', 'stripe', 'cash', 'insurance'),
    allowNull: false,
    defaultValue: 'stripe'
  },
  status: {
    type: DataTypes.ENUM('pending', 'completed', 'failed', 'refunded'),
    allowNull: false,
    defaultValue: 'pending'
  },
  transaction_id: {
    type: DataTypes.STRING(255),
    allowNull: true,
    unique: true
  },
  paid_at: {
    type: DataTypes.DATE,
    allowNull: true
  },
  refund_amount: {
    type: DataTypes.DECIMAL(10, 2),
    allowNull: true
  },
  refund_reason: {
    type: DataTypes.TEXT,
    allowNull: true
  }
}, {
  tableName: 'payments',
  timestamps: true
});

module.exports = Payment;
