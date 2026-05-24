import { useState, useEffect } from 'react';
import { useAuth } from '../context/AuthContext';
import { doctorService } from '../services/doctorService';
import { toast } from 'react-hot-toast';
import {
  User,
  Phone,
  Mail,
  Users,
  Search,
  Filter,
  Calendar,
  Heart,
} from 'lucide-react';

const DoctorPatients = () => {
  const { user } = useAuth();
  const [patients, setPatients] = useState([]);
  const [filteredPatients, setFilteredPatients] = useState([]);
  const [loading, setLoading] = useState(true);
  const [searchTerm, setSearchTerm] = useState('');

  useEffect(() => {
    loadPatients();
  }, []);

  useEffect(() => {
    filterPatients();
  }, [patients, searchTerm]);

  const loadPatients = async () => {
    try {
      // Get all appointments to extract unique patients
      const appointmentsData = await doctorService.getAppointments();
      const uniquePatients = {};
      
      (appointmentsData.data || []).forEach(appointment => {
        if (appointment.patient && !uniquePatients[appointment.patient.user_id]) {
          uniquePatients[appointment.patient.user_id] = appointment.patient;
        }
      });

      setPatients(Object.values(uniquePatients));
    } catch (error) {
      toast.error('Failed to load patients');
      setPatients([]);
    } finally {
      setLoading(false);
    }
  };

  const filterPatients = () => {
    let filtered = patients;

    if (searchTerm) {
      filtered = filtered.filter(patient =>
        patient.user?.name?.toLowerCase().includes(searchTerm.toLowerCase()) ||
        patient.user?.email?.toLowerCase().includes(searchTerm.toLowerCase()) ||
        patient.user?.phone?.toLowerCase().includes(searchTerm.toLowerCase())
      );
    }

    setFilteredPatients(filtered);
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
        <h1 className="text-3xl font-bold text-gray-900">Patients</h1>
        <p className="text-gray-600 mt-2">Manage your patient list</p>
      </div>

      {/* Search Bar */}
      <div className="card">
        <div className="flex items-center gap-2 bg-gray-50 rounded-lg px-4 py-2">
          <Search className="w-5 h-5 text-gray-400" />
          <input
            type="text"
            placeholder="Search by name, email, or phone..."
            value={searchTerm}
            onChange={(e) => setSearchTerm(e.target.value)}
            className="flex-1 bg-transparent outline-none text-gray-700"
          />
        </div>
      </div>

      {/* Patients Grid */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {filteredPatients.length === 0 ? (
          <div className="col-span-full card text-center py-12">
            <Users className="w-16 h-16 text-gray-300 mx-auto mb-4" />
            <h3 className="text-lg font-semibold text-gray-900 mb-2">No Patients</h3>
            <p className="text-gray-600">
              {searchTerm ? 'No patients match your search' : 'You have no patients yet'}
            </p>
          </div>
        ) : (
          filteredPatients.map((patient) => (
            <div key={patient.id} className="card hover:shadow-lg transition">
              <div className="flex flex-col">
                {/* Patient Avatar */}
                <div className="flex items-center justify-between mb-4">
                  <div className="flex-shrink-0 w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center">
                    <User className="w-6 h-6 text-primary-600" />
                  </div>
                  <span className="px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">
                    Active
                  </span>
                </div>

                {/* Patient Info */}
                <h3 className="font-semibold text-gray-900 text-lg">
                  {patient.user?.name || 'Unknown Patient'}
                </h3>

                {/* Contact Details */}
                <div className="mt-4 space-y-2 text-sm text-gray-600">
                  {patient.user?.email && (
                    <div className="flex items-start gap-2">
                      <Mail className="w-4 h-4 mt-0.5 flex-shrink-0" />
                      <span className="break-all">{patient.user.email}</span>
                    </div>
                  )}
                  {patient.user?.phone && (
                    <div className="flex items-center gap-2">
                      <Phone className="w-4 h-4" />
                      <span>{patient.user.phone}</span>
                    </div>
                  )}
                </div>

                {/* Stats */}
                <div className="mt-4 pt-4 border-t border-gray-200 grid grid-cols-2 gap-4 text-center">
                  <div>
                    <p className="text-sm text-gray-600">Appointments</p>
                    <p className="text-lg font-semibold text-gray-900">-</p>
                  </div>
                  <div>
                    <p className="text-sm text-gray-600">Last Visit</p>
                    <p className="text-lg font-semibold text-gray-900">-</p>
                  </div>
                </div>

                {/* Action Button */}
                <button className="mt-4 w-full px-4 py-2 bg-primary-50 text-primary-600 rounded-lg hover:bg-primary-100 transition font-medium">
                  View Details
                </button>
              </div>
            </div>
          ))
        )}
      </div>
    </div>
  );
};

export default DoctorPatients;
