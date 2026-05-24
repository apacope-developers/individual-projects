import api from './api';

export const prescriptionService = {
  createPrescription: async (prescriptionData) => {
    const response = await api.post('/prescriptions', prescriptionData);
    return response.data;
  },

  getPatientPrescriptions: async () => {
    const response = await api.get('/prescriptions/my');
    return response.data;
  },

  getPrescriptionById: async (id) => {
    const response = await api.get(`/prescriptions/${id}`);
    return response.data;
  },
};
