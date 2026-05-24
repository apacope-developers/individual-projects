import axios from 'axios';

// Always use Vite proxy in dev (works on 3000, 3001, etc.) — avoids CORS entirely
export const API_BASE_URL = import.meta.env.DEV
  ? '/api'
  : (import.meta.env.VITE_API_URL || 'http://localhost:5000/api');

export const API_ORIGIN = import.meta.env.VITE_API_URL
  ? import.meta.env.VITE_API_URL.replace('/api', '')
  : (import.meta.env.DEV ? '' : window.location.origin);

export const getAssetUrl = (assetPath) => {
  if (!assetPath) return '';
  if (assetPath.startsWith('http')) return assetPath;
  return `${API_ORIGIN}${assetPath}`;
};

const api = axios.create({
  baseURL: API_BASE_URL,
  headers: {
    'Content-Type': 'application/json',
  },
});

// Request interceptor to add token
api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => Promise.reject(error)
);

// Response interceptor to handle errors
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      const isAuthRoute = error.config?.url?.includes('/auth/login')
        || error.config?.url?.includes('/auth/register');
      const isPublicPage = ['/login', '/register', '/'].includes(window.location.pathname);
      if (!isAuthRoute) {
        localStorage.removeItem('token');
        delete api.defaults.headers.common['Authorization'];
        if (!isPublicPage) {
          window.location.href = '/login';
        }
      }
    }
    return Promise.reject(error);
  }
);

export default api;
