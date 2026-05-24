import { Link } from 'react-router-dom';

const ForgotPassword = () => {
  return (
    <div className="min-h-screen bg-gradient-to-br from-slate-50 to-white flex items-center justify-center px-4 py-12">
      <div className="max-w-xl w-full rounded-3xl border border-slate-200 bg-white p-10 shadow-xl">
        <h1 className="text-3xl font-bold text-slate-900">Forgot Password</h1>
        <p className="mt-4 text-slate-600">Enter your email address and we will send instructions to reset your password.</p>
        <form className="mt-8 space-y-6">
          <div>
            <label className="block text-sm font-medium text-slate-700">Email address</label>
            <input
              type="email"
              className="input-field mt-2"
              placeholder="you@example.com"
              required
            />
          </div>
          <button type="submit" className="btn-primary w-full">
            Send reset link
          </button>
        </form>
        <p className="mt-6 text-center text-sm text-slate-500">
          Remembered your password?{' '}
          <Link to="/login" className="text-primary-600 hover:text-primary-700">
            Sign in
          </Link>
        </p>
      </div>
    </div>
  );
};

export default ForgotPassword;
