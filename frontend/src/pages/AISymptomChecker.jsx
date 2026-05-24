import { useState, useRef, useEffect } from 'react';
import { toast } from 'react-hot-toast';
import { useLanguage } from '../context/LanguageContext';
import { aiService } from '../services/aiService';
import { Brain, Send, AlertTriangle, CheckCircle, Clock, Stethoscope, Mic, Square } from 'lucide-react';

const AISymptomChecker = () => {
  const { t } = useLanguage();
  const [symptoms, setSymptoms] = useState('');
  const [loading, setLoading] = useState(false);
  const [result, setResult] = useState(null);
  const [history, setHistory] = useState([]);
  const [isRecording, setIsRecording] = useState(false);
  const [recordingTime, setRecordingTime] = useState(0);
  const mediaRecorderRef = useRef(null);
  const recognitionRef = useRef(null);
  const timerIntervalRef = useRef(null);

  const commonSymptoms = [
    'Headache',
    'Fever',
    'Cough',
    'Fatigue',
    'Nausea',
    'Dizziness',
    'Chest pain',
    'Shortness of breath',
    'Stomach pain',
    'Joint pain',
    'Skin rash',
    'Sore throat',
  ];

  // Initialize Web Speech API
  useEffect(() => {
    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
    if (SpeechRecognition) {
      recognitionRef.current = new SpeechRecognition();
      recognitionRef.current.continuous = true;
      recognitionRef.current.interimResults = true;

      recognitionRef.current.onstart = () => {
        setIsRecording(true);
        setRecordingTime(0);
        timerIntervalRef.current = setInterval(() => {
          setRecordingTime(prev => prev + 1);
        }, 1000);
      };

      recognitionRef.current.onresult = (event) => {
        let interimTranscript = '';
        let finalTranscript = '';

        for (let i = event.resultIndex; i < event.results.length; i++) {
          const transcript = event.results[i][0].transcript;
          if (event.results[i].isFinal) {
            finalTranscript += transcript + ' ';
          } else {
            interimTranscript += transcript;
          }
        }

        if (finalTranscript) {
          setSymptoms(prev => prev + ' ' + finalTranscript);
        }
      };

      recognitionRef.current.onend = () => {
        setIsRecording(false);
        if (timerIntervalRef.current) {
          clearInterval(timerIntervalRef.current);
        }
      };

      recognitionRef.current.onerror = (event) => {
        toast.error(`${t('speechRecognitionError')}${event.error}`);
        setIsRecording(false);
        if (timerIntervalRef.current) {
          clearInterval(timerIntervalRef.current);
        }
      };
    }

    return () => {
      if (timerIntervalRef.current) {
        clearInterval(timerIntervalRef.current);
      }
    };
  }, []);

  const startVoiceRecording = () => {
    if (recognitionRef.current) {
      recognitionRef.current.start();
    }
  };

  const stopVoiceRecording = () => {
    if (recognitionRef.current) {
      recognitionRef.current.stop();
    }
    if (timerIntervalRef.current) {
      clearInterval(timerIntervalRef.current);
    }
  };

  const formatTime = (seconds) => {
    const mins = Math.floor(seconds / 60);
    const secs = seconds % 60;
    return `${mins}:${secs.toString().padStart(2, '0')}`;
  };

  const handleSymptomToggle = (symptom) => {
    const currentSymptoms = symptoms.split(',').map(s => s.trim()).filter(s => s);
    if (currentSymptoms.includes(symptom)) {
      setSymptoms(currentSymptoms.filter(s => s !== symptom).join(', '));
    } else {
      setSymptoms([...currentSymptoms, symptom].join(', '));
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    
    if (!symptoms.trim()) {
      toast.error(t('pleaseEnterSymptoms'));
      return;
    }

    setLoading(true);
    try {
      const symptomsArray = symptoms.split(',').map(s => s.trim()).filter(s => s);
      const response = await aiService.submitSymptoms(symptomsArray);
      setResult(response.data);
      setHistory([response.data, ...history]);
      toast.success(t('analysisComplete'));
    } catch (error) {
      toast.error(error.response?.data?.message || t('failedToAnalyzeSymptoms'));
    } finally {
      setLoading(false);
    }
  };

  const getUrgencyColor = (urgency) => {
    switch (urgency) {
      case 'emergency':
        return 'bg-red-100 dark:bg-red-900/40 text-red-800 dark:text-red-300 border-red-200 dark:border-red-800';
      case 'high':
        return 'bg-orange-100 dark:bg-orange-900/40 text-orange-800 dark:text-orange-300 border-orange-200 dark:border-orange-800';
      case 'medium':
        return 'bg-yellow-100 dark:bg-yellow-900/40 text-yellow-800 dark:text-yellow-300 border-yellow-200 dark:border-yellow-800';
      case 'low':
        return 'bg-green-100 dark:bg-green-900/40 text-green-800 dark:text-green-300 border-green-200 dark:border-green-800';
      default:
        return 'bg-gray-100 dark:bg-gray-900/40 text-gray-800 dark:text-gray-300 border-gray-200 dark:border-gray-800';
    }
  };

  const getUrgencyIcon = (urgency) => {
    switch (urgency) {
      case 'emergency':
        return <AlertTriangle className="w-5 h-5" />;
      case 'high':
        return <AlertTriangle className="w-5 h-5" />;
      case 'medium':
        return <Clock className="w-5 h-5" />;
      case 'low':
        return <CheckCircle className="w-5 h-5" />;
      default:
        return <Clock className="w-5 h-5" />;
    }
  };

  const renderConditionList = (conditions) => {
    if (!Array.isArray(conditions)) return null;

    return conditions.map((condition, index) => {
      if (typeof condition === 'string') {
        return (
          <li key={index} className="flex items-start text-gray-700 dark:text-gray-300">
            <span className="w-2 h-2 bg-primary-600 rounded-full mt-2 mr-2"></span>
            {condition}
          </li>
        );
      }

      return (
        <li key={index} className="border dark:border-gray-700 rounded-lg p-3 bg-gray-50 dark:bg-gray-700">
          <div className="flex items-center justify-between mb-2">
            <span className="font-semibold text-gray-900 dark:text-white">{condition.name}</span>
            {condition.probability && (
              <span className="text-sm text-gray-600 dark:text-gray-400">{condition.probability}</span>
            )}
          </div>
          <p className="text-sm text-gray-700 dark:text-gray-300">{condition.details}</p>
        </li>
      );
    });
  };

  return (
    <div className="max-w-4xl mx-auto space-y-6">
      {/* Header */}
      <div>
        <h1 className="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
          <Brain className="w-8 h-8 mr-3 text-primary-600" />
          {t('aiChecker')}
        </h1>
        <p className="text-gray-600 dark:text-gray-400 mt-2">
          {t('aiSymptomCheckerDescription')}
        </p>
      </div>

      {/* Disclaimer */}
      <div className="card bg-yellow-50 dark:bg-yellow-900/40 border-yellow-200 dark:border-yellow-800">
        <div className="flex items-start space-x-3">
          <AlertTriangle className="w-5 h-5 text-yellow-600 dark:text-yellow-400 flex-shrink-0 mt-0.5" />
          <div>
            <p className="text-sm text-yellow-800 dark:text-yellow-300 font-medium">{t('medicalDisclaimer')}</p>
            <p className="text-sm text-yellow-700 dark:text-yellow-200 mt-1">
              {t('medicalDisclaimerMessage')}
            </p>
          </div>
        </div>
      </div>

      {/* Input Form */}
      <div className="card">
        <form onSubmit={handleSubmit} className="space-y-6">
          {/* Common Symptoms */}
          <div>
            <label className="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-3">
              {t('commonSymptoms')} {t('clickToAdd')}
            </label>
            <div className="flex flex-wrap gap-2">
              {commonSymptoms.map((symptom) => {
                const isSelected = symptoms.includes(symptom);
                return (
                  <button
                    key={symptom}
                    type="button"
                    onClick={() => handleSymptomToggle(symptom)}
                    className={`px-3 py-1.5 rounded-full text-sm transition-all ${
                      isSelected
                        ? 'bg-primary-600 text-white'
                        : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'
                    }`}
                  >
                    {symptom}
                  </button>
                );
              })}
            </div>
          </div>

          {/* Custom Symptoms Input */}
          <div>
            <label className="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
              {t('yourSymptoms')}
            </label>
            <textarea
              className="input-field"
              rows="4"
              placeholder={t('symptomsPlaceholder')}
              value={symptoms}
              onChange={(e) => setSymptoms(e.target.value)}
            />
            <p className="text-xs text-gray-500 dark:text-gray-400 mt-1">
              {t('separateSymptomsNote')}
            </p>
          </div>

          {/* Voice Recording Button */}
          <div>
            <p className="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
              {t('voiceRecording')}
            </p>
            {isRecording ? (
              <button
                type="button"
                onClick={stopVoiceRecording}
                className="btn-outline text-red-600 dark:text-red-400 border-red-600 dark:border-red-400 flex items-center justify-center w-full animate-pulse"
              >
                <Square className="w-5 h-5 mr-2" />
                Stop Recording - {formatTime(recordingTime)}
              </button>
            ) : (
              <button
                type="button"
                onClick={startVoiceRecording}
                className="btn-outline flex items-center justify-center w-full hover:bg-primary-50 dark:hover:bg-primary-900/30"
              >
                <Mic className="w-5 h-5 mr-2" />
                {t('startVoiceRecording')}
              </button>
            )}
          </div>

          {/* Submit Button */}
          <button
            type="submit"
            disabled={loading || !symptoms.trim()}
            className="btn-primary w-full disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center"
          >
            {loading ? (
              <>
                <div className="animate-spin rounded-full h-5 w-5 border-b-2 border-white mr-2"></div>
                {t('analyzing')}
              </>
            ) : (
              <>
                <Brain className="w-5 h-5 mr-2" />
                {t('analyzeSymptoms')}
              </>
            )}
          </button>
        </form>
      </div>

      {/* Results */}
      {result && (
        <div className="card animate-fade-in">
          <h2 className="text-xl font-bold text-gray-900 dark:text-white mb-4">{t('analysisResults')}</h2>
          
          {/* Urgency Level */}
          <div className={`p-4 rounded-lg border-2 mb-4 ${getUrgencyColor(result.urgency)}`}>
            <div className="flex items-center space-x-3">
              {getUrgencyIcon(result.urgency)}
              <div>
                <p className="font-semibold">{t('urgencyLevel')}</p>
                <p className="text-sm capitalize">{result.urgency}</p>
              </div>
            </div>
          </div>

          {/* Possible Conditions */}
          <div className="mb-4">
            <h3 className="font-semibold text-gray-900 dark:text-white mb-2">{t('possibleConditions')}</h3>
            <ul className="space-y-2">
              {renderConditionList(result.possible_conditions)}
            </ul>
          </div>

          {/* Suggested Specialty */}
          <div className="mb-4">
            <h3 className="font-semibold text-gray-900 dark:text-white mb-2">{t('suggestedSpecialty')}</h3>
            <div className="flex items-center space-x-2 text-gray-700 dark:text-gray-300">
              <Stethoscope className="w-5 h-5" />
              <span>{result.suggested_specialty}</span>
            </div>
          </div>

          {/* Recommendations */}
          <div className="mb-4">
            <h3 className="font-semibold text-gray-900 dark:text-white mb-2">{t('recommendations')}</h3>
            <p className="text-gray-700 dark:text-gray-300">{result.recommendations}</p>
          </div>

          {result.disclaimer && (
            <div className="mb-4 bg-yellow-50 dark:bg-yellow-900/40 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
              <p className="text-sm text-yellow-800 dark:text-yellow-300 font-medium">{t('disclaimer')}</p>
              <p className="text-sm text-yellow-700 mt-1">{result.disclaimer}</p>
            </div>
          )}

          {/* Action */}
          {result.urgency === 'emergency' ? (
            <div className="bg-red-50 border border-red-200 rounded-lg p-4">
              <p className="text-red-800 font-medium">
                ⚠️ {t('immediateAttention')}
              </p>
            </div>
          ) : (
            <button className="btn-primary w-full">
              {t('bookAppointmentWith')} {result.suggested_specialty}
            </button>
          )}
        </div>
      )}

      {/* History */}
      {history.length > 0 && (
        <div className="card">
          <h2 className="text-xl font-bold text-gray-900 mb-4">{t('recentChecks')}</h2>
          <div className="space-y-4">
            {history.slice(1).map((item, index) => (
              <div key={index} className="border-b border-gray-200 pb-4 last:border-0 last:pb-0">
                <div className="flex items-center justify-between mb-2">
                  <span className={`badge ${getUrgencyColor(item.urgency)}`}>
                    {item.urgency}
                  </span>
                  <span className="text-sm text-gray-500">
                    {new Date().toLocaleDateString()}
                  </span>
                </div>
                <p className="text-sm text-gray-600">
                  {item.symptoms?.join(', ')}
                </p>
              </div>
            ))}
          </div>
        </div>
      )}
    </div>
  );
};

export default AISymptomChecker;
