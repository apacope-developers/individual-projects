import { useState, useEffect } from 'react';
import { adminService } from '../services/adminService';
import { useLanguage } from '../context/LanguageContext';
import { ProfileAvatar } from '../utils/photoUrl';
import { toast } from 'react-hot-toast';
import { Calendar } from 'lucide-react';

const AdminAppointments = () => {
  const { t } = useLanguage();
  const [appointments, setAppointments] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    loadAppointments();
  }, []);

  const loadAppointments = async () => {
    try {
      const res = await adminService.getAllAppointments();
      setAppointments(res.data || []);
    } catch {
      toast.error(t('failedToLoad'));
    } finally {
      setLoading(false);
    }
  };

  if (loading) {
    return <div className="flex justify-center h-64"><div className="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600" /></div>;
  }

  return (
    <div className="space-y-6">
      <h1 className="text-3xl font-bold text-gray-900 dark:text-white">{t('appointments')}</h1>
      <p className="text-gray-600 dark:text-gray-400">All patient-doctor bookings across the platform</p>

      {appointments.length > 0 ? (
        <div className="space-y-3">
          {appointments.map((apt) => (
            <div key={apt.id} className="card">
              <div className="flex items-start justify-between flex-wrap gap-4">
                <div className="flex items-center gap-4">
                  <ProfileAvatar photo={apt.patient?.user?.profile_photo} name={apt.patient?.user?.name} size="sm" />
                  <div>
                    <p className="font-semibold text-gray-900 dark:text-white">
                      {apt.patient?.user?.name} → Dr. {apt.doctor?.user?.name}
                    </p>
                    <p className="text-sm text-gray-600 dark:text-gray-400 flex items-center mt-1">
                      <Calendar className="w-4 h-4 mr-1" />
                      {new Date(apt.date).toLocaleDateString()} at {apt.time_slot}
                    </p>
                    <p className="text-sm text-gray-500 capitalize">{apt.type} · ${apt.fee}</p>
                    {apt.reason && <p className="text-sm text-gray-500 mt-1">Reason: {apt.reason}</p>}
                  </div>
                </div>
                <span className={`badge ${
                  apt.status === 'completed' ? 'badge-success' :
                  apt.status === 'confirmed' ? 'badge-info' :
                  apt.status === 'cancelled' ? 'badge-error' : 'badge-warning'
                }`}>{apt.status}</span>
              </div>
            </div>
          ))}
        </div>
      ) : (
        <p className="text-center text-gray-500 py-12">{t('noAppointments')}</p>
      )}
    </div>
  );
};

export default AdminAppointments;
