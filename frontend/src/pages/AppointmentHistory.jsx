import { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { appointmentService } from '../services/appointmentService';
import { useLanguage } from '../context/LanguageContext';
import { toast } from 'react-hot-toast';
import { Calendar, Clock, FileText } from 'lucide-react';

const AppointmentHistory = () => {
  const { t } = useLanguage();
  const [appointments, setAppointments] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const loadAppointments = async () => {
      try {
        const response = await appointmentService.getPatientAppointments({});
        setAppointments(response.data || []);
      } catch (error) {
        toast.error(t('failedToLoad'));
      } finally {
        setLoading(false);
      }
    };

    loadAppointments();
  }, [t]);

  return (
    <div className="space-y-8">
      <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
          <h1 className="text-3xl font-bold text-gray-900 dark:text-white">{t('appointmentHistoryTitle')}</h1>
          <p className="text-gray-600 dark:text-gray-400 mt-2">{t('appointmentHistorySubtitle')}</p>
        </div>
        <Link to="/doctors" className="btn-primary inline-flex items-center gap-2">
          <FileText className="w-4 h-4" /> {t('bookAppointment')}
        </Link>
      </div>

      {loading ? (
        <div className="flex items-center justify-center h-64">
          <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600"></div>
        </div>
      ) : appointments.length > 0 ? (
        <div className="space-y-4">
          {appointments.map((appointment) => (
            <div key={appointment.id} className="card border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950">
              <div className="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div className="space-y-2">
                  <p className="text-sm text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em]">{appointment.status}</p>
                  <h2 className="text-xl font-semibold text-slate-900 dark:text-white">Dr. {appointment.doctor?.user?.name}</h2>
                  <p className="text-sm text-slate-600 dark:text-slate-400">{appointment.doctor?.specialty || t('generalConsultation')}</p>
                </div>
                <div className="grid gap-2 sm:grid-cols-2 text-sm text-slate-600 dark:text-slate-400">
                  <div className="flex items-center gap-2">
                    <Calendar className="w-4 h-4" />
                    {new Date(appointment.date).toLocaleDateString()}
                  </div>
                  <div className="flex items-center gap-2">
                    <Clock className="w-4 h-4" />
                    {appointment.time_slot}
                  </div>
                </div>
                <div className="flex items-center justify-end gap-3">
                  {appointment.type === 'video' && appointment.status === 'confirmed' ? (
                    <Link to={`/video-consultation/${appointment.id}`} className="btn-primary text-sm inline-flex items-center justify-center">
                      {t('joinCall')}
                    </Link>
                  ) : (
                    <span className="text-sm text-slate-500 dark:text-slate-400">{appointment.type === 'video' ? t('videoCall') : t('inPerson')}</span>
                  )}
                </div>
              </div>
            </div>
          ))}
        </div>
      ) : (
        <div className="card text-center py-16 bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
          <Calendar className="mx-auto mb-4 w-14 h-14 text-primary-600" />
          <h3 className="text-xl font-semibold text-slate-900 dark:text-white">{t('noAppointmentsFound')}</h3>
          <p className="mt-3 text-slate-600 dark:text-slate-400">{t('bookYourFirstAppointment')}</p>
          <Link to="/doctors" className="btn-primary mt-6 inline-flex items-center gap-2">
            {t('findDoctors')}
          </Link>
        </div>
      )}
    </div>
  );
};

export default AppointmentHistory;
