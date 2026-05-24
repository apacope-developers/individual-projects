import { useState, useEffect } from 'react';
import { adminService } from '../services/adminService';
import { useLanguage } from '../context/LanguageContext';
import { ProfileAvatar } from '../utils/photoUrl';
import { toast } from 'react-hot-toast';

const AdminUsers = () => {
  const { t } = useLanguage();
  const [users, setUsers] = useState([]);
  const [loading, setLoading] = useState(true);
  const [filter, setFilter] = useState('');

  useEffect(() => {
    loadUsers();
  }, [filter]);

  const loadUsers = async () => {
    try {
      const params = filter ? { role: filter } : {};
      const res = await adminService.getAllUsers(params);
      setUsers(res.data || []);
    } catch {
      toast.error(t('failedToLoad'));
    } finally {
      setLoading(false);
    }
  };

  const toggleActive = async (user) => {
    try {
      if (user.is_active) {
        await adminService.deactivateUser(user.id);
      } else {
        await adminService.activateUser(user.id);
      }
      loadUsers();
    } catch {
      toast.error('Action failed');
    }
  };

  if (loading) {
    return <div className="flex justify-center h-64"><div className="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600" /></div>;
  }

  return (
    <div className="space-y-6">
      <div className="flex justify-between items-center">
        <h1 className="text-3xl font-bold text-gray-900 dark:text-white">{t('users')}</h1>
        <select className="input-field w-48" value={filter} onChange={(e) => setFilter(e.target.value)}>
          <option value="">All roles</option>
          <option value="patient">Patients</option>
          <option value="doctor">Doctors</option>
          <option value="admin">Admins</option>
        </select>
      </div>

      <div className="space-y-3">
        {users.map((u) => (
          <div key={u.id} className="card flex items-center justify-between">
            <div className="flex items-center gap-4">
              <ProfileAvatar photo={u.profile_photo} name={u.name} size="sm" />
              <div>
                <p className="font-semibold text-gray-900 dark:text-white">{u.name}</p>
                <p className="text-sm text-gray-600 dark:text-gray-400">{u.email} · <span className="capitalize">{u.role}</span></p>
              </div>
            </div>
            <div className="flex items-center gap-3">
              <span className={`badge ${u.is_active ? 'badge-success' : 'badge-error'}`}>
                {u.is_active ? 'Active' : 'Inactive'}
              </span>
              {u.role !== 'admin' && (
                <button onClick={() => toggleActive(u)} className="btn-outline text-sm">
                  {u.is_active ? 'Deactivate' : 'Activate'}
                </button>
              )}
            </div>
          </div>
        ))}
      </div>
    </div>
  );
};

export default AdminUsers;
