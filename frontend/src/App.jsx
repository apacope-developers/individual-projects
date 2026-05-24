import { BrowserRouter, Routes, Route, Navigate } from 'react-router-dom';
import { Toaster } from 'react-hot-toast';
import { AuthProvider } from './context/AuthContext';
import { LanguageProvider } from './context/LanguageContext';
import { ThemeProvider } from './context/ThemeContext';
import ProtectedRoute from './components/ProtectedRoute';
import Layout from './components/Layout';

// Pages
import LandingPage from './pages/LandingPage';
import Login from './pages/Login';
import Register from './pages/Register';
import ForgotPassword from './pages/ForgotPassword';
import Terms from './pages/Terms';
import Privacy from './pages/Privacy';
import RoleProtectedRoute from './components/RoleProtectedRoute';
import PatientDashboard from './pages/PatientDashboard';
import AppointmentHistory from './pages/AppointmentHistory';
import DoctorDashboard from './pages/DoctorDashboard';
import DoctorAppointments from './pages/DoctorAppointments';
import DoctorPatients from './pages/DoctorPatients';
import DoctorPrescriptions from './pages/DoctorPrescriptions';
import AdminDashboard from './pages/AdminDashboard';
import AdminUsers from './pages/AdminUsers';
import AdminDoctors from './pages/AdminDoctors';
import AdminAppointments from './pages/AdminAppointments';
import BookAppointment from './pages/BookAppointment';
import DoctorList from './pages/DoctorList';
import DoctorProfile from './pages/DoctorProfile';
import VideoConsultation from './pages/VideoConsultation';
import AISymptomChecker from './pages/AISymptomChecker';
import Prescriptions from './pages/Prescriptions';
import MedicalRecords from './pages/MedicalRecords';
import Profile from './pages/Profile';
import NotFound from './pages/NotFound';

function App() {
  return (
    <ThemeProvider>
    <LanguageProvider>
    <AuthProvider>
      <BrowserRouter>
        <Toaster position="top-right" />
        <Routes>
          {/* Public routes */}
          <Route path="/" element={<LandingPage />} />
          <Route path="/login" element={<Login />} />
          <Route path="/register" element={<Register />} />
          <Route path="/forgot-password" element={<ForgotPassword />} />
          <Route path="/terms" element={<Terms />} />
          <Route path="/privacy" element={<Privacy />} />

          {/* Protected routes */}
          <Route element={<ProtectedRoute><Layout /></ProtectedRoute>}>
            <Route path="/dashboard" element={<PatientDashboard />} />
            <Route path="/appointments" element={<AppointmentHistory />} />
            <Route path="/doctors" element={<DoctorList />} />
            <Route path="/doctors/:id" element={<DoctorProfile />} />
            <Route path="/book-appointment" element={<Navigate to="/doctors" />} />
            <Route path="/book-appointment/:doctorId" element={<BookAppointment />} />
            <Route path="/video-consultation/:appointmentId" element={<VideoConsultation />} />
            <Route path="/ai-checker" element={<AISymptomChecker />} />
            <Route path="/prescriptions" element={<Prescriptions />} />
            <Route path="/medical-records" element={<MedicalRecords />} />
            <Route path="/profile" element={<Profile />} />
            
            {/* Doctor Routes */}
            <Route
              path="/doctor/dashboard"
              element={
                <RoleProtectedRoute roles={["doctor"]}>
                  <DoctorDashboard />
                </RoleProtectedRoute>
              }
            />
            <Route
              path="/doctor/appointments"
              element={
                <RoleProtectedRoute roles={["doctor"]}>
                  <DoctorAppointments />
                </RoleProtectedRoute>
              }
            />
            <Route
              path="/doctor/patients"
              element={
                <RoleProtectedRoute roles={["doctor"]}>
                  <DoctorPatients />
                </RoleProtectedRoute>
              }
            />
            <Route
              path="/doctor/prescriptions"
              element={
                <RoleProtectedRoute roles={["doctor"]}>
                  <DoctorPrescriptions />
                </RoleProtectedRoute>
              }
            />
            
            {/* Admin Routes */}
            <Route
              path="/admin/dashboard"
              element={
                <RoleProtectedRoute roles={["admin"]}>
                  <AdminDashboard />
                </RoleProtectedRoute>
              }
            />
            <Route
              path="/admin/users"
              element={
                <RoleProtectedRoute roles={["admin"]}>
                  <AdminUsers />
                </RoleProtectedRoute>
              }
            />
            <Route
              path="/admin/doctors"
              element={
                <RoleProtectedRoute roles={["admin"]}>
                  <AdminDoctors />
                </RoleProtectedRoute>
              }
            />
            <Route
              path="/admin/appointments"
              element={
                <RoleProtectedRoute roles={["admin"]}>
                  <AdminAppointments />
                </RoleProtectedRoute>
              }
            />
            <Route path="/admin/settings" element={<RoleProtectedRoute roles={["admin"]}><AdminDashboard /></RoleProtectedRoute>} />
          </Route>

          {/* 404 */}
          <Route path="*" element={<NotFound />} />
        </Routes>
      </BrowserRouter>
    </AuthProvider>
    </LanguageProvider>
    </ThemeProvider>
  );
}

export default App;
