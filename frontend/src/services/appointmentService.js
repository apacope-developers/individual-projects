import api from './api';

export const appointmentService = {
  bookAppointment: async (appointmentData) => {
    const response = await api.post('/appointments', appointmentData);
    return response.data;
  },

  getPatientAppointments: async (params = {}) => {
    const response = await api.get('/appointments/my', { params });
    return response.data;
  },

  getAppointmentById: async (id) => {
    const response = await api.get(`/appointments/${id}`);
    return response.data;
  },

  cancelAppointment: async (id, reason) => {
    const response = await api.put(`/appointments/${id}/cancel`, { reason });
    return response.data;
  },

  rescheduleAppointment: async (id, date, time_slot) => {
    const response = await api.put(`/appointments/${id}/reschedule`, { date, time_slot });
    return response.data;
  },

  confirmAppointment: async (id) => {
    const response = await api.put(`/appointments/${id}/confirm`);
    return response.data;
  },

  completeAppointment: async (id) => {
    const response = await api.put(`/appointments/${id}/complete`);
    return response.data;
  },
};
