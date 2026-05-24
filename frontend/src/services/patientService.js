import api from './api';

export const patientService = {
  updateProfile: async (userData) => {
    const response = await api.put('/patients/profile', userData);
    return response.data;
  },

  getMedicalRecords: async () => {
    const response = await api.get('/patients/records');
    return response.data;
  },

  uploadMedicalRecord: async (formData) => {
    const response = await api.post('/patients/records/upload', formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
    });
    return response.data;
  },

  deleteMedicalRecord: async (id) => {
    const response = await api.delete(`/patients/records/${id}`);
    return response.data;
  },

  getAICheckHistory: async () => {
    const response = await api.get('/patients/ai-checks');
    return response.data;
  },

  getDashboard: async () => {
    const response = await api.get('/patients/dashboard');
    return response.data;
  },
};
