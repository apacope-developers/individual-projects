import { useState, useEffect } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import { doctorService } from '../services/doctorService';
import { appointmentService } from '../services/appointmentService';
import { useLanguage } from '../context/LanguageContext';
import { toast } from 'react-hot-toast';
import { Calendar, Clock, Video, User, Stethoscope, ChevronLeft } from 'lucide-react';

const BookAppointment = () => {
  const { doctorId } = useParams();
  const navigate = useNavigate();
  const { t } = useLanguage();
  const [doctor, setDoctor] = useState(null);
  const [selectedDate, setSelectedDate] = useState('');
  const [selectedSlot, setSelectedSlot] = useState(null);
  const [appointmentType, setAppointmentType] = useState('in-person');
  const [reason, setReason] = useState('');
  const [loading, setLoading] = useState(true);
  const [bookingLoading, setBookingLoading] = useState(false);

  useEffect(() => {
    loadDoctorData();
  }, [doctorId]);

  const loadDoctorData = async () => {
    try {
      setLoading(true);
      const response = await doctorService.getDoctorById(doctorId);
      setDoctor(response.data);
    } catch (error) {
      toast.error(t('failedLoadDoctor'));
    } finally {
      setLoading(false);
    }
  };

  const timeSlots = [
    '09:00-10:00',
    '10:00-11:00',
    '11:00-12:00',
    '12:00-13:00',
    '14:00-15:00',
    '15:00-16:00',
    '16:00-17:00',
    '17:00-18:00',
  ];

  const handleBookAppointment = async (e) => {
    e.preventDefault();
    
    if (!selectedDate || !selectedSlot) {
      toast.error(t('selectDateAndSlot'));
      return;
    }

    setBookingLoading(true);
    try {
      await appointmentService.bookAppointment({
        doctor_id: parseInt(doctorId),
        date: selectedDate,
        time_slot: selectedSlot,
        type: appointmentType,
        reason,
      });
      toast.success(t('bookingSuccessful'));
      navigate('/dashboard');
    } catch (error) {
      toast.error(error.response?.data?.message || t('failedBookAppointment'));
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
        <h3 className="text-lg font-semibold text-gray-900 mb-2">{t('doctorNotFound')}</h3>
      </div>
    );
  }

  return (
    <div className="max-w-4xl mx-auto">
      <button
        onClick={() => navigate(-1)}
        className="inline-flex items-center text-gray-600 hover:text-gray-900 mb-6"
      >
        <ChevronLeft className="w-4 h-4 mr-1" />
        {t('backButton')}
      </button>

      <div className="card">
        <h1 className="text-2xl font-bold text-gray-900 mb-6">{t('bookAppointment')}</h1>

        {/* Doctor Info */}
        <div className="bg-gray-50 rounded-lg p-4 mb-6">
          <div className="flex items-center space-x-4">
            <div className="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center">
              <Stethoscope className="w-8 h-8 text-primary-600" />
            </div>
            <div>
              <h2 className="text-lg font-semibold text-gray-900">
                Dr. {doctor.user?.name}
              </h2>
              <p className="text-gray-600">{doctor.specialty}</p>
              <p className="text-sm text-gray-500 mt-1">
                {t('consultationFee')} ${doctor.consultation_fee}
              </p>
            </div>
          </div>
        </div>

        <form onSubmit={handleBookAppointment} className="space-y-6">
          {/* Appointment Type */}
          <div>
            <label className="block text-sm font-medium text-gray-700 mb-3">
              {t('appointmentType')}
            </label>
            <div className="grid grid-cols-2 gap-4">
              <button
                type="button"
                onClick={() => setAppointmentType('in-person')}
                className={`p-4 rounded-lg border-2 transition-all ${
                  appointmentType === 'in-person'
                    ? 'border-primary-600 bg-primary-50 text-primary-700'
                    : 'border-gray-300 hover:border-gray-400'
                }`}
              >
                <User className="w-6 h-6 mx-auto mb-2" />
                <span className="font-medium">{t('inPerson')}</span>
              </button>
              <button
                type="button"
                onClick={() => setAppointmentType('video')}
                className={`p-4 rounded-lg border-2 transition-all ${
                  appointmentType === 'video'
                    ? 'border-primary-600 bg-primary-50 text-primary-700'
                    : 'border-gray-300 hover:border-gray-400'
                }`}
              >
                <Video className="w-6 h-6 mx-auto mb-2" />
                <span className="font-medium">{t('videoCall')}</span>
              </button>
            </div>
          </div>

          {/* Date Selection */}
          <div>
            <label className="block text-sm font-medium text-gray-700 mb-2">
              {t('selectDate')}
            </label>
            <div className="relative">
              <Calendar className="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" />
              <input
                type="date"
                className="input-field pl-10"
                value={selectedDate}
                onChange={(e) => setSelectedDate(e.target.value)}
                min={new Date().toISOString().split('T')[0]}
                required
              />
            </div>
          </div>

          {/* Time Slot Selection */}
          {selectedDate && (
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-3">
                {t('selectTimeSlot')}
              </label>
              <div className="grid grid-cols-2 md:grid-cols-4 gap-3">
                {timeSlots.map((slot) => (
                  <button
                    key={slot}
                    type="button"
                    onClick={() => setSelectedSlot(slot)}
                    className={`p-3 rounded-lg border-2 text-sm transition-all ${
                      selectedSlot === slot
                        ? 'border-primary-600 bg-primary-50 text-primary-700'
                        : 'border-gray-300 hover:border-gray-400'
                    }`}
                  >
                    <Clock className="w-4 h-4 mx-auto mb-1" />
                    {slot}
                  </button>
                ))}
              </div>
            </div>
          )}

          {/* Reason */}
          <div>
            <label className="block text-sm font-medium text-gray-700 mb-2">
              {t('reasonForVisit')}
            </label>
            <textarea
              className="input-field"
              rows="3"
              placeholder={t('describeSymptoms')}
              value={reason}
              onChange={(e) => setReason(e.target.value)}
              maxLength="500"
            />
            <p className="text-xs text-gray-500 mt-1">{reason.length}/500 {t('charactersLimit')}</p>
          </div>

          {/* Submit */}
          <div className="flex items-center justify-between pt-4 border-t">
            <div className="text-lg font-bold text-gray-900">
              {t('total')} ${doctor.consultation_fee}
            </div>
            <button
              type="submit"
              disabled={bookingLoading || !selectedDate || !selectedSlot}
              className="btn-primary disabled:opacity-50 disabled:cursor-not-allowed"
            >
              {bookingLoading ? t('booking') : t('confirmBooking')}
            </button>
          </div>
        </form>
      </div>
    </div>
  );
};

export default BookAppointment;
