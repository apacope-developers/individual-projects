import { useState, useEffect } from 'react';
import { useLanguage } from '../context/LanguageContext';
import { patientService } from '../services/patientService';
import { getAssetUrl } from '../services/api';
import { toast } from 'react-hot-toast';
import { FolderOpen, Upload, Download, Trash2, FileText, Image, Calendar, Eye, X } from 'lucide-react';

const MedicalRecords = () => {
  const { t } = useLanguage();
  const [records, setRecords] = useState([]);
  const [loading, setLoading] = useState(true);
  const [uploading, setUploading] = useState(false);
  const [showUploadModal, setShowUploadModal] = useState(false);
  const [showPreviewModal, setShowPreviewModal] = useState(false);
  const [previewRecord, setPreviewRecord] = useState(null);
  const [uploadData, setUploadData] = useState({
    title: '',
    description: '',
    record_type: 'other',
  });

  useEffect(() => {
    loadRecords();
  }, []);

  const loadRecords = async () => {
    try {
      setLoading(true);
      const response = await patientService.getMedicalRecords();
      setRecords(response.data);
    } catch (error) {
      toast.error(t('failedToLoadMedicalRecords'));
    } finally {
      setLoading(false);
    }
  };

  const handleFileUpload = async (e) => {
    e.preventDefault();
    const fileInput = e.target.querySelector('input[type="file"]');
    const file = fileInput?.files[0];

    if (!file) {
      toast.error(t('pleaseSelectFile'));
      return;
    }

    const formData = new FormData();
    formData.append('file', file);
    formData.append('title', uploadData.title);
    formData.append('description', uploadData.description);
    formData.append('record_type', uploadData.record_type);

    setUploading(true);
    try {
      await patientService.uploadMedicalRecord(formData);
      toast.success(t('medicalRecordUploaded'));
      setShowUploadModal(false);
      setUploadData({ title: '', description: '', record_type: 'other' });
      loadRecords();
    } catch (error) {
      toast.error(t('failedUploadMedicalRecord'));
    } finally {
      setUploading(false);
    }
  };

  const handleDelete = async (id) => {
    if (!window.confirm(t('deleteRecordConfirm'))) {
      return;
    }

    try {
      await patientService.deleteMedicalRecord(id);
      toast.success(t('medicalRecordDeleted'));
      loadRecords();
    } catch (error) {
      toast.error(t('failedDeleteMedicalRecord'));
    }
  };

  const handleDownload = (record) => {
    const url = getAssetUrl(record.file_url);
    window.open(url, '_blank');
  };

  const handlePreview = (record) => {
    setPreviewRecord(record);
    setShowPreviewModal(true);
  };

  const getFileIcon = (fileType) => {
    if (fileType?.includes('image')) {
      return <Image className="w-6 h-6" />;
    }
    return <FileText className="w-6 h-6" />;
  };

  const isImageFile = (fileType) => {
    return fileType?.includes('image');
  };

  const formatFileSize = (bytes) => {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
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
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-3xl font-bold text-gray-900 dark:text-white">{t('medicalRecords')}</h1>
          <p className="text-gray-600 dark:text-gray-400 mt-2">{t('accessAndManageHealthDocuments')}</p>
        </div>
        <button
          onClick={() => setShowUploadModal(true)}
          className="btn-primary flex items-center"
        >
          <Upload className="w-4 h-4 mr-2" />
          {t('uploadRecord')}
        </button>
      </div>

      {/* Records Grid */}
      {records.length > 0 ? (
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          {records.map((record) => (
            <div key={record.id} className="card hover:shadow-lg transition-shadow">
              <div className="flex items-start space-x-3 mb-4">
                <div className="w-12 h-12 bg-primary-100 dark:bg-primary-900/40 rounded-lg flex items-center justify-center flex-shrink-0">
                  {getFileIcon(record.file_type)}
                </div>
                <div className="flex-1 min-w-0">
                  <h3 className="font-semibold text-gray-900 dark:text-white truncate">{record.title}</h3>
                  <p className="text-sm text-gray-600 dark:text-gray-400 truncate">{record.description}</p>
                </div>
              </div>

              <div className="space-y-2 mb-4">
                <div className="flex items-center text-sm text-gray-600 dark:text-gray-400">
                  <Calendar className="w-4 h-4 mr-2" />
                  {new Date(record.uploaded_at).toLocaleDateString()}
                </div>
                <div className="flex items-center text-sm text-gray-600 dark:text-gray-400">
                  <FileText className="w-4 h-4 mr-2" />
                  {formatFileSize(record.file_size)}
                </div>
              </div>

              <div className="flex items-center space-x-2">
                {isImageFile(record.file_type) && (
                  <button
                    onClick={() => handlePreview(record)}
                    className="flex-1 btn-outline text-sm flex items-center justify-center hover:bg-primary-50 dark:hover:bg-primary-900/30"
                  >
                    <Eye className="w-4 h-4 mr-1" />
                    View
                  </button>
                )}
                <button
                  onClick={() => handleDownload(record)}
                  className={`${isImageFile(record.file_type) ? 'flex-1' : 'flex-1'} btn-outline text-sm flex items-center justify-center hover:bg-primary-50 dark:hover:bg-primary-900/30`}
                >
                  <Download className="w-4 h-4 mr-1" />
                  {t('download')}
                </button>
                <button
                  onClick={() => handleDelete(record.id)}
                  className="p-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-colors"
                >
                  <Trash2 className="w-4 h-4" />
                </button>
              </div>
            </div>
          ))}
        </div>
      ) : (
        <div className="card text-center py-12">
          <FolderOpen className="w-16 h-16 text-gray-300 dark:text-gray-600 mx-auto mb-4" />
          <h3 className="text-lg font-semibold text-gray-900 dark:text-white mb-2">{t('noMedicalRecords')}</h3>
          <p className="text-gray-600 dark:text-gray-400 mb-4">{t('uploadYourMedicalDocuments')}</p>
          <button
            onClick={() => setShowUploadModal(true)}
            className="btn-primary inline-flex items-center"
          >
            <Upload className="w-4 h-4 mr-2" />
            {t('uploadFirstRecord')}
          </button>
        </div>
      )}

      {/* Upload Modal */}
      {showUploadModal && (
        <div className="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
          <div className="bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-md w-full mx-4">
            <div className="p-6">
              <div className="flex items-center justify-between mb-4">
                <h2 className="text-xl font-bold text-gray-900 dark:text-white">{t('uploadMedicalRecord')}</h2>
                <button
                  onClick={() => setShowUploadModal(false)}
                  className="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300"
                >
                  <X className="w-6 h-6" />
                </button>
              </div>
              
              <form onSubmit={handleFileUpload} className="space-y-4">
                <div>
                  <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {t('title')}
                  </label>
                  <input
                    type="text"
                    className="input-field"
                    value={uploadData.title}
                    onChange={(e) => setUploadData({ ...uploadData, title: e.target.value })}
                    required
                  />
                </div>

                <div>
                  <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {t('description')}
                  </label>
                  <textarea
                    className="input-field"
                    rows="2"
                    value={uploadData.description}
                    onChange={(e) => setUploadData({ ...uploadData, description: e.target.value })}
                  />
                </div>

                <div>
                  <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {t('recordType')}
                  </label>
                  <select
                    className="input-field"
                    value={uploadData.record_type}
                    onChange={(e) => setUploadData({ ...uploadData, record_type: e.target.value })}
                  >
                    <option value="lab_report">Lab Report</option>
                    <option value="prescription">Prescription</option>
                    <option value="imaging">Imaging</option>
                    <option value="discharge_summary">Discharge Summary</option>
                    <option value="other">Other</option>
                  </select>
                </div>

                <div>
                  <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {t('fileLabel')}
                  </label>
                  <input
                    type="file"
                    accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                    className="input-field"
                    required
                  />
                  <p className="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    {t('acceptedFormats')}
                  </p>
                </div>

                <div className="flex space-x-3 pt-4">
                  <button
                    type="button"
                    onClick={() => setShowUploadModal(false)}
                    className="flex-1 btn-outline"
                  >
                    {t('cancel')}
                  </button>
                  <button
                    type="submit"
                    disabled={uploading}
                    className="flex-1 btn-primary disabled:opacity-50 disabled:cursor-not-allowed"
                  >
                    {uploading ? t('uploading') : t('upload')}
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      )}

      {/* Preview Modal */}
      {showPreviewModal && previewRecord && (
        <div className="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
          <div className="bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div className="p-6">
              <div className="flex items-center justify-between mb-4">
                <h2 className="text-xl font-bold text-gray-900 dark:text-white">{previewRecord.title}</h2>
                <button
                  onClick={() => {
                    setShowPreviewModal(false);
                    setPreviewRecord(null);
                  }}
                  className="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300"
                >
                  <X className="w-6 h-6" />
                </button>
              </div>

              {isImageFile(previewRecord.file_type) ? (
                <div className="mb-4">
                  <img
                    src={getAssetUrl(previewRecord.file_url)}
                    alt={previewRecord.title}
                    className="w-full h-auto rounded-lg"
                  />
                </div>
              ) : (
                <div className="bg-gray-100 dark:bg-gray-700 rounded-lg p-8 text-center mb-4">
                  <FileText className="w-16 h-16 text-gray-400 dark:text-gray-500 mx-auto mb-2" />
                  <p className="text-gray-600 dark:text-gray-400">{t('previewNotAvailable')}</p>
                  <p className="text-sm text-gray-500 dark:text-gray-500 mt-2">{previewRecord.file_type}</p>
                </div>
              )}

              <div className="space-y-2 mb-4">
                <div>
                  <p className="text-sm text-gray-600 dark:text-gray-400">{t('description')}</p>
                  <p className="text-gray-900 dark:text-white">{previewRecord.description || t('noDescription')}</p>
                </div>
                <div>
                  <p className="text-sm text-gray-600 dark:text-gray-400">{t('uploaded')}</p>
                  <p className="text-gray-900 dark:text-white">{new Date(previewRecord.uploaded_at).toLocaleDateString()}</p>
                </div>
                <div>
                  <p className="text-sm text-gray-600 dark:text-gray-400">{t('fileSize')}</p>
                  <p className="text-gray-900 dark:text-white">{formatFileSize(previewRecord.file_size)}</p>
                </div>
              </div>

              <div className="flex space-x-3">
                <button
                  onClick={() => handleDownload(previewRecord)}
                  className="flex-1 btn-primary flex items-center justify-center"
                >
                  <Download className="w-4 h-4 mr-2" />
                  {t('download')}
                </button>
                <button
                  onClick={() => {
                    setShowPreviewModal(false);
                    setPreviewRecord(null);
                  }}
                  className="flex-1 btn-outline"
                >
                  {t('close')}
                </button>
              </div>
            </div>
          </div>
        </div>
      )}
    </div>
  );
};

export default MedicalRecords;
