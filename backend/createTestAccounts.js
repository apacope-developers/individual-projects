const axios = require('axios');

const API_BASE = 'http://localhost:5000/api';

const testAccounts = [
  {
    email: 'admin@healthcare.com',
    password: 'Admin123',
    name: 'Admin User',
    role: 'admin',
    phone: '9876543210'
  },
  {
    email: 'doctor@healthcare.com',
    password: 'Doctor123',
    name: 'Dr. John Smith',
    role: 'doctor',
    phone: '0123456789'
  },
  {
    email: 'patient@healthcare.com',
    password: 'Patient123',
    name: 'John Patient',
    role: 'patient',
    phone: '5555555555'
  }
];

const createAccounts = async () => {
  console.log('🚀 Creating test accounts...\n');
  
  for (const account of testAccounts) {
    try {
      console.log(`Creating ${account.role} account: ${account.email}`);
      const response = await axios.post(`${API_BASE}/auth/register`, {
        email: account.email,
        password: account.password,
        name: account.name,
        role: account.role,
        phone: account.phone
      });
      
      console.log(`✅ ${account.role.toUpperCase()} created successfully!`);
      console.log(`   Email: ${account.email}`);
      console.log(`   Password: ${account.password}\n`);
    } catch (error) {
      if (error.response?.data?.message === 'Email already registered') {
        console.log(`⚠️  ${account.role.toUpperCase()} already exists: ${account.email}\n`);
      } else {
        console.error(`❌ Failed to create ${account.role}:`, error.response?.data?.message || error.message);
        console.error(error.response?.data || '');
        console.log();
      }
    }
  }
  
  console.log('📋 Test Account Credentials:\n');
  console.log('=== ADMIN ACCOUNT ===');
  console.log('Email: admin@healthcare.com');
  console.log('Password: Admin123');
  console.log('Role: admin\n');
  
  console.log('=== DOCTOR ACCOUNT ===');
  console.log('Email: doctor@healthcare.com');
  console.log('Password: Doctor123');
  console.log('Role: doctor\n');
  
  console.log('=== PATIENT ACCOUNT (Optional) ===');
  console.log('Email: patient@healthcare.com');
  console.log('Password: Patient123');
  console.log('Role: patient\n');
};

createAccounts().then(() => {
  console.log('✨ Setup complete!');
  process.exit(0);
}).catch(error => {
  console.error('Fatal error:', error);
  process.exit(1);
});
