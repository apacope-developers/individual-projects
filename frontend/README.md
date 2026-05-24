# Smart Health Appointment & Telemedicine System - Frontend

## Overview
This is the frontend React application for the Smart Health Appointment & Telemedicine System.

## Tech Stack
- **Framework**: React 18
- **Build Tool**: Vite
- **Routing**: React Router v6
- **Styling**: TailwindCSS
- **HTTP Client**: Axios
- **Real-time**: Socket.io Client
- **Icons**: Lucide React
- **Forms**: React Hook Form
- **Notifications**: React Hot Toast
- **Charts**: Recharts

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
```
VITE_API_URL=http://localhost:5000/api
```

## Running the Application

Development mode:
```bash
npm run dev
```

Production build:
```bash
npm run build
npm run preview
```

The application will be available at `http://localhost:3000`

## Project Structure

```
frontend/
├── public/                 # Static assets
├── src/
│   ├── components/         # Reusable components
│   │   ├── Layout.jsx
│   │   └── ProtectedRoute.jsx
│   ├── context/            # React Context providers
│   │   └── AuthContext.jsx
│   ├── pages/              # Page components
│   │   ├── Login.jsx
│   │   ├── Register.jsx
│   │   ├── PatientDashboard.jsx
│   │   ├── DoctorDashboard.jsx
│   │   ├── AdminDashboard.jsx
│   │   ├── BookAppointment.jsx
│   │   ├── DoctorList.jsx
│   │   ├── DoctorProfile.jsx
│   │   ├── VideoConsultation.jsx
│   │   ├── AISymptomChecker.jsx
│   │   ├── Prescriptions.jsx
│   │   ├── MedicalRecords.jsx
│   │   ├── Profile.jsx
│   │   └── NotFound.jsx
│   ├── services/           # API service modules
│   │   ├── api.js
│   │   ├── authService.js
│   │   ├── doctorService.js
│   │   ├── appointmentService.js
│   │   ├── patientService.js
│   │   ├── aiService.js
│   │   ├── prescriptionService.js
│   │   ├── notificationService.js
│   │   └── adminService.js
│   ├── utils/              # Utility functions
│   ├── App.jsx             # Main app component
│   ├── main.jsx            # Entry point
│   └── index.css           # Global styles
├── index.html
├── vite.config.js
├── tailwind.config.js
├── postcss.config.js
├── package.json
└── README.md
```

## Features

### Authentication
- User registration (Patient, Doctor, Admin)
- Login with JWT tokens
- Protected routes
- Role-based access control

### Patient Dashboard
- Book appointments
- View appointment history
- AI symptom checker
- View prescriptions
- Upload medical records
- Profile management

### Doctor Dashboard
- View appointments
- Manage availability
- Create prescriptions
- View patient history
- Statistics overview

### Admin Dashboard
- User management
- Doctor approval
- System analytics
- Appointment overview
- Revenue tracking

### Video Consultation
- WebRTC-based video calls
- Real-time signaling via Socket.io
- In-call controls (mute, video toggle)
- Call timer

### AI Symptom Checker
- Symptom input interface
- AI-powered analysis
- Condition suggestions
- Urgency assessment

## API Integration

The frontend communicates with the backend API using Axios. All API calls are centralized in the `services` directory for easy maintenance.

## State Management

React Context API is used for authentication state management. Additional state can be managed using React hooks or a state management library as needed.

## Routing

React Router v6 is used for client-side routing. Protected routes ensure only authenticated users can access specific pages.

## Styling

TailwindCSS is used for styling with custom theme colors defined in `tailwind.config.js`. Custom utility classes are defined in `index.css`.

## Development Notes

- The API proxy is configured in `vite.config.js` to handle CORS during development
- JWT tokens are stored in localStorage and automatically included in API requests
- Socket.io client can be integrated for real-time features
- Form validation can be enhanced with React Hook Form
