import { Link } from 'react-router-dom';

const Privacy = () => {
  return (
    <div className="min-h-screen bg-slate-50 py-16 px-4">
      <div className="mx-auto max-w-4xl rounded-3xl bg-white p-10 shadow-xl">
        <div className="flex items-center justify-between gap-4">
          <div>
            <h1 className="text-3xl font-bold text-slate-900">Privacy Policy</h1>
            <p className="mt-2 text-slate-600">Your privacy is our priority.</p>
          </div>
          <Link to="/" className="text-primary-600 hover:text-primary-700">Back home</Link>
        </div>
        <div className="mt-10 space-y-6 text-slate-700">
          <p>We collect only the information needed to provide healthcare services and manage your appointments.</p>
          <p>Your personal and medical data is protected with industry-standard security measures and stored securely.</p>
          <p>Doctor and admin credentials are managed through authorized channels and are not publicly available through self-signup.</p>
        </div>
      </div>
    </div>
  );
};

export default Privacy;
