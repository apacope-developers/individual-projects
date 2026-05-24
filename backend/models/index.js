const { sequelize } = require('../config/database');
const User = require('./User');
const Doctor = require('./Doctor');
const Patient = require('./Patient');
const Appointment = require('./Appointment');
const Consultation = require('./Consultation');
const Prescription = require('./Prescription');
const MedicalRecord = require('./MedicalRecord');
const AICheck = require('./AICheck');
const Review = require('./Review');
const Notification = require('./Notification');
const TimeSlot = require('./TimeSlot');
const Payment = require('./Payment');

// Define associations

// User associations
User.hasOne(Doctor, { foreignKey: 'user_id', as: 'doctorProfile' });
User.hasOne(Patient, { foreignKey: 'user_id', as: 'patientProfile' });
User.hasMany(Notification, { foreignKey: 'user_id', as: 'notifications' });

Doctor.belongsTo(User, { foreignKey: 'user_id', as: 'user' });
Doctor.hasMany(Appointment, { foreignKey: 'doctor_id', as: 'appointments' });
Doctor.hasMany(Review, { foreignKey: 'doctor_id', as: 'reviews' });
Doctor.hasMany(TimeSlot, { foreignKey: 'doctor_id', as: 'timeSlots' });
Doctor.hasMany(Prescription, { foreignKey: 'doctor_id', as: 'prescriptions' });

Patient.belongsTo(User, { foreignKey: 'user_id', as: 'user' });
Patient.hasMany(Appointment, { foreignKey: 'patient_id', as: 'appointments' });
Patient.hasMany(MedicalRecord, { foreignKey: 'patient_id', as: 'medicalRecords' });
Patient.hasMany(AICheck, { foreignKey: 'patient_id', as: 'aiChecks' });
Patient.hasMany(Review, { foreignKey: 'patient_id', as: 'reviews' });
Patient.hasMany(Prescription, { foreignKey: 'patient_id', as: 'prescriptions' });

Appointment.belongsTo(Patient, { foreignKey: 'patient_id', as: 'patient' });
Appointment.belongsTo(Doctor, { foreignKey: 'doctor_id', as: 'doctor' });
Appointment.hasOne(Consultation, { foreignKey: 'appointment_id', as: 'consultation' });
Appointment.hasOne(Payment, { foreignKey: 'appointment_id', as: 'payment' });
Appointment.hasOne(Review, { foreignKey: 'appointment_id', as: 'review' });

Consultation.belongsTo(Appointment, { foreignKey: 'appointment_id', as: 'appointment' });
Consultation.hasMany(Prescription, { foreignKey: 'consultation_id', as: 'prescriptions' });

Prescription.belongsTo(Consultation, { foreignKey: 'consultation_id', as: 'consultation' });
Prescription.belongsTo(Doctor, { foreignKey: 'doctor_id', as: 'doctor' });
Prescription.belongsTo(Patient, { foreignKey: 'patient_id', as: 'patient' });

MedicalRecord.belongsTo(Patient, { foreignKey: 'patient_id', as: 'patient' });

AICheck.belongsTo(Patient, { foreignKey: 'patient_id', as: 'patient' });

Review.belongsTo(Patient, { foreignKey: 'patient_id', as: 'patient' });
Review.belongsTo(Doctor, { foreignKey: 'doctor_id', as: 'doctor' });
Review.belongsTo(Appointment, { foreignKey: 'appointment_id', as: 'appointment' });

Notification.belongsTo(User, { foreignKey: 'user_id', as: 'user' });

TimeSlot.belongsTo(Doctor, { foreignKey: 'doctor_id', as: 'doctor' });

Payment.belongsTo(Appointment, { foreignKey: 'appointment_id', as: 'appointment' });

module.exports = {
  sequelize,
  User,
  Doctor,
  Patient,
  Appointment,
  Consultation,
  Prescription,
  MedicalRecord,
  AICheck,
  Review,
  Notification,
  TimeSlot,
  Payment
};
