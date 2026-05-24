import { createContext, useContext, useState, useEffect } from 'react';
import api from '../services/api';

const AuthContext = createContext(null);

const REMEMBER_EMAIL_KEY = 'rememberedEmail';
const REMEMBER_ME_KEY = 'rememberMe';

export const AuthProvider = ({ children }) => {
  const [user, setUser] = useState(null);
  const [loading, setLoading] = useState(true);
  const [token, setToken] = useState(() => localStorage.getItem('token'));

  useEffect(() => {
    const checkAuth = async () => {
      const storedToken = localStorage.getItem('token');
      if (storedToken) {
        try {
          api.defaults.headers.common['Authorization'] = `Bearer ${storedToken}`;
          const response = await api.get('/auth/profile');
          setUser(response.data.data);
          setToken(storedToken);
        } catch {
          localStorage.removeItem('token');
          setToken(null);
          setUser(null);
          delete api.defaults.headers.common['Authorization'];
        }
      } else {
        delete api.defaults.headers.common['Authorization'];
      }
      setLoading(false);
    };

    checkAuth();
  }, []);

  const login = async (email, password, rememberMe = false) => {
    const normalizedEmail = String(email).trim().toLowerCase();
    const response = await api.post('/auth/login', {
      email: normalizedEmail,
      password,
    });
    const { user: userData, accessToken } = response.data.data;

    localStorage.setItem('token', accessToken);
    if (rememberMe) {
      localStorage.setItem(REMEMBER_EMAIL_KEY, normalizedEmail);
      localStorage.setItem(REMEMBER_ME_KEY, 'true');
    } else {
      localStorage.removeItem(REMEMBER_EMAIL_KEY);
      localStorage.removeItem(REMEMBER_ME_KEY);
    }

    setToken(accessToken);
    setUser(userData);
    api.defaults.headers.common['Authorization'] = `Bearer ${accessToken}`;

    return userData;
  };

  const register = async (userData) => {
    const payload = {
      ...userData,
      email: String(userData.email).trim().toLowerCase(),
    };
    const response = await api.post('/auth/register', payload);
    const { user: newUser, accessToken } = response.data.data;

    localStorage.setItem('token', accessToken);
    localStorage.setItem(REMEMBER_EMAIL_KEY, payload.email);
    localStorage.setItem(REMEMBER_ME_KEY, 'true');

    setToken(accessToken);
    setUser(newUser);
    api.defaults.headers.common['Authorization'] = `Bearer ${accessToken}`;

    return newUser;
  };

  const logout = async () => {
    const rememberedEmail = localStorage.getItem(REMEMBER_EMAIL_KEY);
    const rememberMe = localStorage.getItem(REMEMBER_ME_KEY);

    try {
      await api.post('/auth/logout');
    } catch {
      // Clear client session even if server unreachable
    }

    localStorage.removeItem('token');
    setToken(null);
    setUser(null);
    delete api.defaults.headers.common['Authorization'];

    if (rememberMe === 'true' && rememberedEmail) {
      localStorage.setItem(REMEMBER_EMAIL_KEY, rememberedEmail);
      localStorage.setItem(REMEMBER_ME_KEY, 'true');
    }
  };

  const getRememberedEmail = () => localStorage.getItem(REMEMBER_EMAIL_KEY) || '';

  const updateUser = (userData) => {
    setUser(userData);
  };

  const value = {
    user,
    token,
    loading,
    login,
    register,
    logout,
    updateUser,
    getRememberedEmail,
  };

  return <AuthContext.Provider value={value}>{children}</AuthContext.Provider>;
};

export const useAuth = () => {
  const context = useContext(AuthContext);
  if (!context) {
    throw new Error('useAuth must be used within an AuthProvider');
  }
  return context;
};
