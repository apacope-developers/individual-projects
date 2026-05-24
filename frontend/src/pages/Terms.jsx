import { Link } from 'react-router-dom';

const Terms = () => {
  return (
    <div className="min-h-screen bg-slate-50 py-16 px-4">
      <div className="mx-auto max-w-4xl rounded-3xl bg-white p-10 shadow-xl">
        <div className="flex items-center justify-between gap-4">
          <div>
            <h1 className="text-3xl font-bold text-slate-900">Terms of Service</h1>
            <p className="mt-2 text-slate-600">A quick overview of how you can use HealthCare.</p>
          </div>
          <Link to="/" className="text-primary-600 hover:text-primary-700">Back home</Link>
        </div>
        <div className="mt-10 space-y-6 text-slate-700">
          <p>By accessing or using the platform, you agree to follow these terms and any posted rules.</p>
          <p>Accounts are for personal use only. Doctor and admin accounts must be provided through authorized channels.</p>
          <p>All medical advice and content on the platform is for informational purposes. Consult a licensed provider for a medical diagnosis.</p>
        </div>
      </div>
    </div>
  );
};

export default Terms;
