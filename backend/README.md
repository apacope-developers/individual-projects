# Smart Health Appointment & Telemedicine System - Backend

## Overview
This is the backend API for the Smart Health Appointment & Telemedicine System, built with Node.js, Express, MySQL, and Sequelize ORM.

## Features
- JWT-based authentication with role-based access control
- Doctor booking and appointment management
- Video consultation with WebRTC and Socket.io
- AI symptom checker integration
- Prescription management with PDF generation
- Medical records management
- Notifications system (email + real-time)
- Ratings and reviews
- Admin dashboard with analytics

## Tech Stack
- **Runtime**: Node.js
- **Framework**: Express.js
- **Database**: MySQL
- **ORM**: Sequelize
- **Authentication**: JWT + bcrypt
- **Real-time**: Socket.io
- **Video**: WebRTC
- **AI**: OpenAI API
- **Email**: Nodemailer
- **PDF**: PDFKit
- **File Upload**: Multer

## Installation

1. Install dependencies:
```bash
npm install
```

2. Create a `.env` file based on `.env.example`:
```bash
cp .env.example .env
```

3. Configure your environment variables in `.env`:
- Database credentials
- JWT secrets
- Email configuration
- OpenAI API key
- Cloudinary credentials (optional)

4. Ensure MySQL is running and create the database:
```sql
CREATE DATABASE healthcare_db;
```

## Running the Server

Development mode (with auto-reload):
```bash
npm run dev
```

Production mode:
```bash
npm start
```

The server will start on port 5000 (or the port specified in `.env`).

## API Endpoints

### Authentication
- `POST /api/auth/register` - Register new user
- `POST /api/auth/login` - Login user
- `POST /api/auth/refresh-token` - Refresh access token
- `POST /api/auth/logout` - Logout user
- `GET /api/auth/profile` - Get current user profile
- `PUT /api/auth/profile` - Update user profile

### Doctors
- `GET /api/doctors` - Get all doctors (with filters)
- `GET /api/doctors/:id` - Get doctor by ID
- `GET /api/doctors/:id/time-slots` - Get doctor's available time slots
- `PUT /api/doctors/profile` - Update doctor profile (doctor only)
- `GET /api/doctors/my/appointments` - Get doctor's appointments (doctor only)
- `PUT /api/doctors/time-slots` - Set doctor's availability (doctor only)
- `GET /api/doctors/my/stats` - Get doctor statistics (doctor only)

### Appointments
- `POST /api/appointments` - Book appointment (patient only)
- `GET /api/appointments/my` - Get patient's appointments (patient only)
- `GET /api/appointments/:id` - Get appointment by ID
- `PUT /api/appointments/:id/cancel` - Cancel appointment
- `PUT /api/appointments/:id/reschedule` - Reschedule appointment
- `PUT /api/appointments/:id/confirm` - Confirm appointment (doctor only)
- `PUT /api/appointments/:id/complete` - Complete appointment (doctor only)

### Patients
- `PUT /api/patients/profile` - Update patient profile (patient only)
- `GET /api/patients/records` - Get medical records (patient only)
- `POST /api/patients/records/upload` - Upload medical record (patient only)
- `DELETE /api/patients/records/:id` - Delete medical record (patient only)
- `GET /api/patients/ai-checks` - Get AI check history (patient only)
- `GET /api/patients/dashboard` - Get patient dashboard summary (patient only)

### AI Symptom Checker
- `POST /api/ai/check` - Submit symptoms for AI analysis (patient only)
- `GET /api/ai/check/:id` - Get AI check by ID

### Prescriptions
- `POST /api/prescriptions` - Create prescription (doctor only)
- `GET /api/prescriptions/my` - Get patient's prescriptions (patient only)
- `GET /api/prescriptions/:id` - Get prescription by ID

### Reviews
- `GET /api/reviews/doctor/:doctor_id` - Get doctor's reviews
- `POST /api/reviews` - Create review (patient only)
- `GET /api/reviews/my` - Get patient's reviews (patient only)

