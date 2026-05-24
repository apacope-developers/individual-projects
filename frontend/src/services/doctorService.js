import api from './api';

export const doctorService = {
  getAllDoctors: async (params = {}) => {
    const response = await api.get('/doctors', { params });
    return response.data;
  },

  getDoctorById: async (id) => {
    const response = await api.get(`/doctors/${id}`);
    return response.data;
  },

  getTimeSlots: async (doctorId) => {
    const response = await api.get(`/doctors/${doctorId}/time-slots`);
    return response.data;
  },

  updateProfile: async (userData) => {
    const response = await api.put('/doctors/profile', userData);
    return response.data;
  },

  getAppointments: async (params = {}) => {
    const response = await api.get('/doctors/my/appointments', { params });
    return response.data;
  },

  setTimeSlots: async (timeSlots) => {
    const response = await api.put('/doctors/time-slots', { timeSlots });
    return response.data;
  },

  getStats: async () => {
    const response = await api.get('/doctors/my/stats');
    return response.data;
  },
};
