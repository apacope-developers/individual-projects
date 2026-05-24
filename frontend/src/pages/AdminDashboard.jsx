import { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { adminService } from '../services/adminService';
import { useLanguage } from '../context/LanguageContext';
import { ProfileAvatar } from '../utils/photoUrl';
import { toast } from 'react-hot-toast';
import {
  Users,
  Stethoscope,
  Calendar,
  DollarSign,
  TrendingUp,
  CheckCircle,
  XCircle,
  Shield,
  Settings,
  BarChart3,
  Clock,
} from 'lucide-react';

const AdminDashboard = () => {
  const navigate = useNavigate();
  const { t } = useLanguage();
  const [stats, setStats] = useState(null);
  const [loading, setLoading] = useState(true);
  const [activeTab, setActiveTab] = useState('overview');

  useEffect(() => {
    loadDashboardData();
  }, []);

  const loadDashboardData = async () => {
    try {
      const response = await adminService.getDashboardStats();
      setStats(response.data);
    } catch (error) {
      toast.error(t('failedToLoad'));
    } finally {
      setLoading(false);
    }
  };

  const handleApproveDoctor = async (doctorId) => {
    try {
      await adminService.approveDoctor(doctorId);
      toast.success('Doctor approved successfully');
      loadDashboardData();
    } catch (error) {
      toast.error('Failed to approve doctor');
    }
  };

  const handleSuspendDoctor = async (doctorId) => {
    try {
      await adminService.suspendDoctor(doctorId);
      toast.success('Doctor suspended successfully');
      loadDashboardData();
    } catch (error) {
      toast.error('Failed to suspend doctor');
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
      {/* Header */}
      <div className="space-y-4">
        <div className="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
          <div>
            <h1 className="text-3xl font-semibold text-slate-900 dark:text-white">{t('adminDashboard')}</h1>
            <p className="text-gray-600 dark:text-gray-400 mt-2 max-w-2xl">{t('platformOverview')}</p>
          </div>
          <div className="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 px-5 py-4 shadow-sm">
            <p className="text-xs uppercase tracking-[0.24em] text-slate-500 dark:text-slate-400">Live status</p>
            <p className="mt-2 text-xl font-semibold text-slate-900 dark:text-white">{stats?.pendingDoctors || 0} pending reviews</p>
          </div>
        </div>
      </div>

      {/* Stats Cards */}
      <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div className="card border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950">
          <div className="flex items-center justify-between gap-4">
            <div>
              <p className="text-sm text-slate-500 dark:text-slate-400">Total Users</p>
              <p className="text-3xl font-semibold text-slate-900 dark:text-white mt-2">{stats?.totalUsers || 0}</p>
            </div>
            <div className="rounded-2xl bg-blue-500/10 p-3">
              <Users className="w-10 h-10 text-blue-600 dark:text-blue-300" />
            </div>
          </div>
        </div>

        <div className="card border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950">
          <div className="flex items-center justify-between gap-4">
            <div>
              <p className="text-sm text-slate-500 dark:text-slate-400">Total Doctors</p>
              <p className="text-3xl font-semibold text-slate-900 dark:text-white mt-2">{stats?.totalDoctors || 0}</p>
            </div>
            <div className="rounded-2xl bg-emerald-500/10 p-3">
              <Stethoscope className="w-10 h-10 text-emerald-600 dark:text-emerald-300" />
            </div>
          </div>
        </div>

        <div className="card border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950">
          <div className="flex items-center justify-between gap-4">
            <div>
              <p className="text-sm text-slate-500 dark:text-slate-400">Total Patients</p>
              <p className="text-3xl font-semibold text-slate-900 dark:text-white mt-2">{stats?.totalPatients || 0}</p>
            </div>
            <div className="rounded-2xl bg-violet-500/10 p-3">
              <Users className="w-10 h-10 text-violet-600 dark:text-violet-300" />
            </div>
          </div>
        </div>

        <div className="card border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950">
          <div className="flex items-center justify-between gap-4">
            <div>
              <p className="text-sm text-slate-500 dark:text-slate-400">Total Revenue</p>
              <p className="text-3xl font-semibold text-slate-900 dark:text-white mt-2">${stats?.totalRevenue?.toFixed(2) || '0.00'}</p>
            </div>
            <div className="rounded-2xl bg-orange-500/10 p-3">
              <DollarSign className="w-10 h-10 text-orange-600 dark:text-orange-300" />
            </div>
          </div>
        </div>
      </div>

      {/* Secondary Stats */}
      <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div className="card border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950">
          <div className="flex items-center justify-between gap-4">
            <div>
              <p className="text-sm text-slate-500 dark:text-slate-400">Total Appointments</p>
              <p className="text-2xl font-semibold text-slate-900 dark:text-white mt-2">{stats?.totalAppointments || 0}</p>
            </div>
            <Calendar className="w-10 h-10 text-primary-600" />
          </div>
        </div>

        <div className="card border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950">
          <div className="flex items-center justify-between gap-4">
            <div>
              <p className="text-sm text-slate-500 dark:text-slate-400">Completed Appointments</p>
              <p className="text-2xl font-semibold text-slate-900 dark:text-white mt-2">{stats?.completedAppointments || 0}</p>
            </div>
            <CheckCircle className="w-10 h-10 text-green-600" />
          </div>
        </div>

        <div className="card border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950">
          <div className="flex items-center justify-between gap-4">
            <div>
              <p className="text-sm text-slate-500 dark:text-slate-400">Pending Doctors</p>
              <p className="text-2xl font-semibold text-slate-900 dark:text-white mt-2">{stats?.pendingDoctors || 0}</p>
            </div>
            <Clock className="w-10 h-10 text-amber-600" />
          </div>
        </div>
      </div>

      {/* Tabs */}
      <div className="card border border-slate-200 dark:border-slate-800 overflow-hidden">
        <div className="border-b border-slate-200 dark:border-slate-800 px-6 py-4 bg-slate-50 dark:bg-slate-950">
          <nav className="flex flex-wrap gap-3">
            {[
              { key: 'overview', label: 'Overview' },
              { key: 'doctors', label: 'Doctors' },
              { key: 'users', label: 'Users' },
              { key: 'appointments', label: 'Appointments' },
            ].map((tab) => (
              <button
                key={tab.key}
                onClick={() => setActiveTab(tab.key)}
                className={`rounded-full px-4 py-2 text-sm font-medium transition ${activeTab === tab.key
                  ? 'bg-primary-600 text-white shadow-sm'
                  : 'bg-slate-100 text-slate-600 hover:bg-slate-200 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800'
                }`}
              >
                {tab.label}
              </button>
            ))}
          </nav>
        </div>

        <div className="space-y-6 px-6 py-6">
          {activeTab === 'overview' && (
            <div>
              <h3 className="text-lg font-semibold text-slate-900 dark:text-white mb-4">Recent Appointments</h3>
              {stats?.recentAppointments?.length > 0 ? (
                <div className="space-y-3">
                  {stats.recentAppointments.slice(0, 5).map((appointment) => (
                    <div key={appointment.id} className="flex items-center justify-between p-4 rounded-3xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
                      <div className="flex items-center gap-4">
                        <ProfileAvatar
                          photo={appointment.patient?.user?.profile_photo}
                          name={appointment.patient?.user?.name}
                          size="sm"
                        />
                        <div>
                          <p className="font-medium text-slate-900 dark:text-white">
                            {appointment.patient?.user?.name} - Dr. {appointment.doctor?.user?.name}
                          </p>
                          <p className="text-sm text-slate-500 dark:text-slate-400">
                            {new Date(appointment.date).toLocaleDateString()} at {appointment.time_slot}
                          </p>
                        </div>
                      </div>
                      <span className={`badge ${appointment.status === 'completed' ? 'badge-success' :
                          appointment.status === 'confirmed' ? 'badge-info' :
                            appointment.status === 'cancelled' ? 'badge-error' :
                              'badge-warning'
                        }`}>
                        {appointment.status}
                      </span>
                    </div>
                  ))}
                </div>
              ) : (
                <p className="text-slate-500 dark:text-slate-400 text-center py-8">No recent appointments</p>
              )}
            </div>
          )}

          {activeTab === 'doctors' && (
            <div className="space-y-6">
              <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                {[
                  { label: 'Total Doctors', value: stats?.totalDoctors || 0 },
                  { label: 'Pending Approval', value: stats?.pendingDoctors || 0 },
                  { label: 'Approved Doctors', value: (stats?.totalDoctors - stats?.pendingDoctors) || 0 },
                ].map((item) => (
                  <div key={item.label} className="card border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950">
                    <p className="text-sm text-slate-500 dark:text-slate-400">{item.label}</p>
                    <p className="text-3xl font-semibold text-slate-900 dark:text-white mt-3">{item.value}</p>
                  </div>
                ))}
              </div>
              <div className="rounded-3xl border border-dashed border-primary-200 bg-primary-50 p-6">
                <h3 className="text-lg font-semibold text-primary-700">Doctor review workflow</h3>
                <p className="mt-3 text-sm text-primary-700/80">
                  Approve pending doctors, review credentials, and keep the provider network secure.
                </p>
              </div>
            </div>
          )}

          {activeTab === 'users' && (
            <div className="space-y-6">
              <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                {[
                  { label: 'Total Users', value: stats?.totalUsers || 0 },
                  { label: 'Total Patients', value: stats?.totalPatients || 0 },
                  { label: 'Active Accounts', value: stats?.totalUsers || 0 },
                ].map((item) => (
                  <div key={item.label} className="card border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950">
                    <p className="text-sm text-slate-500 dark:text-slate-400">{item.label}</p>
                    <p className="text-3xl font-semibold text-slate-900 dark:text-white mt-3">{item.value}</p>
                  </div>
                ))}
              </div>
              <div className="rounded-3xl border border-dashed border-slate-300 bg-slate-50 p-6">
                <h3 className="text-lg font-semibold text-slate-900">Account management</h3>
                <p className="mt-3 text-sm text-slate-600">
                  Use this space to deactivate inactive users, enforce account policy, and ensure patient data integrity.
                </p>
              </div>
            </div>
          )}

          {activeTab === 'appointments' && (
            <div className="space-y-6">
              <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                {[
                  { label: 'Total Appointments', value: stats?.totalAppointments || 0 },
                  { label: 'Completed', value: stats?.completedAppointments || 0 },
                  { label: 'Revenue', value: `$${stats?.totalRevenue?.toFixed(2) || '0.00'}` },
                ].map((item) => (
                  <div key={item.label} className="card border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950">
                    <p className="text-sm text-slate-500 dark:text-slate-400">{item.label}</p>
                    <p className="text-3xl font-semibold text-slate-900 dark:text-white mt-3">{item.value}</p>
                  </div>
                ))}
              </div>
              {stats?.recentAppointments?.length > 0 ? (
                <div className="space-y-3">
                  {stats.recentAppointments.slice(0, 5).map((appointment) => (
                    <div key={appointment.id} className="flex items-center justify-between p-4 rounded-3xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
                      <div>
                        <p className="font-semibold text-slate-900 dark:text-white">
                          {appointment.patient?.user?.name} → Dr. {appointment.doctor?.user?.name}
                        </p>
                        <p className="text-sm text-slate-500 dark:text-slate-400">
                          {new Date(appointment.date).toLocaleDateString()} at {appointment.time_slot}
                        </p>
                      </div>
                      <span className="badge badge-info">{appointment.status}</span>
                    </div>
                  ))}
                </div>
              ) : (
                <p className="text-slate-500 dark:text-slate-400 text-center py-8">No recent appointments available.</p>
              )}
            </div>
          )}
        </div>
      </div>

      {/* Quick Actions */}
      <div>
        <h2 className="text-xl font-semibold text-slate-900 dark:text-white mb-4">Quick Actions</h2>
        <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
          {[
            { label: 'Approve Doctors', value: `${stats?.pendingDoctors || 0} pending`, icon: Shield, color: 'bg-blue-100 text-blue-600' , onClick: () => navigate('/admin/doctors') },
            { label: 'Manage Users', value: `${stats?.totalUsers || 0} users`, icon: Users, color: 'bg-emerald-100 text-emerald-600', onClick: () => navigate('/admin/users') },
            { label: 'View Appointments', value: 'Reports & insights', icon: BarChart3, color: 'bg-violet-100 text-violet-600', onClick: () => navigate('/admin/appointments') },
            { label: 'System Settings', value: 'Configure platform', icon: Settings, color: 'bg-orange-100 text-orange-600', onClick: () => {} },
          ].map((action) => {
            const Icon = action.icon;
            return (
              <button
                key={action.label}
                onClick={action.onClick}
                className="card border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 hover:shadow-lg transition-shadow text-left"
              >
                <div className="flex items-center gap-3">
                  <div className={`${action.color} p-3 rounded-2xl`}>
                    <Icon className="w-5 h-5" />
                  </div>
                  <div>
                    <h3 className="font-semibold text-slate-900 dark:text-white">{action.label}</h3>
                    <p className="text-sm text-slate-500 dark:text-slate-400">{action.value}</p>
                  </div>
                </div>
              </button>
            );
          })}
        </div>
      </div>
    </div>
  );
};

export default AdminDashboard;
