import api from './api';

export const aiService = {
  submitSymptoms: async (symptoms) => {
    const response = await api.post('/ai/check', { symptoms });
    return response.data;
  },

  getAICheckById: async (id) => {
    const response = await api.get(`/ai/check/${id}`);
    return response.data;
  },
};
