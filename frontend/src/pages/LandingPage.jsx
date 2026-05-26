import { useEffect } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import { useLanguage } from '../context/LanguageContext';
import PublicNavBar from '../components/PublicNavBar';
import LandingImage from '../components/LandingImage';
import { CheckCircle, Clock, Users, Shield, Video, FileText } from 'lucide-react';

const IMAGES = {
  hero: '/images/doctor-patient.jpg',
  telemedicine: '/images/telemedicine.jpg',
  careTeam: '/images/care-team.jpg',
  hospital: '/images/hospital.svg',
};

const LandingPage = () => {
  const { user } = useAuth();
  const { t } = useLanguage();
  const navigate = useNavigate();

  useEffect(() => {
    if (user) {
      if (user.role === 'admin') navigate('/admin/dashboard');
      else if (user.role === 'doctor') navigate('/doctor/dashboard');
      else navigate('/dashboard');
    }
  }, [user, navigate]);

  const videoCard = {
    title: t('videoFeature'),
    description: t('videoFeatureDesc'),
    src: 'https://interactive-examples.mdn.mozilla.net/media/cc0-videos/flower.mp4'
  };

  return (
    <div className="min-h-screen bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">
      <PublicNavBar />

      <main className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 pt-28">
        {/* Hero Section */}
        <section className="grid gap-12 lg:grid-cols-2 items-center mb-24">
          <div className="space-y-6">
            <span className="inline-flex items-center px-4 py-2 rounded-full bg-gradient-to-r from-blue-100 to-blue-50 dark:from-blue-900/40 dark:to-blue-800/30 text-blue-700 dark:text-blue-300 text-sm font-semibold border border-blue-200 dark:border-blue-800">
              ✨ {t('heroBadge')}
            </span>
            <h1 className="text-5xl md:text-6xl font-bold tracking-tight bg-gradient-to-r from-blue-600 to-blue-800 dark:from-blue-400 dark:to-blue-500 bg-clip-text text-transparent">
              {t('heroTitle')}
            </h1>
            <p className="text-xl text-gray-600 dark:text-gray-300 leading-relaxed">
              {t('heroSubtitle')}
            </p>

            <div className="flex flex-col gap-3 sm:flex-row pt-4">
              <Link
                to="/register"
                className="px-8 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg font-semibold hover:shadow-lg hover:shadow-blue-500/50 transition-all duration-300 inline-flex items-center justify-center transform hover:scale-105"
              >
                {t('getStarted')} →
              </Link>
              <Link
                to="/login"
                className="px-8 py-3 border-2 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-lg font-semibold hover:bg-gray-50 dark:hover:bg-gray-800 transition-all duration-300 inline-flex items-center justify-center"
              >
                {t('signIn')}
              </Link>
            </div>

            <div className="grid gap-4 sm:grid-cols-2 pt-6">
              <div className="border border-gray-200 dark:border-gray-700 rounded-lg p-4 bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 hover:shadow-md transition-shadow">
                <p className="text-sm font-bold text-blue-600 dark:text-blue-400 flex items-center"><Clock className="w-4 h-4 mr-2" />{t('apptFeature')}</p>
                <p className="mt-2 text-gray-600 dark:text-gray-300">{t('apptFeatureDesc')}</p>
              </div>
              <div className="border border-gray-200 dark:border-gray-700 rounded-lg p-4 bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 hover:shadow-md transition-shadow">
                <p className="text-sm font-bold text-blue-600 dark:text-blue-400 flex items-center"><FileText className="w-4 h-4 mr-2" />{t('recordsFeature')}</p>
                <p className="mt-2 text-gray-600 dark:text-gray-300">{t('recordsFeatureDesc')}</p>
              </div>
            </div>
          </div>

          <div className="relative group">
            <div className="absolute inset-0 bg-gradient-to-r from-blue-600 to-blue-400 rounded-2xl blur-2xl opacity-30 group-hover:opacity-50 transition-opacity duration-300"></div>
            <div className="relative overflow-hidden rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700">
              <LandingImage
                src={IMAGES.hero}
                alt={t('heroTitle')}
                className="w-full h-96 object-cover transform group-hover:scale-105 transition-transform duration-500"
              />
            </div>
          </div>
        </section>

        {/* Core Features */}
        <section className="mb-24">
          <div className="text-center mb-12">
            <h2 className="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">{t('coreFeatures')}</h2>
            <p className="text-xl text-gray-600 dark:text-gray-300">Everything you need for modern healthcare</p>
          </div>
          <div className="grid gap-8 md:grid-cols-3">
            {[
              { 
                title: t('apptFeature'), 
                description: t('apptFeatureDesc'),
                icon: Clock,
                color: 'from-blue-500 to-blue-600'
              },
              { 
                title: t('videoFeature'), 
                description: t('videoFeatureDesc'),
                icon: Video,
                color: 'from-green-500 to-green-600'
              },
              { 
                title: t('recordsFeature'), 
                description: t('recordsFeatureDesc'),
                icon: FileText,
                color: 'from-purple-500 to-purple-600'
              },
            ].map((item) => {
              const IconComponent = item.icon;
              return (
                <div
                  key={item.title}
                  className="group border border-gray-200 dark:border-gray-700 rounded-xl p-8 bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2"
                >
                  <div className={`w-14 h-14 rounded-lg bg-gradient-to-r ${item.color} p-3 text-white mb-4 group-hover:scale-110 transition-transform`}>
                    <IconComponent className="w-full h-full" />
                  </div>
                  <h3 className="text-xl font-bold text-gray-900 dark:text-white mb-3">{item.title}</h3>
                  <p className="text-gray-600 dark:text-gray-300 leading-relaxed">{item.description}</p>
                </div>
              );
            })}
          </div>
        </section>

        {/* Why Choose Us */}
        <section className="mb-24 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-2xl p-12 border border-blue-200 dark:border-blue-800/50">
          <h2 className="text-4xl font-bold text-gray-900 dark:text-white mb-8">{t('whyChooseUs')}</h2>
          <div className="grid md:grid-cols-2 gap-6">
            {[
              { 
                title: t('patientExp'),
                description: t('patientExpDesc'),
                icon: Users 
              },
              { 
                title: t('doctorCoord'),
                description: t('doctorCoordDesc'),
                icon: Shield 
              },
              { 
                title: t('connectedCare'),
                description: t('connectedCareDesc'),
                icon: CheckCircle 
              },
              { 
                title: t('smartTech'),
                description: t('aiGuidanceDesc'),
                icon: Clock 
              },
            ].map((item, idx) => {
              const IconComponent = item.icon;
              return (
                <div key={idx} className="flex gap-4 p-4 rounded-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:shadow-md transition">
                  <IconComponent className="w-6 h-6 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-1" />
                  <div>
                    <p className="font-bold text-gray-900 dark:text-white mb-2">{item.title}</p>
                    <p className="text-gray-600 dark:text-gray-300 text-sm">{item.description}</p>
                  </div>
                </div>
              );
            })}
          </div>
        </section>

        {/* Video Consultation Section */}
        <section className="mb-24">
          <h2 className="text-4xl font-bold text-gray-900 dark:text-white mb-10 text-center">{t('videoFeature')}</h2>
          <div className="grid gap-8 lg:grid-cols-2 items-stretch">
            {/* Video Player */}
            <div className="group rounded-2xl overflow-hidden shadow-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
              <video
                src={videoCard.src}
                controls
                loop
                muted
                playsInline
                className="w-full h-80 object-cover group-hover:brightness-110 transition-all duration-300"
              />
              <div className="p-6 bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900">
                <h3 className="text-xl font-bold text-gray-900 dark:text-white flex items-center"><Video className="w-5 h-5 mr-2 text-green-500" />{videoCard.title}</h3>
                <p className="mt-3 text-gray-600 dark:text-gray-300 leading-relaxed">{videoCard.description}</p>
              </div>
            </div>

            {/* Care Team Image */}
            <div className="group rounded-2xl overflow-hidden shadow-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
              <LandingImage
                src={IMAGES.careTeam}
                alt={t('trustedPatients')}
                className="w-full h-80 object-cover group-hover:scale-110 transition-transform duration-500"
              />
              <div className="p-6 bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900">
                <h3 className="text-xl font-bold text-gray-900 dark:text-white flex items-center"><Users className="w-5 h-5 mr-2 text-blue-500" />{t('trustedPatients')}</h3>
                <p className="mt-3 text-gray-600 dark:text-gray-300 leading-relaxed">{t('joinTrustedUsers')}</p>
              </div>
            </div>
          </div>
        </section>

        {/* Final CTA Section */}
        <section className="mb-16 text-center bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-600 dark:from-blue-700 dark:via-blue-800 dark:to-indigo-700 rounded-2xl p-16 shadow-2xl overflow-hidden relative">
          <div className="absolute inset-0 opacity-10">
            <div className="absolute top-0 left-0 w-96 h-96 bg-white rounded-full filter blur-3xl"></div>
            <div className="absolute bottom-0 right-0 w-96 h-96 bg-white rounded-full filter blur-3xl"></div>
          </div>
          <div className="relative z-10">
            <h2 className="text-4xl font-bold text-white mb-4">{t('readyToGetStarted')}</h2>
            <p className="text-xl text-blue-50 mb-8 max-w-2xl mx-auto">{t('joinTrustedUsers')}</p>
            <Link
              to="/register"
              className="inline-flex items-center px-8 py-4 bg-white text-blue-600 font-bold rounded-lg hover:shadow-2xl transition-all duration-300 transform hover:scale-105"
            >
              {t('createAccountBtn')} →
            </Link>
          </div>
        </section>

        <p className="text-center text-sm text-gray-500 dark:text-gray-400">{t('copyright')}</p>
      </main>
    </div>
  );
};

export default LandingPage;
