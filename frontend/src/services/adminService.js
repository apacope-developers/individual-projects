import api from './api';

export const adminService = {
  getDashboardStats: async () => {
    const response = await api.get('/admin/dashboard');
    return response.data;
  },

  getAllUsers: async (params = {}) => {
    const response = await api.get('/admin/users', { params });
    return response.data;
  },

  getAllDoctors: async (params = {}) => {
    const response = await api.get('/admin/doctors', { params });
    return response.data;
  },

  createDoctor: async (data) => {
    const response = await api.post('/admin/doctors', data);
    return response.data;
  },

  approveDoctor: async (id) => {
    const response = await api.put(`/admin/doctors/${id}/approve`);
    return response.data;
  },

  suspendDoctor: async (id) => {
    const response = await api.put(`/admin/doctors/${id}/suspend`);
    return response.data;
  },

  deleteDoctor: async (id, reason = '') => {
    const response = await api.delete(`/admin/doctors/${id}`, {
      data: { reason }
    });
    return response.data;
  },

  deactivateUser: async (id) => {
    const response = await api.put(`/admin/users/${id}/deactivate`);
    return response.data;
  },

  activateUser: async (id) => {
    const response = await api.put(`/admin/users/${id}/activate`);
    return response.data;
  },

  deleteUser: async (id, reason = '') => {
    const response = await api.delete(`/admin/users/${id}`, {
      data: { reason }
    });
    return response.data;
  },

  getAllAppointments: async (params = {}) => {
    const response = await api.get('/admin/appointments', { params });
    return response.data;
  },

  getAnalytics: async (params = {}) => {
    const response = await api.get('/admin/analytics', { params });
    return response.data;
  },
};
