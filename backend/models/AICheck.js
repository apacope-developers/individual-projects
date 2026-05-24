const { DataTypes } = require('sequelize');
const { sequelize } = require('../config/database');

const AICheck = sequelize.define('AICheck', {
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
  symptoms: {
    type: DataTypes.JSON,
    allowNull: false,
    comment: 'Array of symptoms entered by patient'
  },
  ai_response: {
    type: DataTypes.JSON,
    allowNull: true,
    comment: 'AI analysis response with conditions and recommendations'
  },
  urgency: {
    type: DataTypes.ENUM('low', 'medium', 'high', 'emergency'),
    allowNull: true
  },
  suggested_specialty: {
    type: DataTypes.STRING(100),
    allowNull: true
  },
  possible_conditions: {
    type: DataTypes.JSON,
    allowNull: true,
    comment: 'Array of possible conditions suggested by AI'
  },
  follow_up_action: {
    type: DataTypes.TEXT,
    allowNull: true
  }
}, {
  tableName: 'ai_checks',
  timestamps: true
});

module.exports = AICheck;
