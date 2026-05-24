import { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { appointmentService } from '../services/appointmentService';
import { toast } from 'react-hot-toast';
import { Calendar, Clock, ChevronRight, FileText } from 'lucide-react';

const AppointmentHistory = () => {
  const [appointments, setAppointments] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const loadAppointments = async () => {
      try {
        const response = await appointmentService.getPatientAppointments({});
        setAppointments(response.data || []);
      } catch (error) {
        toast.error('Failed to load appointments');
      } finally {
        setLoading(false);
      }
    };

    loadAppointments();
  }, []);

  return (
    <div className="space-y-8">
      <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
          <h1 className="text-3xl font-bold text-gray-900">Appointment History</h1>
          <p className="text-gray-600 mt-2">Review your scheduled consultations and upcoming visits.</p>
        </div>
        <Link to="/doctors" className="btn-primary inline-flex items-center gap-2">
          <FileText className="w-4 h-4" /> Book a new appointment
        </Link>
      </div>

      {loading ? (
        <div className="flex items-center justify-center h-64">
          <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600"></div>
        </div>
      ) : appointments.length > 0 ? (
        <div className="space-y-4">
          {appointments.map((appointment) => (
            <div key={appointment.id} className="card border border-slate-200">
              <div className="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div className="space-y-2">
                  <p className="text-sm text-slate-500 uppercase tracking-[0.2em]">{appointment.status}</p>
                  <h2 className="text-xl font-semibold text-slate-900">Dr. {appointment.doctor?.user?.name}</h2>
                  <p className="text-sm text-slate-600">{appointment.doctor?.specialty || 'General consultation'}</p>
                </div>
                <div className="grid gap-2 sm:grid-cols-2 text-sm text-slate-600">
                  <div className="flex items-center gap-2">
                    <Calendar className="w-4 h-4" />
                    {new Date(appointment.date).toLocaleDateString()}
                  </div>
                  <div className="flex items-center gap-2">
                    <Clock className="w-4 h-4" />
                    {appointment.time_slot}
                  </div>
                </div>
                <div className="flex items-center justify-end">
                  <ChevronRight className="w-5 h-5 text-primary-600" />
                </div>
              </div>
            </div>
          ))}
        </div>
      ) : (
        <div className="card text-center py-16">
          <Calendar className="mx-auto mb-4 w-14 h-14 text-primary-600" />
          <h3 className="text-xl font-semibold text-slate-900">No appointments found</h3>
          <p className="mt-3 text-slate-600">Book your first appointment to start your care journey.</p>
          <Link to="/doctors" className="btn-primary mt-6 inline-flex items-center gap-2">
            Browse doctors
          </Link>
        </div>
      )}
    </div>
  );
};

export default AppointmentHistory;
