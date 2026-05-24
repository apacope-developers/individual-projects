const { Sequelize } = require('sequelize');
require('dotenv').config();

// Initialize Sequelize with MySQL connection
const sequelize = new Sequelize(
  process.env.DB_NAME || 'healthcare_db',
  process.env.DB_USER || 'root',
  process.env.DB_PASSWORD || '',
  {
    host: process.env.DB_HOST || 'localhost',
    port: process.env.DB_PORT || 3306,
    dialect: 'mysql',
    logging: process.env.NODE_ENV === 'development' ? console.log : false,
    pool: {
      max: 10,
      min: 0,
      acquire: 30000,
      idle: 10000
    },
    define: {
      timestamps: true,
      underscored: false,
      freezeTableName: true
    }
  }
);

const delay = (ms) => new Promise((resolve) => setTimeout(resolve, ms));

const testConnection = async (retries = 3, retryDelay = 2000) => {
  for (let attempt = 1; attempt <= retries; attempt += 1) {
    try {
      await sequelize.authenticate();
      console.log('✅ Database connection established successfully.');
      return;
    } catch (error) {
      const msg = error.message || error;
      console.error(`❌ Unable to connect to the database (attempt ${attempt}/${retries}):`, msg);
      if (attempt === retries) {
        process.exit(1);
      }
      await delay(retryDelay);
    }
  }
};

// Sync database models
const syncDatabase = async (force = false, alter = false, retries = 2, retryDelay = 2000) => {
  for (let attempt = 1; attempt <= retries; attempt += 1) {
    try {
      console.log(`⏳ Synchronizing database (force=${force}, alter=${alter}, attempt=${attempt}/${retries})...`);
      await sequelize.sync({ force, alter });
      console.log('✅ Database synchronized successfully.');
      return;
    } catch (error) {
      const msg = error.message || error;
      console.error(`❌ Error synchronizing database (attempt ${attempt}/${retries}):`, msg);
      if (attempt === retries) {
        throw error;
      }
      await delay(retryDelay);
    }
  }
};

module.exports = { sequelize, testConnection, syncDatabase };
