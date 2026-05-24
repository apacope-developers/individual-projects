const nodemailer = require('nodemailer');
require('dotenv').config();

// Create transporter
const transporter = nodemailer.createTransport({
  host: process.env.EMAIL_HOST || 'smtp.gmail.com',
  port: process.env.EMAIL_PORT || 587,
  secure: false,
  auth: {
    user: process.env.EMAIL_USER,
    pass: process.env.EMAIL_PASSWORD
  }
});

/**
 * Send email
 */
const sendEmail = async (options) => {
  try {
    const mailOptions = {
      from: process.env.EMAIL_FROM || 'HealthCare System <noreply@healthcare.com>',
      to: options.email,
      subject: options.subject,
      html: options.html
    };

    const info = await transporter.sendMail(mailOptions);
    console.log('Email sent:', info.messageId);
    return info;
  } catch (error) {
    console.error('Email error:', error);
    throw new Error('Failed to send email');
  }
};

/**
 * Send appointment confirmation email
 */
const sendAppointmentConfirmation = async (email, patientName, doctorName, date, time) => {
  const html = `
    <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
      <h2 style="color: #2c3e50;">Appointment Confirmed</h2>
      <p>Dear ${patientName},</p>
      <p>Your appointment has been successfully confirmed with the following details:</p>
      <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0;">
        <p><strong>Doctor:</strong> ${doctorName}</p>
        <p><strong>Date:</strong> ${date}</p>
        <p><strong>Time:</strong> ${time}</p>
      </div>
      <p>Please arrive 10 minutes before your appointment time.</p>
      <p>If you need to reschedule or cancel, please do so at least 24 hours in advance.</p>
      <p>Best regards,<br>HealthCare Team</p>
    </div>
  `;

  return sendEmail({
    email,
    subject: 'Appointment Confirmed - HealthCare System',
    html
  });
};

/**
 * Send appointment reminder email
 */
const sendAppointmentReminder = async (email, patientName, doctorName, date, time) => {
  const html = `
    <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
      <h2 style="color: #e67e22;">Appointment Reminder</h2>
      <p>Dear ${patientName},</p>
      <p>This is a friendly reminder about your upcoming appointment:</p>
      <div style="background: #fff3cd; padding: 20px; border-radius: 8px; margin: 20px 0;">
        <p><strong>Doctor:</strong> ${doctorName}</p>
        <p><strong>Date:</strong> ${date}</p>
        <p><strong>Time:</strong> ${time}</p>
      </div>
      <p>Please ensure you're ready for your consultation.</p>
      <p>Best regards,<br>HealthCare Team</p>
    </div>
  `;

  return sendEmail({
    email,
    subject: 'Appointment Reminder - HealthCare System',
    html
  });
};

/**
 * Send prescription ready email
 */
const sendPrescriptionReady = async (email, patientName, prescriptionId) => {
  const html = `
    <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
      <h2 style="color: #27ae60;">Prescription Ready</h2>
      <p>Dear ${patientName},</p>
      <p>Your prescription is now ready for download.</p>
      <div style="background: #d4edda; padding: 20px; border-radius: 8px; margin: 20px 0;">
        <p><strong>Prescription ID:</strong> ${prescriptionId}</p>
      </div>
      <p>You can download it from your patient dashboard.</p>
      <p>Best regards,<br>HealthCare Team</p>
    </div>
  `;

  return sendEmail({
    email,
    subject: 'Prescription Ready - HealthCare System',
    html
  });
};

/**
 * Send welcome email
 */
const sendWelcomeEmail = async (email, name) => {
  const html = `
    <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
      <h2 style="color: #3498db;">Welcome to HealthCare System</h2>
      <p>Dear ${name},</p>
      <p>Thank you for registering with HealthCare System. Your account has been successfully created.</p>
      <p>You can now book appointments, consult with doctors, and manage your health records.</p>
      <p>If you have any questions, please don't hesitate to contact us.</p>
      <p>Best regards,<br>HealthCare Team</p>
    </div>
  `;

  return sendEmail({
    email,
    subject: 'Welcome to HealthCare System',
    html
  });
};

/**
 * Notify doctor of new patient booking
 */
const sendDoctorNewAppointmentEmail = async (email, doctorName, patientName, date, time, type) => {
  const html = `
    <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
      <h2 style="color: #2980b9;">New Appointment Booking</h2>
      <p>Dear Dr. ${doctorName},</p>
      <p>A patient has booked an appointment with you:</p>
      <div style="background: #ebf5fb; padding: 20px; border-radius: 8px; margin: 20px 0;">
        <p><strong>Patient:</strong> ${patientName}</p>
        <p><strong>Date:</strong> ${date}</p>
        <p><strong>Time:</strong> ${time}</p>
        <p><strong>Type:</strong> ${type === 'video' ? 'Video Consultation' : 'In-Person'}</p>
      </div>
      <p>Please log in to your doctor dashboard to review and confirm this appointment.</p>
      <p>Best regards,<br>HealthCare Team</p>
    </div>
  `;

  return sendEmail({
    email,
    subject: 'New Patient Appointment - HealthCare System',
    html
  });
};

module.exports = {
  sendEmail,
  sendAppointmentConfirmation,
  sendAppointmentReminder,
  sendPrescriptionReady,
  sendWelcomeEmail,
  sendDoctorNewAppointmentEmail
};
