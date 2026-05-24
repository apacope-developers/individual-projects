import { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import { useLanguage } from '../context/LanguageContext';
import { doctorService } from '../services/doctorService';
import { appointmentService } from '../services/appointmentService';
import { toast } from 'react-hot-toast';
import {
  Calendar,
  Users,
  DollarSign,
  Star,
  Clock,
  Video,
  User,
  CheckCircle,
  XCircle,
  ChevronRight,
  Stethoscope,
  TrendingUp,
} from 'lucide-react';

const DoctorDashboard = () => {
  const navigate = useNavigate();
  const { user } = useAuth();
  const { t } = useLanguage();
  const [stats, setStats] = useState(null);
  const [appointments, setAppointments] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    loadDashboardData();
  }, []);

  const loadDashboardData = async () => {
    try {
      const [statsData, appointmentsData] = await Promise.all([
        doctorService.getStats(),
        doctorService.getAppointments(),
      ]);
      
      setStats(statsData.data);
      const upcoming = (appointmentsData.data || [])
        .filter((a) => ['pending', 'confirmed'].includes(a.status))
        .slice(0, 5);
      setAppointments(upcoming);
    } catch (error) {
      toast.error(t('failedToLoad'));
    } finally {
      setLoading(false);
    }
  };

  const handleCompleteAppointment = async (appointmentId) => {
    try {
      await appointmentService.completeAppointment(appointmentId);
      toast.success('Appointment completed');
      loadDashboardData();
    } catch (error) {
      toast.error('Failed to complete appointment');
    }
  };

  const handleCancelAppointment = async (appointmentId) => {
    try {
      await appointmentService.cancelAppointment(appointmentId, 'Cancelled by doctor');
      toast.success('Appointment cancelled');
      loadDashboardData();
    } catch (error) {
      toast.error('Failed to cancel appointment');
    }
  };

  if (loading) {
    return (
      <div className="flex items-center justify-center h-64">
        <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600"></div>
      </div>
    );
  }

  return (
    <div className="space-y-8">
      {/* Welcome Header */}
      <div>
        <h1 className="text-3xl font-bold text-gray-900 dark:text-white">
          {t('welcome')}, Dr. {user?.name}!
        </h1>
        <p className="text-gray-600 dark:text-gray-400 mt-2">{t('doctorDashboard')}</p>
      </div>

      {/* Stats Cards */}
      <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div className="card border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950">
          <div className="flex items-center justify-between gap-4">
            <div>
              <p className="text-sm text-slate-500 dark:text-slate-400">Total Appointments</p>
              <p className="text-3xl font-semibold text-slate-900 dark:text-white mt-2">{stats?.totalAppointments || 0}</p>
            </div>
            <div className="rounded-2xl bg-blue-500/10 p-3">
              <Calendar className="w-10 h-10 text-blue-600 dark:text-blue-300" />
            </div>
          </div>
        </div>

        <div className="card border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950">
          <div className="flex items-center justify-between gap-4">
            <div>
              <p className="text-sm text-slate-500 dark:text-slate-400">Completed</p>
              <p className="text-3xl font-semibold text-slate-900 dark:text-white mt-2">{stats?.completedAppointments || 0}</p>
            </div>
            <div className="rounded-2xl bg-emerald-500/10 p-3">
              <CheckCircle className="w-10 h-10 text-emerald-600 dark:text-emerald-300" />
            </div>
          </div>
        </div>

        <div className="card border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950">
          <div className="flex items-center justify-between gap-4">
            <div>
              <p className="text-sm text-slate-500 dark:text-slate-400">Upcoming</p>
              <p className="text-3xl font-semibold text-slate-900 dark:text-white mt-2">{stats?.upcomingAppointments || 0}</p>
            </div>
            <div className="rounded-2xl bg-violet-500/10 p-3">
              <Clock className="w-10 h-10 text-violet-600 dark:text-violet-300" />
            </div>
          </div>
        </div>

        <div className="card border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950">
          <div className="flex items-center justify-between gap-4">
            <div>
              <p className="text-sm text-slate-500 dark:text-slate-400">Total Earnings</p>
              <p className="text-3xl font-semibold text-slate-900 dark:text-white mt-2">${stats?.totalEarnings?.toFixed(2) || '0.00'}</p>
            </div>
            <div className="rounded-2xl bg-orange-500/10 p-3">
              <DollarSign className="w-10 h-10 text-orange-600 dark:text-orange-300" />
            </div>
          </div>
        </div>
      </div>

      {/* Rating Card */}
      <div className="card border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950">
        <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
          <div className="flex items-center gap-4">
            <div className="rounded-3xl bg-yellow-500/10 p-3">
              <Star className="w-10 h-10 text-yellow-600 dark:text-yellow-300" />
            </div>
            <div>
              <p className="text-sm font-medium text-slate-500 dark:text-slate-400">Your Rating</p>
              <p className="text-4xl font-semibold text-slate-900 dark:text-white">{stats?.rating?.toFixed(1) || '0.0'}</p>
              <p className="text-sm text-slate-500 dark:text-slate-400 mt-1">Based on {stats?.totalReviews || 0} reviews</p>
            </div>
          </div>
          <TrendingUp className="w-16 h-16 text-yellow-500 dark:text-yellow-300" />
        </div>
      </div>

      {/* Today's Appointments */}
      <div>
        <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between mb-4">
          <h2 className="text-xl font-semibold text-slate-900 dark:text-white">Today's Appointments</h2>
          <button
            onClick={() => navigate('/doctor/appointments')}
            className="text-primary-600 hover:text-primary-700 text-sm font-medium"
          >
            View All
          </button>
        </div>

        {appointments.length > 0 ? (
          <div className="space-y-4">
            {appointments.map((appointment) => (
              <div key={appointment.id} className="card border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950">
                <div className="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                  <div className="flex items-start gap-4">
                    <div className="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center flex-shrink-0">
                      <User className="w-6 h-6 text-primary-600" />
                    </div>
                    <div>
                      <h3 className="font-semibold text-slate-900 dark:text-white">
                        {appointment.patient?.user?.name}
                      </h3>
                      <p className="text-sm text-slate-500 dark:text-slate-400">{appointment.reason || 'General consultation'}</p>
                      <div className="mt-2 flex flex-wrap items-center gap-4 text-sm text-slate-500 dark:text-slate-400">
                        <div className="flex items-center gap-1">
                          <Clock className="w-4 h-4" />
                          <span>{appointment.time_slot}</span>
                        </div>
                        <div className="flex items-center gap-1">
                          {appointment.type === 'video' ? (
                            <Video className="w-4 h-4" />
                          ) : (
                            <Stethoscope className="w-4 h-4" />
                          )}
                          <span>{appointment.type === 'video' ? 'Video Call' : 'In-Person'}</span>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div className="flex flex-wrap items-center gap-2">
                    {appointment.type === 'video' && (
                      <button
                        onClick={() => navigate(`/video-consultation/${appointment.id}`)}
                        className="btn-primary text-sm inline-flex items-center"
                      >
                        <Video className="w-4 h-4 mr-1" />
                        Join Call
                      </button>
                    )}
                    <button
                      onClick={() => handleCompleteAppointment(appointment.id)}
                      className="btn-secondary text-sm inline-flex items-center"
                    >
                      <CheckCircle className="w-4 h-4 mr-1" />
                      Complete
                    </button>
                    <button
                      onClick={() => handleCancelAppointment(appointment.id)}
                      className="btn-outline text-sm inline-flex items-center"
                    >
                      <XCircle className="w-4 h-4 mr-1" />
                      Cancel
                    </button>
                  </div>
                </div>
              </div>
            ))}
          </div>
        ) : (
          <div className="card bg-white dark:bg-slate-950 text-center py-12">
            <Calendar className="w-16 h-16 text-slate-300 mx-auto mb-4" />
            <h3 className="text-lg font-semibold text-slate-900 dark:text-white mb-2">No Appointments Today</h3>
            <p className="text-slate-600 dark:text-slate-400">Enjoy your free time!</p>
          </div>
        )}
      </div>

      {/* Quick Actions */}
      <div>
        <h2 className="text-xl font-semibold text-slate-900 dark:text-white mb-4">Quick Actions</h2>
        <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
          <button
            onClick={() => navigate('/profile')}
            className="card border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 hover:shadow-lg transition-shadow text-left"
          >
            <div className="flex items-center gap-4">
              <div className="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <Calendar className="w-6 h-6 text-blue-600" />
              </div>
              <div>
                <h3 className="font-semibold text-slate-900 dark:text-white">Manage Availability</h3>
                <p className="text-sm text-slate-500 dark:text-slate-400">Set your time slots</p>
              </div>
              <ChevronRight className="w-5 h-5 text-slate-400 ml-auto" />
            </div>
          </button>

          <button
            onClick={() => navigate('/doctor/patients')}
            className="card border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 hover:shadow-lg transition-shadow text-left"
          >
            <div className="flex items-center gap-4">
              <div className="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <Users className="w-6 h-6 text-green-600" />
              </div>
              <div>
                <h3 className="font-semibold text-slate-900 dark:text-white">View Patients</h3>
                <p className="text-sm text-slate-500 dark:text-slate-400">Manage patient list</p>
              </div>
              <ChevronRight className="w-5 h-5 text-slate-400 ml-auto" />
            </div>
          </button>

          <button
            onClick={() => navigate('/doctor/prescriptions')}
            className="card border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 hover:shadow-lg transition-shadow text-left"
          >
            <div className="flex items-center gap-4">
              <div className="w-12 h-12 bg-violet-100 rounded-lg flex items-center justify-center">
                <Stethoscope className="w-6 h-6 text-violet-600" />
              </div>
              <div>
                <h3 className="font-semibold text-slate-900 dark:text-white">Write Prescription</h3>
                <p className="text-sm text-slate-500 dark:text-slate-400">Create prescriptions</p>
              </div>
              <ChevronRight className="w-5 h-5 text-slate-400 ml-auto" />
            </div>
          </button>
        </div>
      </div>
    </div>
  );
};

export default DoctorDashboard;
