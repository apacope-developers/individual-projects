import { useState, useEffect } from 'react';
import { useAuth } from '../context/AuthContext';
import { doctorService } from '../services/doctorService';
import { appointmentService } from '../services/appointmentService';
import { toast } from 'react-hot-toast';
import {
  Calendar,
  Clock,
  User,
  Phone,
  Mail,
  CheckCircle,
  XCircle,
  ChevronRight,
  Search,
  Filter,
} from 'lucide-react';

const DoctorAppointments = () => {
  const { user } = useAuth();
  const [appointments, setAppointments] = useState([]);
  const [filteredAppointments, setFilteredAppointments] = useState([]);
  const [loading, setLoading] = useState(true);
  const [searchTerm, setSearchTerm] = useState('');
  const [statusFilter, setStatusFilter] = useState('all');

  useEffect(() => {
    loadAppointments();
  }, []);

  useEffect(() => {
    filterAppointments();
  }, [appointments, searchTerm, statusFilter]);

  const loadAppointments = async () => {
    try {
      const data = await doctorService.getAppointments();
      setAppointments(data.data || []);
    } catch (error) {
      toast.error('Failed to load appointments');
      setAppointments([]);
    } finally {
      setLoading(false);
    }
  };

  const filterAppointments = () => {
    let filtered = appointments;

    if (statusFilter !== 'all') {
      filtered = filtered.filter(apt => apt.status === statusFilter);
    }

    if (searchTerm) {
      filtered = filtered.filter(apt =>
        apt.patient?.user?.name?.toLowerCase().includes(searchTerm.toLowerCase()) ||
        apt.patient?.user?.email?.toLowerCase().includes(searchTerm.toLowerCase())
      );
    }

    setFilteredAppointments(filtered);
  };

  const handleCompleteAppointment = async (appointmentId) => {
    try {
      await appointmentService.completeAppointment(appointmentId);
      toast.success('Appointment marked as completed');
      loadAppointments();
    } catch (error) {
      toast.error('Failed to complete appointment');
    }
  };

  const handleCancelAppointment = async (appointmentId) => {
    try {
      await appointmentService.cancelAppointment(appointmentId, 'Cancelled by doctor');
      toast.success('Appointment cancelled');
      loadAppointments();
    } catch (error) {
      toast.error('Failed to cancel appointment');
    }
  };

  const getStatusColor = (status) => {
    switch (status) {
      case 'confirmed':
        return 'bg-blue-100 text-blue-800';
      case 'completed':
        return 'bg-green-100 text-green-800';
      case 'cancelled':
        return 'bg-red-100 text-red-800';
      case 'pending':
        return 'bg-yellow-100 text-yellow-800';
      default:
        return 'bg-gray-100 text-gray-800';
    }
  };

  if (loading) {
    return (
      <div className="flex items-center justify-center h-96">
        <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600"></div>
      </div>
    );
  }

  return (
    <div className="space-y-6">
      <div>
        <h1 className="text-3xl font-bold text-gray-900">Appointments</h1>
        <p className="text-gray-600 mt-2">Manage your patient appointments</p>
      </div>

      {/* Filters */}
      <div className="card space-y-4">
        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div className="flex items-center gap-2 bg-gray-50 rounded-lg px-4 py-2">
            <Search className="w-5 h-5 text-gray-400" />
            <input
              type="text"
              placeholder="Search patient name or email..."
              value={searchTerm}
              onChange={(e) => setSearchTerm(e.target.value)}
              className="flex-1 bg-transparent outline-none text-gray-700"
            />
          </div>

          <div className="flex items-center gap-2">
            <Filter className="w-5 h-5 text-gray-400" />
            <select
              value={statusFilter}
              onChange={(e) => setStatusFilter(e.target.value)}
              className="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500"
            >
              <option value="all">All Status</option>
              <option value="confirmed">Confirmed</option>
              <option value="completed">Completed</option>
              <option value="cancelled">Cancelled</option>
              <option value="pending">Pending</option>
            </select>
          </div>
        </div>
      </div>

      {/* Appointments List */}
      <div className="space-y-4">
        {filteredAppointments.length === 0 ? (
          <div className="card text-center py-12">
            <Calendar className="w-16 h-16 text-gray-300 mx-auto mb-4" />
            <h3 className="text-lg font-semibold text-gray-900 mb-2">No Appointments</h3>
            <p className="text-gray-600">
              {searchTerm || statusFilter !== 'all'
                ? 'No appointments match your filters'
                : 'You have no appointments yet'}
            </p>
          </div>
        ) : (
          filteredAppointments.map((appointment) => (
            <div key={appointment.id} className="card hover:shadow-lg transition">
              <div className="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div className="flex-1">
                  <div className="flex items-start gap-4">
                    <div className="flex-shrink-0 w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center">
                      <User className="w-6 h-6 text-primary-600" />
                    </div>
                    <div className="flex-1">
                      <h3 className="font-semibold text-gray-900">
                        {appointment.patient?.user?.name || 'Unknown Patient'}
                      </h3>
                      <div className="flex flex-col gap-1 mt-2 text-sm text-gray-600">
                        <div className="flex items-center gap-2">
                          <Mail className="w-4 h-4" />
                          {appointment.patient?.user?.email}
                        </div>
                        {appointment.patient?.user?.phone && (
                          <div className="flex items-center gap-2">
                            <Phone className="w-4 h-4" />
                            {appointment.patient?.user?.phone}
                          </div>
                        )}
                      </div>
                    </div>
                  </div>
                </div>

                <div className="flex flex-col md:flex-row md:items-center gap-4">
                  <div className="flex items-center gap-2 text-gray-700">
                    <Calendar className="w-5 h-5" />
                    <span className="font-medium">
                      {new Date(appointment.date).toLocaleDateString()}
                    </span>
                  </div>

                  <div className="flex items-center gap-2 text-gray-700">
                    <Clock className="w-5 h-5" />
                    <span className="font-medium">{appointment.time_slot}</span>
                  </div>

                  <span className={`px-3 py-1 rounded-full text-sm font-medium capitalize ${getStatusColor(appointment.status)}`}>
                    {appointment.status}
                  </span>

                  <div className="flex gap-2">
                    {appointment.status === 'confirmed' && (
                      <>
                        <button
                          onClick={() => handleCompleteAppointment(appointment.id)}
                          className="flex items-center gap-1 px-3 py-2 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition font-medium"
                        >
                          <CheckCircle className="w-4 h-4" />
                          Complete
                        </button>
                        <button
                          onClick={() => handleCancelAppointment(appointment.id)}
                          className="flex items-center gap-1 px-3 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition font-medium"
                        >
                          <XCircle className="w-4 h-4" />
                          Cancel
                        </button>
                      </>
                    )}
                  </div>
                </div>
              </div>
            </div>
          ))
        )}
      </div>
    </div>
  );
};

export default DoctorAppointments;
