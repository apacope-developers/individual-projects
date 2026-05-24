import { useEffect } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import PublicNavBar from '../components/PublicNavBar';
import LandingImage from '../components/LandingImage';

// Local images in /public/images — always load, no external CDN required
const IMAGES = {
  hero: '/images/hero.jpg',
  doctorPatient: '/images/doctor-patient.jpg',
  telemedicine: '/images/telemedicine.jpg',
  hospital: '/images/hospital.svg',
  careTeam: '/images/care-team.jpg',
};

const LandingPage = () => {
  const { user } = useAuth();
  const navigate = useNavigate();

  useEffect(() => {
    if (user) {
      if (user.role === 'admin') navigate('/admin/dashboard');
      else if (user.role === 'doctor') navigate('/doctor/dashboard');
      else navigate('/dashboard');
    }
  }, [user, navigate]);

  return (
    <div className="min-h-screen bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">
      <PublicNavBar />

      <main className="max-w-6xl mx-auto px-6 py-16 pt-28">
        <section className="grid gap-12 lg:grid-cols-2 items-center">
          <div className="space-y-6">
            <h1 className="text-5xl font-bold tracking-tight text-gray-900 dark:text-white">
              Healthcare at Your Fingertips
            </h1>
            <p className="text-xl text-gray-600 dark:text-gray-300">
              Access quality healthcare services anytime, anywhere. Book appointments, consult with doctors, and manage your medical records all in one place.
            </p>

            <div className="flex flex-col gap-3 sm:flex-row">
              <Link to="/register" className="px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors inline-flex items-center justify-center">
                Get Started
              </Link>
              <Link
                to="/login"
                className="px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-lg font-medium hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors inline-flex items-center justify-center"
              >
                Sign In
              </Link>
            </div>

            <div className="grid gap-4 sm:grid-cols-2 pt-4">
              <div className="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                <p className="text-sm font-semibold text-blue-600">Easy Booking</p>
                <p className="mt-2 text-gray-600 dark:text-gray-300">Schedule appointments with verified doctors in minutes</p>
              </div>
              <div className="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                <p className="text-sm font-semibold text-blue-600">Secure Records</p>
                <p className="mt-2 text-gray-600 dark:text-gray-300">Keep all your medical documents safe and organized</p>
              </div>
            </div>
          </div>

          <div className="relative">
            <div className="overflow-hidden rounded-lg shadow-lg border border-gray-200 dark:border-gray-700">
              <LandingImage
                src={IMAGES.hero}
                alt="Healthcare professional with patient"
                className="w-full h-96 object-cover"
              />
            </div>
          </div>
        </section>

        <section className="mt-20">
          <h2 className="text-3xl font-bold text-gray-900 dark:text-white mb-4">Core Features</h2>
          <div className="grid gap-6 md:grid-cols-3">
            {[
              { title: 'Book Appointments', description: 'Schedule visits with doctors at your convenience' },
              { title: 'Medical Records', description: 'Store and access all your health documents securely' },
              { title: 'Doctor Consultation', description: 'Get medical guidance from qualified healthcare providers' },
            ].map((item) => (
              <div
                key={item.title}
                className="border border-gray-200 dark:border-gray-700 rounded-lg p-6 hover:shadow-md transition"
              >
                <h3 className="text-lg font-semibold text-gray-900 dark:text-white">{item.title}</h3>
                <p className="mt-2 text-gray-600 dark:text-gray-300">{item.description}</p>
              </div>
            ))}
          </div>
        </section>

        <section className="mt-16 bg-gray-50 dark:bg-gray-800 rounded-lg p-8">
          <h2 className="text-2xl font-bold text-gray-900 dark:text-white mb-4">Why Choose Us?</h2>
          <ul className="space-y-3 text-gray-600 dark:text-gray-300">
            <li className="flex items-start">
              <span className="text-blue-600 mr-3">✓</span>
              <span>Experienced and certified healthcare professionals</span>
            </li>
            <li className="flex items-start">
              <span className="text-blue-600 mr-3">✓</span>
              <span>24/7 access to your medical information</span>
            </li>
            <li className="flex items-start">
              <span className="text-blue-600 mr-3">✓</span>
              <span>Confidential and secure patient data</span>
            </li>
            <li className="flex items-start">
              <span className="text-blue-600 mr-3">✓</span>
              <span>Affordable healthcare services</span>
            </li>
          </ul>
        </section>

        <section className="mt-16 text-center">
          <h2 className="text-3xl font-bold text-gray-900 dark:text-white mb-4">Ready to Get Started?</h2>
          <p className="text-gray-600 dark:text-gray-300 mb-6">Join thousands of users who trust us with their healthcare needs</p>
          <Link to="/register" className="px-8 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors inline-flex items-center">
            Create Your Account
          </Link>
        </section>

        <p className="mt-16 text-center text-sm text-gray-500 dark:text-gray-500">&copy; 2024 Healthcare System. All rights reserved.</p>
      </main>
    </div>
  );
};

export default LandingPage;
