require('dotenv').config({ path: require('path').join(__dirname, '../.env') });
const bcrypt = require('bcryptjs');
const { sequelize } = require('../config/database');
const { User } = require('../models');

const run = async () => {
  await sequelize.authenticate();
  const admin = await User.findOne({ where: { email: 'admin@healthcare.com' } });
  if (!admin) {
    console.log('No admin@healthcare.com found');
    process.exit(1);
  }
  admin.password = await bcrypt.hash('admin123', 10);
  await admin.save({ hooks: false });
  console.log('Password reset to: admin123');
  process.exit(0);
};
run();
