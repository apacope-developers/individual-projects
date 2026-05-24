import { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { useLanguage } from '../context/LanguageContext';
import { doctorService } from '../services/doctorService';
import { toast } from 'react-hot-toast';
import { ProfileAvatar } from '../utils/photoUrl';
import {
  Search,
  Filter,
  Star,
  MapPin,
  Clock,
  Stethoscope,
} from 'lucide-react';

const DoctorList = () => {
  const { t } = useLanguage();
  const [doctors, setDoctors] = useState([]);
  const [loading, setLoading] = useState(true);
  const [sampleShown, setSampleShown] = useState(false);
  const [filters, setFilters] = useState({
    specialty: '',
    search: '',
    available: false,
  });

  const specialties = [
    'General Practice',
    'Cardiology',
    'Dermatology',
    'Neurology',
    'Pediatrics',
    'Orthopedics',
    'Psychiatry',
    'Ophthalmology',
    'Gynecology',
    'Urology',
  ];

  useEffect(() => {
    loadDoctors();
  }, [filters]);

  const loadDoctors = async () => {
    try {
      setLoading(true);
      const params = {};
      if (filters.specialty) params.specialty = filters.specialty;
      if (filters.search) params.search = filters.search;
      if (filters.available) params.available = true;

      const response = await doctorService.getAllDoctors(params);
      // `doctorService.getAllDoctors` returns the API body: { success, data, meta }
      setDoctors(response.data || []);
      setSampleShown(!!(response.meta && response.meta.sample));
    } catch (error) {
      toast.error(t('failedToLoad'));
    } finally {
      setLoading(false);
    }
  };

  const handleFilterChange = (key, value) => {
    setFilters({ ...filters, [key]: value });
  };

  if (loading) {
    return (
      <div className="flex items-center justify-center h-64">
        <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600"></div>
      </div>
    );
  }

  return (
    <div className="space-y-6">
      {/* Header */}
      <div>
        <h1 className="text-3xl font-bold text-gray-900 dark:text-white">{t('findDoctors')}</h1>
        <p className="text-gray-600 dark:text-gray-400 mt-2">Browse and book appointments with qualified healthcare professionals</p>
      </div>

      {/* Search and Filters */}
      <div className="card">
        <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
          {/* Search */}
          <div className="md:col-span-2">
            <div className="relative">
              <Search className="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" />
              <input
                type="text"
                placeholder="Search by name or specialty..."
                className="input-field pl-10"
                value={filters.search}
                onChange={(e) => handleFilterChange('search', e.target.value)}
              />
            </div>
          </div>

          {/* Specialty Filter */}
          <div>
            <select
              className="input-field"
              value={filters.specialty}
              onChange={(e) => handleFilterChange('specialty', e.target.value)}
            >
              <option value="">All Specialties</option>
              {specialties.map((specialty) => (
                <option key={specialty} value={specialty}>
                  {specialty}
                </option>
              ))}
            </select>
          </div>

          {/* Available Only */}
          <div className="flex items-center">
            <label className="flex items-center cursor-pointer">
              <input
                type="checkbox"
                checked={filters.available}
                onChange={(e) => handleFilterChange('available', e.target.checked)}
                className="w-4 h-4 text-primary-600 rounded border-gray-300"
              />
              <span className="ml-2 text-sm text-gray-700 dark:text-gray-300">Available Now</span>
            </label>
          </div>
        </div>
      </div>

      {/* Results Count */}
      <div className="flex items-center justify-between">
        <div className="flex items-center">
          <p className="text-gray-600 dark:text-gray-400">
            {doctors.length} {doctors.length === 1 ? 'doctor' : 'doctors'} found
          </p>
          {sampleShown && (
            <span
              role="status"
              aria-label="Sample doctors shown"
              className="ml-3 px-3 py-1 text-sm bg-yellow-100 dark:bg-yellow-900/40 text-yellow-800 dark:text-yellow-300 rounded-full border border-yellow-200 dark:border-yellow-800"
            >
              Sample doctors shown
            </span>
          )}
        </div>
        <button className="flex items-center text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300">
          <Filter className="w-4 h-4 mr-2" />
          More Filters
        </button>
      </div>

      {/* Doctor Cards */}
      {doctors.length > 0 ? (
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          {doctors.map((doctor) => (
            <div key={doctor.id} className="card hover:shadow-lg transition-shadow">
              <div className="flex items-start space-x-4">
                <ProfileAvatar
                  photo={doctor.user?.profile_photo}
                  name={doctor.user?.name}
                  size="md"
                  className="flex-shrink-0"
                />
                <div className="flex-1 min-w-0">
                  <h3 className="font-semibold text-gray-900 dark:text-white truncate">
                    Dr. {doctor.user?.name}
                  </h3>
                  <p className="text-sm text-gray-600 dark:text-gray-400">{doctor.specialty}</p>
                  <div className="flex items-center mt-2">
                    <Star className="w-4 h-4 text-yellow-400 fill-current" />
                    <span className="text-sm font-medium text-gray-900 dark:text-white ml-1">
                      {(doctor.rating !== undefined && doctor.rating !== null)
                        ? Number(doctor.rating).toFixed(1)
                        : '0.0'}
                    </span>
                    <span className="text-sm text-gray-500 dark:text-gray-400 ml-1">
                      ({doctor.total_reviews} reviews)
                    </span>
                  </div>
                </div>
              </div>

              <div className="mt-4 space-y-2">
                <div className="flex items-center text-sm text-gray-600 dark:text-gray-400">
                  <MapPin className="w-4 h-4 mr-2" />
                  {doctor.hospital || doctor.qualification}
                </div>
                <div className="flex items-center text-sm text-gray-600 dark:text-gray-400">
                  <Clock className="w-4 h-4 mr-2" />
                  {doctor.experience} years experience
                </div>
                {doctor.location && (
                  <div className="flex items-center text-sm text-gray-600 dark:text-gray-400">
                    <MapPin className="w-4 h-4 mr-2" />
                    {doctor.location}
                  </div>
                )}
              </div>

              <div className="mt-4 flex items-center justify-between">
                {doctor.available ? (
                  <span className="badge badge-success">Available</span>
                ) : (
                  <span className="badge badge-warning">Unavailable</span>
                )}
                <div className="flex space-x-2">
                  <Link
                    to={`/doctors/${doctor.id}`}
                    className="btn-outline text-xs"
                  >
                    View
                  </Link>
                  <Link
                    to={`/book-appointment/${doctor.id}`}
                    className="btn-primary text-xs"
                  >
                    Book
                  </Link>
                </div>
              </div>
            </div>
          ))}
        </div>
      ) : (
        <div className="card text-center py-12">
          <Stethoscope className="w-16 h-16 text-gray-300 dark:text-gray-600 mx-auto mb-4" />
          <h3 className="text-lg font-semibold text-gray-900 dark:text-white mb-2">No Doctors Found</h3>
          <p className="text-gray-600 dark:text-gray-400">Try adjusting your filters or search terms</p>
        </div>
      )}
    </div>
  );
};

export default DoctorList;
