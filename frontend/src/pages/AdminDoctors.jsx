import { useState, useEffect } from 'react';
import { adminService } from '../services/adminService';
import { useLanguage } from '../context/LanguageContext';
import { ProfileAvatar } from '../utils/photoUrl';
import { toast } from 'react-hot-toast';
import { Plus, CheckCircle, XCircle } from 'lucide-react';

const AdminDoctors = () => {
  const { t } = useLanguage();
  const [doctors, setDoctors] = useState([]);
  const [loading, setLoading] = useState(true);
  const [showForm, setShowForm] = useState(false);
  const [form, setForm] = useState({
    name: '',
    email: '',
    password: '',
    phone: '',
    specialty: 'General Practice',
    qualification: 'MBBS',
    experience: 0,
    consultation_fee: 50,
    bio: '',
  });

  const loadDoctors = async () => {
    try {
      const res = await adminService.getAllDoctors();
      setDoctors(res.data || []);
    } catch {
      toast.error(t('failedToLoad'));
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    loadDoctors();
  }, []);

  const handleCreate = async (e) => {
    e.preventDefault();
    try {
      await adminService.createDoctor(form);
      toast.success('Doctor created successfully');
      setShowForm(false);
      setForm({ name: '', email: '', password: '', phone: '', specialty: 'General Practice', qualification: 'MBBS', experience: 0, consultation_fee: 50, bio: '' });
      loadDoctors();
    } catch (err) {
      toast.error(err.response?.data?.message || 'Failed to create doctor');
    }
  };

  const handleApprove = async (id) => {
    try {
      await adminService.approveDoctor(id);
      toast.success('Doctor approved');
      loadDoctors();
    } catch {
      toast.error('Failed to approve doctor');
    }
  };

  if (loading) {
    return <div className="flex justify-center h-64"><div className="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600" /></div>;
  }

  return (
    <div className="space-y-6">
      <div className="flex justify-between items-center">
        <div>
          <h1 className="text-3xl font-bold text-gray-900 dark:text-white">{t('doctors')}</h1>
          <p className="text-gray-600 dark:text-gray-400 mt-1">Only administrators can add doctor accounts</p>
        </div>
        <button onClick={() => setShowForm(!showForm)} className="btn-primary flex items-center">
          <Plus className="w-4 h-4 mr-2" />
          {t('addDoctor')}
        </button>
      </div>

      {showForm && (
        <form onSubmit={handleCreate} className="card space-y-4">
          <h2 className="text-lg font-semibold">{t('createDoctor')}</h2>
          <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
            <input className="input-field" placeholder="Full Name" value={form.name} onChange={(e) => setForm({ ...form, name: e.target.value })} required />
            <input className="input-field" type="email" placeholder="Email" value={form.email} onChange={(e) => setForm({ ...form, email: e.target.value })} required />
            <input className="input-field" type="password" placeholder="Password" value={form.password} onChange={(e) => setForm({ ...form, password: e.target.value })} required minLength={6} />
            <input className="input-field" placeholder="Phone" value={form.phone} onChange={(e) => setForm({ ...form, phone: e.target.value })} />
            <input className="input-field" placeholder="Specialty" value={form.specialty} onChange={(e) => setForm({ ...form, specialty: e.target.value })} required />
            <input className="input-field" placeholder="Qualification" value={form.qualification} onChange={(e) => setForm({ ...form, qualification: e.target.value })} required />
            <input className="input-field" type="number" placeholder="Experience (years)" value={form.experience} onChange={(e) => setForm({ ...form, experience: parseInt(e.target.value, 10) })} />
            <input className="input-field" type="number" step="0.01" placeholder="Consultation Fee" value={form.consultation_fee} onChange={(e) => setForm({ ...form, consultation_fee: parseFloat(e.target.value) })} />
            <textarea className="input-field md:col-span-2" placeholder="Bio" rows={2} value={form.bio} onChange={(e) => setForm({ ...form, bio: e.target.value })} />
          </div>
          <div className="flex gap-2">
            <button type="submit" className="btn-primary">{t('save')}</button>
            <button type="button" onClick={() => setShowForm(false)} className="btn-outline">{t('cancel')}</button>
          </div>
        </form>
      )}

      <div className="space-y-3">
        {doctors.map((doctor) => (
          <div key={doctor.id} className="card flex items-center justify-between">
            <div className="flex items-center gap-4">
              <ProfileAvatar photo={doctor.user?.profile_photo} name={doctor.user?.name} size="md" />
              <div>
                <p className="font-semibold text-gray-900 dark:text-white">Dr. {doctor.user?.name}</p>
                <p className="text-sm text-gray-600 dark:text-gray-400">{doctor.specialty} · {doctor.user?.email}</p>
                <p className="text-sm text-gray-500">${doctor.consultation_fee} · {doctor.experience} yrs</p>
              </div>
            </div>
            <div className="flex items-center gap-2">
              {doctor.is_approved ? (
                <span className="badge badge-success">Approved</span>
              ) : (
                <button onClick={() => handleApprove(doctor.id)} className="btn-primary text-sm flex items-center">
                  <CheckCircle className="w-4 h-4 mr-1" />
                  {t('approve')}
                </button>
              )}
            </div>
          </div>
        ))}
        {doctors.length === 0 && <p className="text-center text-gray-500 py-8">No doctors yet. Add one above.</p>}
      </div>
    </div>
  );
};

export default AdminDoctors;
