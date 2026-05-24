import { useState, useEffect } from 'react';
import { useAuth } from '../context/AuthContext';
import { doctorService } from '../services/doctorService';
import { toast } from 'react-hot-toast';
import {
  Pill,
  FileText,
  User,
  Calendar,
  Download,
  Plus,
  Search,
  Filter,
} from 'lucide-react';

const DoctorPrescriptions = () => {
  const { user } = useAuth();
  const [prescriptions, setPrescriptions] = useState([]);
  const [filteredPrescriptions, setFilteredPrescriptions] = useState([]);
  const [loading, setLoading] = useState(true);
  const [searchTerm, setSearchTerm] = useState('');
  const [showNewPrescriptionModal, setShowNewPrescriptionModal] = useState(false);

  useEffect(() => {
    loadPrescriptions();
  }, []);

  useEffect(() => {
    filterPrescriptions();
  }, [prescriptions, searchTerm]);

  const loadPrescriptions = async () => {
    try {
      // Fetch prescriptions from backend
      // For now, showing empty state
      setPrescriptions([]);
    } catch (error) {
      toast.error('Failed to load prescriptions');
      setPrescriptions([]);
    } finally {
      setLoading(false);
    }
  };

  const filterPrescriptions = () => {
    let filtered = prescriptions;

    if (searchTerm) {
      filtered = filtered.filter(prescription =>
        prescription.patient?.user?.name?.toLowerCase().includes(searchTerm.toLowerCase()) ||
        prescription.medication?.toLowerCase().includes(searchTerm.toLowerCase())
      );
    }

    setFilteredPrescriptions(filtered);
  };

  const handleCreatePrescription = async (e) => {
    e.preventDefault();
    toast.info('Prescription creation feature coming soon');
    setShowNewPrescriptionModal(false);
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
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-3xl font-bold text-gray-900">Prescriptions</h1>
          <p className="text-gray-600 mt-2">Manage patient prescriptions</p>
        </div>
        <button
          onClick={() => setShowNewPrescriptionModal(true)}
          className="flex items-center gap-2 px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition font-medium"
        >
          <Plus className="w-5 h-5" />
          New Prescription
        </button>
      </div>

      {/* Filters */}
      <div className="card">
        <div className="flex items-center gap-2 bg-gray-50 rounded-lg px-4 py-2">
          <Search className="w-5 h-5 text-gray-400" />
          <input
            type="text"
            placeholder="Search by patient name or medication..."
            value={searchTerm}
            onChange={(e) => setSearchTerm(e.target.value)}
            className="flex-1 bg-transparent outline-none text-gray-700"
          />
        </div>
      </div>

      {/* Prescriptions List */}
      <div className="space-y-4">
        {filteredPrescriptions.length === 0 ? (
          <div className="card text-center py-12">
            <Pill className="w-16 h-16 text-gray-300 mx-auto mb-4" />
            <h3 className="text-lg font-semibold text-gray-900 mb-2">No Prescriptions</h3>
            <p className="text-gray-600 mb-6">
              {searchTerm ? 'No prescriptions match your search' : 'You haven\'t created any prescriptions yet'}
            </p>
            <button
              onClick={() => setShowNewPrescriptionModal(true)}
              className="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition font-medium"
            >
              <Plus className="w-5 h-5" />
              Create Prescription
            </button>
          </div>
        ) : (
          filteredPrescriptions.map((prescription) => (
            <div key={prescription.id} className="card hover:shadow-lg transition">
              <div className="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div className="flex items-start gap-4 flex-1">
                  <div className="flex-shrink-0 w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center">
                    <Pill className="w-6 h-6 text-primary-600" />
                  </div>
                  <div className="flex-1">
                    <h3 className="font-semibold text-gray-900">
                      {prescription.patient?.user?.name || 'Unknown Patient'}
                    </h3>
                    <p className="text-sm text-gray-600 mt-1">
                      {prescription.medication || 'No medication'}
                    </p>
                    <div className="flex items-center gap-4 mt-2 text-sm text-gray-600">
                      <span className="flex items-center gap-1">
                        <Calendar className="w-4 h-4" />
                        {new Date(prescription.created_at).toLocaleDateString()}
                      </span>
                    </div>
                  </div>
                </div>

                <div className="flex gap-2">
                  <button className="flex items-center gap-2 px-3 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition font-medium">
                    <Download className="w-4 h-4" />
                    Download
                  </button>
                  <button className="flex items-center gap-2 px-3 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition font-medium">
                    <FileText className="w-4 h-4" />
                    View
                  </button>
                </div>
              </div>
            </div>
          ))
        )}
      </div>

      {/* New Prescription Modal */}
      {showNewPrescriptionModal && (
        <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
          <div className="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
            <h2 className="text-2xl font-bold text-gray-900 mb-4">Create New Prescription</h2>
            
            <form onSubmit={handleCreatePrescription} className="space-y-4">
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">
                  Patient
                </label>
                <select className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
                  <option>Select a patient...</option>
                </select>
              </div>

              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">
                  Medication
                </label>
                <input
                  type="text"
                  placeholder="Enter medication name"
                  className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500"
                />
              </div>

              <div className="grid grid-cols-2 gap-4">
                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-1">
                    Dosage
                  </label>
                  <input
                    type="text"
                    placeholder="e.g., 500mg"
                    className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500"
                  />
                </div>
                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-1">
                    Frequency
                  </label>
                  <input
                    type="text"
                    placeholder="e.g., 3 times daily"
                    className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500"
                  />
                </div>
              </div>

              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">
                  Duration
                </label>
                <input
                  type="text"
                  placeholder="e.g., 7 days"
                  className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500"
                />
              </div>

              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">
                  Notes
                </label>
                <textarea
                  rows="3"
                  placeholder="Additional instructions or notes..."
                  className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500"
                ></textarea>
              </div>

              <div className="flex gap-3 pt-4">
                <button
                  type="button"
                  onClick={() => setShowNewPrescriptionModal(false)}
                  className="flex-1 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition font-medium text-gray-700"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  className="flex-1 px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition font-medium"
                >
                  Create Prescription
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
};

export default DoctorPrescriptions;
