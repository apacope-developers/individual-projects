import { Outlet, Link, useLocation } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import { useLanguage } from '../context/LanguageContext';
import { useTheme } from '../context/ThemeContext';
import { ProfileAvatar } from '../utils/photoUrl';
import { 
  LayoutDashboard, 
  Calendar, 
  Stethoscope, 
  FileText, 
  Brain, 
  Pill, 
  FolderOpen, 
  User, 
  LogOut,
  Bell,
  Settings,
  Sun,
  Moon,
  Globe,
} from 'lucide-react';

const Layout = () => {
  const { user, logout } = useAuth();
  const { t, language, setLanguage } = useLanguage();
  const { theme, toggleTheme } = useTheme();
  const location = useLocation();

  const homePath = user?.role === 'admin'
    ? '/admin/dashboard'
    : user?.role === 'doctor'
      ? '/doctor/dashboard'
      : '/dashboard';

  const isActive = (path) => location.pathname === path;

  const getNavItems = () => {
    if (user?.role === 'patient') {
      return [
        { path: '/dashboard', icon: LayoutDashboard, label: t('dashboard') },
        { path: '/doctors', icon: Stethoscope, label: t('findDoctors') },
        { path: '/book-appointment', icon: Calendar, label: t('bookAppointment') },
        { path: '/ai-checker', icon: Brain, label: t('aiChecker') },
        { path: '/prescriptions', icon: Pill, label: t('prescriptions') },
        { path: '/medical-records', icon: FolderOpen, label: t('medicalRecords') },
        { path: '/profile', icon: User, label: t('profile') },
      ];
    } else if (user?.role === 'doctor') {
      return [
        { path: '/doctor/dashboard', icon: LayoutDashboard, label: t('dashboard') },
        { path: '/doctor/appointments', icon: Calendar, label: t('appointments') },
        { path: '/doctor/patients', icon: User, label: t('patients') },
        { path: '/doctor/prescriptions', icon: Pill, label: t('prescriptions') },
        { path: '/profile', icon: User, label: t('profile') },
      ];
    } else if (user?.role === 'admin') {
      return [
        { path: '/admin/dashboard', icon: LayoutDashboard, label: t('dashboard') },
        { path: '/admin/users', icon: User, label: t('users') },
        { path: '/admin/doctors', icon: Stethoscope, label: t('doctors') },
        { path: '/admin/appointments', icon: Calendar, label: t('appointments') },
        { path: '/admin/settings', icon: Settings, label: t('settings') },
      ];
    }
    return [];
  };

  const navItems = getNavItems();

  return (
    <div className="min-h-screen bg-gray-50 dark:bg-gray-900">
      <header className="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex justify-between items-center h-16">
            <Link to={homePath} className="flex items-center space-x-2">
              <Stethoscope className="w-8 h-8 text-primary-600" />
              <span className="text-xl font-bold text-gray-900 dark:text-white">{t('appName')}</span>
            </Link>
            
            <div className="flex items-center space-x-3">
              {/* Language switcher */}
              <div className="flex items-center gap-1 bg-gray-100 dark:bg-gray-700 rounded-lg p-1">
                <Globe className="w-4 h-4 text-gray-500 ml-1" />
                {['en', 'fr', 'rw'].map((lang) => (
                  <button
                    key={lang}
                    onClick={() => setLanguage(lang)}
                    className={`px-2 py-1 text-xs font-medium rounded ${
                      language === lang
                        ? 'bg-primary-600 text-white'
                        : 'text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'
                    }`}
                  >
                    {lang.toUpperCase()}
                  </button>
                ))}
              </div>

              {/* Theme toggle */}
              <button
                onClick={toggleTheme}
                className="p-2 text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700"
                title={theme === 'dark' ? t('lightMode') : t('darkMode')}
              >
                {theme === 'dark' ? <Sun className="w-5 h-5" /> : <Moon className="w-5 h-5" />}
              </button>

              <button className="relative p-2 text-gray-600 dark:text-gray-300">
                <Bell className="w-6 h-6" />
                <span className="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
              </button>
              
              <div className="flex items-center space-x-3">
                <div className="text-right hidden sm:block">
                  <p className="text-sm font-medium text-gray-900 dark:text-white">{user?.name}</p>
                  <p className="text-xs text-gray-500 dark:text-gray-400 capitalize">{user?.role}</p>
                </div>
                <ProfileAvatar photo={user?.profile_photo} name={user?.name} size="sm" />
              </div>
              
              <button
                onClick={logout}
                className="p-2 text-gray-600 dark:text-gray-300 hover:text-red-600 transition-colors"
                title={t('logout')}
              >
                <LogOut className="w-5 h-5" />
              </button>
            </div>
          </div>
        </div>
      </header>

      <div className="flex">
        <aside className="w-64 bg-white dark:bg-gray-800 shadow-sm min-h-[calc(100vh-4rem)] border-r border-gray-200 dark:border-gray-700">
          <nav className="p-4 space-y-1">
            {navItems.map((item) => {
              const Icon = item.icon;
              return (
                <Link
                  key={item.path}
                  to={item.path}
                  className={`flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors ${
                    isActive(item.path)
                      ? 'bg-primary-50 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300'
                      : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'
                  }`}
                >
                  <Icon className="w-5 h-5" />
                  <span className="font-medium">{item.label}</span>
                </Link>
              );
            })}
          </nav>
        </aside>

        <main className="flex-1 p-8">
          <Outlet />
        </main>
      </div>
    </div>
  );
};

export default Layout;