### Notifications
- `GET /api/notifications` - Get user notifications
- `GET /api/notifications/unread-count` - Get unread notification count
- `PUT /api/notifications/:id/read` - Mark notification as read
- `PUT /api/notifications/read-all` - Mark all notifications as read

### Admin
- `GET /api/admin/dashboard` - Get dashboard statistics (admin only)
- `GET /api/admin/users` - Get all users (admin only)
- `GET /api/admin/doctors` - Get all doctors (admin only)
- `PUT /api/admin/doctors/:id/approve` - Approve doctor (admin only)
- `PUT /api/admin/doctors/:id/suspend` - Suspend doctor (admin only)
- `PUT /api/admin/users/:id/deactivate` - Deactivate user (admin only)
- `PUT /api/admin/users/:id/activate` - Activate user (admin only)
- `GET /api/admin/appointments` - Get all appointments (admin only)
- `GET /api/admin/analytics` - Get analytics data (admin only)

## Database Models

- **User**: Base user model with authentication
- **Doctor**: Doctor-specific profile
- **Patient**: Patient-specific profile
- **Appointment**: Appointment bookings
- **Consultation**: Video consultation sessions
- **Prescription**: Digital prescriptions
- **MedicalRecord**: Patient medical documents
- **AICheck**: AI symptom check history
- **Review**: Doctor reviews
- **Notification**: User notifications
- **TimeSlot**: Doctor availability slots
- **Payment**: Payment records

## Socket.io Events

### Video Call Signaling
- `join-call` - Join a video call room
- `offer` - Send WebRTC offer
- `answer` - Send WebRTC answer
- `ice-candidate` - Send ICE candidate
- `end-call` - End the call
- `toggle-audio` - Toggle audio mute
- `toggle-video` - Toggle video

### Notifications
- `join-notifications` - Join notification room for user

## Project Structure

```
backend/
├── config/
│   └── database.js          # Database configuration
├── controllers/
│   ├── authController.js
│   ├── doctorController.js
│   ├── appointmentController.js
│   ├── patientController.js
│   ├── aiController.js
│   ├── prescriptionController.js
│   ├── reviewController.js
│   ├── notificationController.js
│   └── adminController.js
├── middleware/
│   ├── auth.js              # JWT authentication
│   ├── errorHandler.js      # Error handling
│   ├── validation.js        # Request validation
│   └── upload.js            # File upload handling
├── models/
│   ├── User.js
│   ├── Doctor.js
│   ├── Patient.js
│   ├── Appointment.js
│   ├── Consultation.js
│   ├── Prescription.js
│   ├── MedicalRecord.js
│   ├── AICheck.js
│   ├── Review.js
│   ├── Notification.js
│   ├── TimeSlot.js
│   ├── Payment.js
│   └── index.js             # Model associations
├── routes/
│   ├── auth.js
│   ├── doctors.js
│   ├── appointments.js
│   ├── patients.js
│   ├── ai.js
│   ├── prescriptions.js
│   ├── reviews.js
│   ├── notifications.js
│   ├── admin.js
│   └── index.js
├── services/                # Business logic services
├── utils/
│   ├── jwt.js              # JWT utilities
│   ├── email.js            # Email utilities
│   ├── pdf.js              # PDF generation
│   └── ai.js               # AI integration
├── uploads/                 # File uploads directory
├── .env.example
├── .gitignore
├── package.json
├── server.js               # Entry point
└── README.md
```

## Security Features
- Helmet for security headers
- CORS configuration
- Rate limiting
- Password hashing with bcrypt
- JWT token authentication
- Role-based access control
- Input validation

## Environment Variables

See `.env.example` for all required environment variables.

## Development Notes

- The server automatically creates database tables on startup
- File uploads are stored locally in the `uploads` directory
- Email notifications require SMTP configuration
- AI features require OpenAI API key
- Socket.io is used for real-time video calls and notifications
