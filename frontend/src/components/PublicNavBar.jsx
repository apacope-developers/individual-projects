import { Link } from 'react-router-dom';
import { useLanguage } from '../context/LanguageContext';
import { useTheme } from '../context/ThemeContext';
import { Stethoscope, Sun, Moon, Globe } from 'lucide-react';

const PublicNavBar = () => {
  const { language, setLanguage, t } = useLanguage();
  const { theme, toggleTheme } = useTheme();

  return (
    <div className="fixed top-0 left-0 right-0 z-50 bg-white/90 dark:bg-gray-900/90 backdrop-blur border-b border-gray-200 dark:border-gray-700">
      <div className="max-w-7xl mx-auto px-4 h-14 flex items-center justify-between">
        <Link to="/" className="flex items-center gap-2">
          <Stethoscope className="w-7 h-7 text-primary-600" />
          <span className="font-bold text-gray-900 dark:text-white">{t('appName')}</span>
        </Link>
        <div className="flex items-center gap-2">
          <div className="flex items-center gap-1 bg-gray-100 dark:bg-gray-700 rounded-lg p-1">
            <Globe className="w-4 h-4 text-gray-500 ml-1" />
            {['en', 'fr', 'rw'].map((lang) => (
              <button
                key={lang}
                onClick={() => setLanguage(lang)}
                className={`px-2 py-1 text-xs font-medium rounded ${
                  language === lang ? 'bg-primary-600 text-white' : 'text-gray-600 dark:text-gray-300'
                }`}
              >
                {lang.toUpperCase()}
              </button>
            ))}
          </div>
          <button
            onClick={toggleTheme}
            className="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300"
          >
            {theme === 'dark' ? <Sun className="w-5 h-5" /> : <Moon className="w-5 h-5" />}
          </button>
          <Link to="/register" className="hidden sm:inline text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-primary-600 px-2">
            {t('register')}
          </Link>
          <Link to="/login" className="text-sm font-medium text-primary-600 dark:text-primary-400 hover:text-primary-500 px-2">
            {t('login')}
          </Link>
        </div>
      </div>
    </div>
  );
};

export default PublicNavBar;
