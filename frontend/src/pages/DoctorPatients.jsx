import { useState, useEffect } from 'react';
import { useAuth } from '../context/AuthContext';
import { useLanguage } from '../context/LanguageContext';
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
  const { t } = useLanguage();
  const [patients, setPatients] = useState([]);
  const [filteredPatients, setFilteredPatients] = useState([]);
  const [loading, setLoading] = useState(true);
  const [searchTerm, setSearchTerm] = useState('');
  const [selectedPatient, setSelectedPatient] = useState(null);

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

  const handleViewDetails = (patient) => {
    setSelectedPatient(patient);
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
        <h1 className="text-3xl font-bold text-gray-900">{t('patients')}</h1>
        <p className="text-gray-600 mt-2">{t('manageYourPatientList')}</p>
      </div>

      {/* Search Bar */}
      <div className="card">
        <div className="flex items-center gap-2 bg-gray-50 rounded-lg px-4 py-2">
          <Search className="w-5 h-5 text-gray-400" />
          <input
            type="text"
            placeholder={t('searchPatientsPlaceholder')}
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
            <h3 className="text-lg font-semibold text-gray-900 mb-2">{t('noPatients')}</h3>
            <p className="text-gray-600">
              {searchTerm ? t('noPatientsMatchSearch') : t('noPatientsYet')}
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
                    {t('active')}
                  </span>
                </div>

                {/* Patient Info */}
                <h3 className="font-semibold text-gray-900 text-lg">
                  {patient.user?.name || t('unknownPatient')}
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
                    <p className="text-sm text-gray-600">{t('appointments')}</p>
                    <p className="text-lg font-semibold text-gray-900">-</p>
                  </div>
                  <div>
                    <p className="text-sm text-gray-600">{t('lastVisit')}</p>
                    <p className="text-lg font-semibold text-gray-900">-</p>
                  </div>
                </div>

                {/* Action Button */}
                <button
                  onClick={() => handleViewDetails(patient)}
                  className="mt-4 w-full px-4 py-2 bg-primary-50 text-primary-600 rounded-lg hover:bg-primary-100 transition font-medium"
                >
                  {t('viewDetails')}
                </button>
              </div>
            </div>
          ))
        )}
      </div>

      {selectedPatient && (
        <div className="fixed inset-0 bg-black/40 flex items-center justify-center p-4 z-50">
          <div className="bg-white dark:bg-slate-950 rounded-3xl shadow-2xl max-w-2xl w-full overflow-hidden">
            <div className="flex items-center justify-between px-6 py-5 border-b border-gray-200 dark:border-gray-800">
              <div>
                <h2 className="text-xl font-semibold text-gray-900 dark:text-white">{selectedPatient.user?.name}</h2>
                <p className="text-sm text-gray-500 dark:text-gray-400">{t('patientDetails')}</p>
              </div>
              <button
                onClick={() => setSelectedPatient(null)}
                className="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200"
              >
                ✕
              </button>
            </div>
            <div className="p-6 space-y-5">
              <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <p className="text-sm text-gray-500 dark:text-gray-400">{t('email')}</p>
                  <p className="mt-1 text-gray-900 dark:text-white break-all">{selectedPatient.user?.email || '—'}</p>
                </div>
                <div>
                  <p className="text-sm text-gray-500 dark:text-gray-400">{t('phone')}</p>
                  <p className="mt-1 text-gray-900 dark:text-white">{selectedPatient.user?.phone || '—'}</p>
                </div>
              </div>
              <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <p className="text-sm text-gray-500 dark:text-gray-400">{t('joined')}</p>
                  <p className="mt-1 text-gray-900 dark:text-white">{new Date(selectedPatient.user?.createdAt || Date.now()).toLocaleDateString()}</p>
                </div>
                <div>
                  <p className="text-sm text-gray-500 dark:text-gray-400">{t('status')}</p>
                  <p className="mt-1 text-gray-900 dark:text-white">{t('active')}</p>
                </div>
              </div>
              <div className="space-y-2">
                <p className="text-sm text-gray-500 dark:text-gray-400">{t('notes')}</p>
                <p className="text-gray-900 dark:text-white">{t('patientContactNotes')}</p>
              </div>
              <div className="flex justify-end gap-3">
                <button
                  onClick={() => setSelectedPatient(null)}
                  className="btn-outline"
                >
                  {t('close')}
                </button>
              </div>
            </div>
          </div>
        </div>
      )}
    </div>
  );
};

export default DoctorPatients;
