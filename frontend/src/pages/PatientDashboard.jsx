import { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import { useLanguage } from '../context/LanguageContext';
import { patientService } from '../services/patientService';
import { appointmentService } from '../services/appointmentService';
import { doctorService } from '../services/doctorService';
import { toast } from 'react-hot-toast';
import {
  Calendar,
  Clock,
  FileText,
  Activity,
  Stethoscope,
  Brain,
  Pill,
  FolderOpen,
  ChevronRight,
  Plus,
} from 'lucide-react';

const PatientDashboard = () => {
  const { user } = useAuth();
  const { t } = useLanguage();
  const [dashboardData, setDashboardData] = useState(null);
  const [upcomingAppointments, setUpcomingAppointments] = useState([]);
  const [quickBookLink, setQuickBookLink] = useState('/doctors');
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    loadDashboardData();
  }, []);

  const loadDashboardData = async () => {
    try {
      const [dashboard, appointments, doctors] = await Promise.all([
        patientService.getDashboard(),
        appointmentService.getPatientAppointments({ status: 'confirmed' }),
        doctorService.getAllDoctors({ available: true })
      ]);
      
      setDashboardData(dashboard.data);
      setUpcomingAppointments(appointments.data.slice(0, 3));
      if (doctors.data && doctors.data.length > 0) {
        setQuickBookLink(`/book-appointment/${doctors.data[0].id}`);
      } else {
        setQuickBookLink('/doctors');
      }
    } catch (error) {
      toast.error(t('failedToLoad'));
    } finally {
      setLoading(false);
    }
  };

  const quickActions = [
    {
      icon: Stethoscope,
      label: t('findDoctors'),
      description: t('fastBookingDesc'),
      link: '/doctors',
      color: 'bg-blue-500',
    },
    {
      icon: Calendar,
      label: t('bookAppointment'),
      description: t('scheduleConsultation'),
      link: quickBookLink,
      color: 'bg-green-500',
    },
    {
      icon: Brain,
      label: t('aiChecker'),
      description: t('checkYourSymptoms'),
      link: '/ai-checker',
      color: 'bg-purple-500',
    },
    {
      icon: Pill,
      label: t('prescriptions'),
      description: t('viewYourPrescriptions'),
      link: '/prescriptions',
      color: 'bg-orange-500',
    },
    {
      icon: FolderOpen,
      label: t('medicalRecords'),
      description: t('accessYourHealthRecords'),
      link: '/medical-records',
      color: 'bg-pink-500',
    },
    {
      icon: FileText,
      label: t('appointmentHistory'),
      description: t('viewPastAppointments'),
      link: '/appointments',
      color: 'bg-indigo-500',
    },
  ];

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
          {t('welcomeBack')}, {user?.name}!
        </h1>
        <p className="text-gray-600 dark:text-gray-400 mt-2">{t('dashboardOverview')}</p>
      </div>

      {/* Stats Cards */}
      <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div className="card border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950">
          <div className="flex items-center justify-between gap-4">
            <div>
              <p className="text-sm text-slate-500 dark:text-slate-400">{t('upcomingAppointments')}</p>
              <p className="text-3xl font-semibold text-slate-900 dark:text-white mt-2">{dashboardData?.upcomingAppointments || 0}</p>
            </div>
            <div className="rounded-2xl bg-blue-500/10 p-3">
              <Calendar className="w-10 h-10 text-blue-600 dark:text-blue-300" />
            </div>
          </div>
        </div>

        <div className="card border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950">
          <div className="flex items-center justify-between gap-4">
            <div>
              <p className="text-sm text-slate-500 dark:text-slate-400">{t('completedConsultations')}</p>
              <p className="text-3xl font-semibold text-slate-900 dark:text-white mt-2">{dashboardData?.completedAppointments || 0}</p>
            </div>
            <div className="rounded-2xl bg-emerald-500/10 p-3">
              <Activity className="w-10 h-10 text-emerald-600 dark:text-emerald-300" />
            </div>
          </div>
        </div>

        <div className="card border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950">
          <div className="flex items-center justify-between gap-4">
            <div>
              <p className="text-sm text-slate-500 dark:text-slate-400">{t('medicalRecords')}</p>
              <p className="text-3xl font-semibold text-slate-900 dark:text-white mt-2">{dashboardData?.totalRecords || 0}</p>
            </div>
            <div className="rounded-2xl bg-violet-500/10 p-3">
              <FolderOpen className="w-10 h-10 text-violet-600 dark:text-violet-300" />
            </div>
          </div>
        </div>

        <div className="card border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950">
          <div className="flex items-center justify-between gap-4">
            <div>
              <p className="text-sm text-slate-500 dark:text-slate-400">{t('aiChecks')}</p>
              <p className="text-3xl font-semibold text-slate-900 dark:text-white mt-2">{dashboardData?.totalAIChecks || 0}</p>
            </div>
            <div className="rounded-2xl bg-orange-500/10 p-3">
              <Brain className="w-10 h-10 text-orange-600 dark:text-orange-300" />
            </div>
          </div>
        </div>
      </div>

      {/* Quick Actions */}
      <div>
        <h2 className="text-xl font-semibold text-slate-900 dark:text-white mb-4">Quick Actions</h2>
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          {quickActions.map((action) => {
            const Icon = action.icon;
            return (
              <Link
                key={action.label}
                to={action.link}
                className="card border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 hover:shadow-lg transition-shadow group"
              >
                <div className="flex items-start gap-4">
                  <div className={`${action.color} p-3 rounded-2xl text-white flex items-center justify-center`}>
                    <Icon className="w-6 h-6" />
                  </div>
                  <div className="flex-1">
                    <h3 className="font-semibold text-slate-900 dark:text-white group-hover:text-primary-600 transition-colors">
                      {action.label}
                    </h3>
                    <p className="text-sm text-slate-500 dark:text-slate-400 mt-1">{action.description}</p>
                  </div>
                  <ChevronRight className="w-5 h-5 text-slate-400 group-hover:text-primary-600 transition-colors" />
                </div>
              </Link>
            );
          })}
        </div>
      </div>

      {/* Upcoming Appointments */}
      <div>
        <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between mb-4">
          <h2 className="text-xl font-semibold text-slate-900 dark:text-white">{t('upcomingAppointments')}</h2>
          <Link to="/appointments" className="text-primary-600 hover:text-primary-700 text-sm font-medium">
            {t('viewAll')}
          </Link>
        </div>

        {upcomingAppointments.length > 0 ? (
          <div className="space-y-4">
            {upcomingAppointments.map((appointment) => (
              <div key={appointment.id} className="card border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950">
                <div className="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                  <div className="flex items-center gap-4">
                    <div className="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center">
                      <Stethoscope className="w-6 h-6 text-primary-600" />
                    </div>
                    <div>
                      <h3 className="font-semibold text-slate-900 dark:text-white">Dr. {appointment.doctor?.user?.name}</h3>
                      <p className="text-sm text-slate-500 dark:text-slate-400">{appointment.doctor?.specialty}</p>
                    </div>
                  </div>
                  <div className="text-right">
                    <div className="flex items-center justify-end gap-2 text-slate-900 font-medium">
                      <Calendar className="w-4 h-4" />
                      <span>{new Date(appointment.date).toLocaleDateString()}</span>
                    </div>
                    <div className="flex items-center justify-end gap-2 text-slate-500 text-sm mt-1">
                      <Clock className="w-4 h-4" />
                      <span>{appointment.time_slot}</span>
                    </div>
                  </div>
                </div>
                <div className="mt-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                  <span className={`badge ${
                    appointment.type === 'video' ? 'badge-info' : 'badge-success'
                  }`}>
                    {appointment.type === 'video' ? t('videoCall') : t('inPerson')}
                  </span>
                  <Link
                    to={`/video-consultation/${appointment.id}`}
                    className="btn-primary text-sm inline-flex items-center justify-center"
                  >
                    <Plus className="w-4 h-4 mr-2" />
                    Join Call
                  </Link>
                </div>
              </div>
            ))}
          </div>
        ) : (
          <div className="card border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-center py-12">
            <Calendar className="w-16 h-16 text-slate-300 mx-auto mb-4" />
            <h3 className="text-lg font-semibold text-slate-900 dark:text-white mb-2">{t('noUpcomingAppointments')}</h3>
            <p className="text-slate-600 dark:text-slate-400 mb-4">{t('bookYourFirstAppointment')}</p>
            <Link to="/doctors" className="btn-primary inline-flex items-center">
              <Plus className="w-4 h-4 mr-2" />
              {t('bookAppointment')}
            </Link>
          </div>
        )}
      </div>
    </div>
  );
};

export default PatientDashboard;
