/**
 * Run: node scripts/seedAdmin.js
 * Creates default admin if none exists
 */
require('dotenv').config({ path: require('path').join(__dirname, '../.env') });
const { sequelize } = require('../config/database');
const { User } = require('../models');

const seed = async () => {
  try {
    await sequelize.authenticate();
    const existing = await User.findOne({ where: { role: 'admin' } });
    if (existing) {
      console.log('Admin already exists:', existing.email);
      process.exit(0);
    }
    const admin = await User.create({
      name: 'System Admin',
      email: 'admin@healthcare.com',
      password: 'admin123',
      role: 'admin',
      phone: null,
    });
    console.log('Admin created successfully!');
    console.log('Email:', admin.email);
    console.log('Password: admin123');
    process.exit(0);
  } catch (err) {
    console.error('Seed failed:', err.message);
    process.exit(1);
  }
};

seed();
