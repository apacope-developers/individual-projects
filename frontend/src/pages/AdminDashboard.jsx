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
  Trash2,
  AlertCircle,
} from 'lucide-react';

const AdminDashboard = () => {
  const navigate = useNavigate();
  const { t } = useLanguage();
  const [stats, setStats] = useState(null);
  const [doctors, setDoctors] = useState([]);
  const [users, setUsers] = useState([]);
  const [loading, setLoading] = useState(true);
  const [activeTab, setActiveTab] = useState('overview');
  const [deleteModal, setDeleteModal] = useState({ show: false, type: null, id: null, name: '' });

  useEffect(() => {
    loadDashboardData();
  }, []);

  const loadDashboardData = async () => {
    try {
      const [dashResponse, doctorsResponse, usersResponse] = await Promise.all([
        adminService.getDashboardStats(),
        adminService.getAllDoctors(),
        adminService.getAllUsers(),
      ]);
      setStats(dashResponse.data);
      setDoctors(doctorsResponse.data || []);
      setUsers(usersResponse.data || []);
    } catch (error) {
      toast.error(t('failedToLoad'));
    } finally {
      setLoading(false);
    }
  };

  const handleApproveDoctor = async (doctorId) => {
    try {
      await adminService.approveDoctor(doctorId);
      toast.success(t('approve') + ' ' + t('loginSuccess').replace('!', ''));
      loadDashboardData();
    } catch (error) {
      toast.error(t('failedToLoad'));
    }
  };

  const handleSuspendDoctor = async (doctorId) => {
    try {
      await adminService.suspendDoctor(doctorId);
      toast.success(t('suspend') + ' ' + t('loginSuccess').replace('!', ''));
      loadDashboardData();
    } catch (error) {
      toast.error(t('failedToLoad'));
    }
  };

  const handleDeleteConfirm = async () => {
    try {
      const { type, id } = deleteModal;
      if (type === 'doctor') {
        await adminService.deleteDoctor(id);
        toast.success('Doctor deleted successfully');
      } else if (type === 'user') {
        await adminService.deleteUser(id);
        toast.success('User deleted successfully');
      }
      setDeleteModal({ show: false, type: null, id: null, name: '' });
      loadDashboardData();
    } catch (error) {
      toast.error(error.response?.data?.message || 'Failed to delete');
    }
  };

  const openDeleteModal = (type, id, name) => {
    setDeleteModal({ show: true, type, id, name });
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
            <p className="text-xs uppercase tracking-[0.24em] text-slate-500 dark:text-slate-400">{t('liveStatus')}</p>
            <p className="mt-2 text-xl font-semibold text-slate-900 dark:text-white">{stats?.pendingDoctors || 0} {t('pendingReviews')}</p>
          </div>
        </div>
      </div>

      {/* Stats Cards */}
      <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div className="card border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950">
          <div className="flex items-center justify-between gap-4">
            <div>
              <p className="text-sm text-slate-500 dark:text-slate-400">{t('totalUsers')}</p>
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
              <p className="text-sm text-slate-500 dark:text-slate-400">{t('totalDoctors')}</p>
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
              <p className="text-sm text-slate-500 dark:text-slate-400">{t('totalPatients')}</p>
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
              <p className="text-sm text-slate-500 dark:text-slate-400">{t('totalRevenue')}</p>
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
              <p className="text-sm text-slate-500 dark:text-slate-400">{t('totalAppointments')}</p>
              <p className="text-2xl font-semibold text-slate-900 dark:text-white mt-2">{stats?.totalAppointments || 0}</p>
            </div>
            <Calendar className="w-10 h-10 text-primary-600" />
          </div>
        </div>

        <div className="card border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950">
          <div className="flex items-center justify-between gap-4">
            <div>
              <p className="text-sm text-slate-500 dark:text-slate-400">{t('completedAppointments')}</p>
              <p className="text-2xl font-semibold text-slate-900 dark:text-white mt-2">{stats?.completedAppointments || 0}</p>
            </div>
            <CheckCircle className="w-10 h-10 text-green-600" />
          </div>
        </div>

        <div className="card border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950">
          <div className="flex items-center justify-between gap-4">
            <div>
              <p className="text-sm text-slate-500 dark:text-slate-400">{t('pendingDoctors')}</p>
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
              { key: 'overview', label: t('overview') },
              { key: 'doctors', label: t('doctors') },
              { key: 'users', label: t('users') },
              { key: 'appointments', label: t('appointments') },
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
              <h3 className="text-lg font-semibold text-slate-900 dark:text-white mb-4">{t('recentAppointments')}</h3>
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
                <p className="text-slate-500 dark:text-slate-400 text-center py-8">{t('noAppointments')}</p>
              )}
            </div>
          )}

          {activeTab === 'doctors' && (
            <div className="space-y-6">
              <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                {[
                  { label: t('totalDoctorsStats'), value: stats?.totalDoctors || 0 },
                  { label: t('pendingApproval'), value: stats?.pendingDoctors || 0 },
                  { label: t('approvedDoctors'), value: (stats?.totalDoctors - stats?.pendingDoctors) || 0 },
                ].map((item) => (
                  <div key={item.label} className="card border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950">
                    <p className="text-sm text-slate-500 dark:text-slate-400">{item.label}</p>
                    <p className="text-3xl font-semibold text-slate-900 dark:text-white mt-3">{item.value}</p>
                  </div>
                ))}
              </div>
              <div className="space-y-3">
                {doctors.length > 0 ? doctors.map((doctor) => (
                  <div key={doctor.id} className="flex items-center justify-between p-4 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 hover:shadow-md transition">
                    <div className="flex items-center gap-4">
                      <ProfileAvatar photo={doctor.user?.profile_photo} name={doctor.user?.name} size="sm" />
                      <div>
                        <p className="font-semibold text-slate-900 dark:text-white">Dr. {doctor.user?.name}</p>
                        <p className="text-sm text-slate-600 dark:text-slate-400">{doctor.specialty} · {doctor.user?.email}</p>
                      </div>
                    </div>
                    <div className="flex items-center gap-2">
                      {!doctor.is_approved && (
                        <button onClick={() => handleApproveDoctor(doctor.id)} className="px-3 py-1 bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 text-sm rounded-lg hover:bg-green-200 transition">
                          <CheckCircle className="w-4 h-4 inline mr-1" /> Approve
                        </button>
                      )}
                      <button onClick={() => openDeleteModal('doctor', doctor.id, doctor.user?.name)} className="px-3 py-1 bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 text-sm rounded-lg hover:bg-red-200 transition">
                        <Trash2 className="w-4 h-4 inline mr-1" /> Delete
                      </button>
                    </div>
                  </div>
                )) : <p className="text-slate-500 dark:text-slate-400 text-center py-8">No doctors found</p>}
              </div>
            </div>
          )}

          {activeTab === 'users' && (
            <div className="space-y-6">
              <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                {[
                  { label: t('totalUsers'), value: stats?.totalUsers || 0 },
                  { label: t('totalPatients'), value: stats?.totalPatients || 0 },
                  { label: t('activeAccounts'), value: stats?.totalUsers || 0 },
                ].map((item) => (
                  <div key={item.label} className="card border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950">
                    <p className="text-sm text-slate-500 dark:text-slate-400">{item.label}</p>
                    <p className="text-3xl font-semibold text-slate-900 dark:text-white mt-3">{item.value}</p>
                  </div>
                ))}
              </div>
              <div className="space-y-3">
                {users.length > 0 ? users.map((user) => (
                  <div key={user.id} className="flex items-center justify-between p-4 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 hover:shadow-md transition">
                    <div className="flex items-center gap-4">
                      <ProfileAvatar photo={user.profile_photo} name={user.name} size="sm" />
                      <div>
                        <p className="font-semibold text-slate-900 dark:text-white">{user.name}</p>
                        <p className="text-sm text-slate-600 dark:text-slate-400">{user.role} · {user.email}</p>
                        <p className="text-xs text-slate-500">{user.is_active ? 'Active' : 'Inactive'}</p>
                      </div>
                    </div>
                    <div className="flex items-center gap-2">
                      {user.is_active && user.role !== 'admin' && (
                        <button onClick={() => openDeleteModal('user', user.id, user.name)} className="px-3 py-1 bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 text-sm rounded-lg hover:bg-red-200 transition">
                          <Trash2 className="w-4 h-4 inline mr-1" /> Delete
                        </button>
                      )}
                    </div>
                  </div>
                )) : <p className="text-slate-500 dark:text-slate-400 text-center py-8">No users found</p>}
              </div>
            </div>
          )}

          {activeTab === 'appointments' && (
            <div className="space-y-6">
              <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                {[
                  { label: t('totalAppointmentsStats'), value: stats?.totalAppointments || 0 },
                  { label: t('completedCount'), value: stats?.completedAppointments || 0 },
                  { label: t('revenueLabel'), value: `$${stats?.totalRevenue?.toFixed(2) || '0.00'}` },
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
                <p className="text-slate-500 dark:text-slate-400 text-center py-8">{t('noRecentAppointmentsAvailable')}</p>
              )}
            </div>
          )}
        </div>
      </div>

      {/* Quick Actions */}
      <div>
        <h2 className="text-xl font-semibold text-slate-900 dark:text-white mb-4">{t('quickActions')}</h2>
        <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
          {[
            { label: t('approveAction'), value: `${stats?.pendingDoctors || 0} ${t('approveValue')}`, icon: Shield, color: 'bg-blue-100 text-blue-600' , onClick: () => navigate('/admin/doctors') },
            { label: t('manageAction'), value: `${stats?.totalUsers || 0} ${t('usersValue')}`, icon: Users, color: 'bg-emerald-100 text-emerald-600', onClick: () => navigate('/admin/users') },
            { label: t('viewAction'), value: t('reportsValue'), icon: BarChart3, color: 'bg-violet-100 text-violet-600', onClick: () => navigate('/admin/appointments') },
            { label: t('settingsAction'), value: t('configurePlatform'), icon: Settings, color: 'bg-orange-100 text-orange-600', onClick: () => {} },
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

      {/* Delete Confirmation Modal */}
      {deleteModal.show && (
        <div className="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
          <div className="bg-white dark:bg-slate-800 rounded-lg p-6 max-w-sm w-full mx-4">
            <div className="flex items-center gap-3 mb-4">
              <AlertCircle className="w-6 h-6 text-red-600" />
              <h3 className="text-lg font-semibold text-slate-900 dark:text-white">Confirm Delete</h3>
            </div>
            <p className="text-slate-600 dark:text-slate-400 mb-6">
              Are you sure you want to delete <strong>{deleteModal.name}</strong>? This action cannot be undone.
            </p>
            <div className="flex gap-3">
              <button onClick={() => setDeleteModal({ show: false, type: null, id: null, name: '' })} className="flex-1 px-4 py-2 border border-slate-300 dark:border-slate-600 text-slate-900 dark:text-white rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 transition">
                Cancel
              </button>
              <button onClick={handleDeleteConfirm} className="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                Delete
              </button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
};

export default AdminDashboard;
