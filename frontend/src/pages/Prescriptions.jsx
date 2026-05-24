import { useState, useEffect } from 'react';
import { useLanguage } from '../context/LanguageContext';
import { prescriptionService } from '../services/prescriptionService';
import { getAssetUrl } from '../services/api';
import { toast } from 'react-hot-toast';
import { Pill, Download, Calendar, FileText, Stethoscope } from 'lucide-react';

const Prescriptions = () => {
  const { t } = useLanguage();
  const [prescriptions, setPrescriptions] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    loadPrescriptions();
  }, []);

  const loadPrescriptions = async () => {
    try {
      setLoading(true);
      const response = await prescriptionService.getPatientPrescriptions();
      setPrescriptions(response.data);
    } catch (error) {
      toast.error('Failed to load prescriptions');
    } finally {
      setLoading(false);
    }
  };

  const handleDownload = async (prescription) => {
    if (prescription.pdf_url) {
      window.open(getAssetUrl(prescription.pdf_url), '_blank');
    } else {
      toast.error(t('pdfNotAvailable'));
    }
  };

  if (loading) {
    return (
      <div className="flex items-center justify-center h-64">
        <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600"></div>
      </div>
    );
  }

  return (
    <div className="space-y-6">
      {/* Header */}
      <div>
        <h1 className="text-3xl font-bold text-gray-900">{t('prescriptions')}</h1>
        <p className="text-gray-600 mt-2">{t('viewAndDownloadPrescriptions')}</p>
      </div>

      {/* Prescriptions List */}
      {prescriptions.length > 0 ? (
        <div className="space-y-4">
          {prescriptions.map((prescription) => (
            <div key={prescription.id} className="card">
              <div className="flex items-start justify-between">
                <div className="flex-1">
                  <div className="flex items-center space-x-4 mb-3">
                    <div className="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center">
                      <Pill className="w-6 h-6 text-primary-600" />
                    </div>
                    <div>
                      <h3 className="font-semibold text-gray-900">
                        Prescription #{prescription.id}
                      </h3>
                      <p className="text-sm text-gray-600">
                        Dr. {prescription.doctor?.user?.name}
                      </p>
                    </div>
                  </div>

                  <div className="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div className="flex items-center text-sm text-gray-600">
                      <Calendar className="w-4 h-4 mr-2" />
                      {new Date(prescription.issued_at).toLocaleDateString()}
                    </div>
                    <div className="flex items-center text-sm text-gray-600">
                      <Stethoscope className="w-4 h-4 mr-2" />
                      {prescription.doctor?.specialty}
                    </div>
                  </div>

                  {/* Medications */}
                  <div className="bg-gray-50 rounded-lg p-4 mb-4">
                    <h4 className="font-medium text-gray-900 mb-2">Medications</h4>
                    <div className="space-y-2">
                      {prescription.medications?.map((med, index) => (
                        <div key={index} className="text-sm">
                          <p className="font-medium text-gray-900">{med.name}</p>
                          <p className="text-gray-600">
                            {med.dosage} - {med.duration}
                          </p>
                          {med.instructions && (
                            <p className="text-gray-500 text-xs mt-1">{med.instructions}</p>
                          )}
                        </div>
                      ))}
                    </div>
                  </div>

                  {prescription.instructions && (
                    <div className="mb-4">
                      <h4 className="font-medium text-gray-900 mb-2">Instructions</h4>
                      <p className="text-sm text-gray-600">{prescription.instructions}</p>
                    </div>
                  )}
                </div>

                <button
                  onClick={() => handleDownload(prescription)}
                  className="btn-outline flex items-center ml-4"
                >
                  <Download className="w-4 h-4 mr-2" />
                  {t('downloadPdf')}
                </button>
              </div>
            </div>
          ))}
        </div>
      ) : (
        <div className="card text-center py-12">
          <Pill className="w-16 h-16 text-gray-300 mx-auto mb-4" />
          <h3 className="text-lg font-semibold text-gray-900 mb-2">{t('noPrescriptionsYet')}</h3>
          <p className="text-gray-600">{t('prescriptionsEmptyMessage')}</p>
        </div>
      )}
    </div>
  );
};

export default Prescriptions;
