import { useState, useEffect } from 'react';
import { useParams, Link, useNavigate } from 'react-router-dom';
import { doctorService } from '../services/doctorService';
import { appointmentService } from '../services/appointmentService';
import { toast } from 'react-hot-toast';
import { ProfileAvatar } from '../utils/photoUrl';
import {
  Star,
  MapPin,
  Clock,
  GraduationCap,
  Calendar,
  Video,
  Stethoscope,
  ChevronLeft,
  Calendar as CalendarIcon,
} from 'lucide-react';

const DoctorProfile = () => {
  const { id } = useParams();
  const navigate = useNavigate();
  const [doctor, setDoctor] = useState(null);
  const [timeSlots, setTimeSlots] = useState([]);
  const [selectedDate, setSelectedDate] = useState('');
  const [selectedSlot, setSelectedSlot] = useState(null);
  const [loading, setLoading] = useState(true);
  const [bookingLoading, setBookingLoading] = useState(false);

  useEffect(() => {
    loadDoctorData();
  }, [id]);

  const loadDoctorData = async () => {
    try {
      setLoading(true);
      const [doctorData, slotsData] = await Promise.all([
        doctorService.getDoctorById(id),
        doctorService.getTimeSlots(id),
      ]);
      
      setDoctor(doctorData.data);
      setTimeSlots(slotsData.data);
    } catch (error) {
      toast.error('Failed to load doctor data');
    } finally {
      setLoading(false);
    }
  };

  const handleBookAppointment = async () => {
    if (!selectedDate || !selectedSlot) {
      toast.error('Please select a date and time slot');
      return;
    }

    setBookingLoading(true);
    try {
      await appointmentService.bookAppointment({
        doctor_id: parseInt(id),
        date: selectedDate,
        time_slot: selectedSlot,
        type: 'in-person',
      });
      toast.success('Appointment booked successfully!');
      navigate('/dashboard');
    } catch (error) {
      toast.error(error.response?.data?.message || 'Failed to book appointment');
    } finally {
      setBookingLoading(false);
    }
  };

  if (loading) {
    return (
      <div className="flex items-center justify-center h-64">
        <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600"></div>
      </div>
    );
  }

  if (!doctor) {
    return (
      <div className="card text-center py-12">
        <Stethoscope className="w-16 h-16 text-gray-300 mx-auto mb-4" />
        <h3 className="text-lg font-semibold text-gray-900 mb-2">Doctor Not Found</h3>
        <Link to="/doctors" className="btn-primary">
          Back to Doctors
        </Link>
      </div>
    );
  }

  return (
    <div className="space-y-6">
      {/* Back Button */}
      <Link to="/doctors" className="inline-flex items-center text-gray-600 hover:text-gray-900">
        <ChevronLeft className="w-4 h-4 mr-1" />
        Back to Doctors
      </Link>

      {/* Doctor Header */}
      <div className="card">
        <div className="flex flex-col md:flex-row md:items-start md:space-x-6">
          <ProfileAvatar
            photo={doctor.user?.profile_photo}
            name={doctor.user?.name}
            size="lg"
            className="flex-shrink-0 mb-4 md:mb-0"
          />
          <div className="flex-1">
            <div className="flex flex-col md:flex-row md:items-start md:justify-between">
              <div>
                <h1 className="text-2xl font-bold text-gray-900">
                  Dr. {doctor.user?.name}
                </h1>
                <p className="text-lg text-gray-600 mt-1">{doctor.specialty}</p>
              </div>
              <div className="mt-4 md:mt-0 flex items-center space-x-4">
                <div className="flex items-center">
                  <Star className="w-5 h-5 text-yellow-400 fill-current" />
                  <span className="text-lg font-bold text-gray-900 ml-1">
                    {doctor.rating?.toFixed(1)}
                  </span>
                  <span className="text-gray-500 ml-1">
                    ({doctor.total_reviews} reviews)
                  </span>
                </div>
              </div>
            </div>

            <div className="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
              <div className="flex items-center text-gray-600">
                <GraduationCap className="w-5 h-5 mr-2" />
                {doctor.qualification}
              </div>
              <div className="flex items-center text-gray-600">
                <Clock className="w-5 h-5 mr-2" />
                {doctor.experience} years experience
              </div>
              <div className="flex items-center text-gray-600">
                <MapPin className="w-5 h-5 mr-2" />
                {doctor.medical_school || 'Medical School'}
              </div>
            </div>

            {doctor.available && (
              <div className="mt-4">
                <span className="badge badge-success">Available for Appointments</span>
              </div>
            )}
          </div>
        </div>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {/* About */}
        <div className="lg:col-span-2 space-y-6">
          <div className="card">
            <h2 className="text-xl font-bold text-gray-900 mb-4">About</h2>
            <p className="text-gray-600 leading-relaxed">
              {doctor.bio || 'No bio available.'}
            </p>
          </div>

          {/* Reviews */}
          <div className="card">
            <h2 className="text-xl font-bold text-gray-900 mb-4">Patient Reviews</h2>
            {doctor.reviews && doctor.reviews.length > 0 ? (
              <div className="space-y-4">
                {doctor.reviews.slice(0, 3).map((review) => (
                  <div key={review.id} className="border-b border-gray-200 pb-4 last:border-0 last:pb-0">
                    <div className="flex items-center justify-between mb-2">
                      <div className="flex items-center">
                        <div className="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center">
                          <span className="text-sm font-medium text-gray-600">
                            {review.patient?.user?.name?.charAt(0) || 'U'}
                          </span>
                        </div>
                        <span className="ml-2 font-medium text-gray-900">
                          {review.patient?.user?.name || 'Anonymous'}
                        </span>
                      </div>
                      <div className="flex items-center">
                        {[...Array(5)].map((_, i) => (
                          <Star
                            key={i}
                            className={`w-4 h-4 ${
                              i < review.rating
                                ? 'text-yellow-400 fill-current'
                                : 'text-gray-300'
                            }`}
                          />
                        ))}
                      </div>
                    </div>
                    <p className="text-gray-600 text-sm">{review.comment}</p>
                  </div>
                ))}
              </div>
            ) : (
              <p className="text-gray-600">No reviews yet.</p>
            )}
          </div>
        </div>

        {/* Booking */}
        <div className="lg:col-span-1">
          <div className="card sticky top-4">
            <h2 className="text-xl font-bold text-gray-900 mb-4">Book Appointment</h2>
            
            <div className="mb-4">
              <p className="text-sm text-gray-500">Consultation Fee</p>
              <p className="text-2xl font-bold text-gray-900">
                ${doctor.consultation_fee}
              </p>
            </div>

            <div className="space-y-4">
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-2">
                  Select Date
                </label>
                <input
                  type="date"
                  className="input-field"
                  value={selectedDate}
                  onChange={(e) => setSelectedDate(e.target.value)}
                  min={new Date().toISOString().split('T')[0]}
                />
              </div>

              {selectedDate && (
                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-2">
                    Available Time Slots
                  </label>
                  <div className="grid grid-cols-2 gap-2">
                    {['09:00-10:00', '10:00-11:00', '11:00-12:00', '14:00-15:00', '15:00-16:00', '16:00-17:00'].map((slot) => (
                      <button
                        key={slot}
                        onClick={() => setSelectedSlot(slot)}
                        className={`p-2 rounded-lg border-2 text-sm transition-all ${
                          selectedSlot === slot
                            ? 'border-primary-600 bg-primary-50 text-primary-700'
                            : 'border-gray-300 hover:border-gray-400'
                        }`}
                      >
                        {slot}
                      </button>
                    ))}
                  </div>
                </div>
              )}

              <div className="space-y-2">
                <button
                  onClick={handleBookAppointment}
                  disabled={bookingLoading || !selectedDate || !selectedSlot}
                  className="w-full btn-primary disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  {bookingLoading ? 'Booking...' : 'Book Appointment'}
                </button>
                <button className="w-full btn-outline flex items-center justify-center">
                  <Video className="w-4 h-4 mr-2" />
                  Book Video Consultation
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default DoctorProfile;
