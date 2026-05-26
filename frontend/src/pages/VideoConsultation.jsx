import { useState, useEffect, useRef, useCallback } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import { useLanguage } from '../context/LanguageContext';
import { appointmentService } from '../services/appointmentService';
import { toast } from 'react-hot-toast';
import { io } from 'socket.io-client';
import {
  Video,
  VideoOff,
  Mic,
  MicOff,
  PhoneOff,
  Clock,
  Stethoscope,
} from 'lucide-react';

const SOCKET_URL = import.meta.env.VITE_API_URL
  ? import.meta.env.VITE_API_URL.replace('/api', '')
  : (import.meta.env.DEV ? window.location.origin : 'http://localhost:5000');

const VideoConsultation = () => {
  const { appointmentId } = useParams();
  const navigate = useNavigate();
  const { user } = useAuth();
  const { t } = useLanguage();
  const [appointment, setAppointment] = useState(null);
  const [loading, setLoading] = useState(true);
  const [inCall, setInCall] = useState(false);
  const [callDuration, setCallDuration] = useState(0);
  const [localStream, setLocalStream] = useState(null);
  const [remoteStream, setRemoteStream] = useState(null);
  const [isAudioEnabled, setIsAudioEnabled] = useState(true);
  const [isVideoEnabled, setIsVideoEnabled] = useState(true);
  
  const localVideoRef = useRef(null);
  const remoteVideoRef = useRef(null);
  const peerConnectionRef = useRef(null);
  const socketRef = useRef(null);
  const isInitiatorRef = useRef(false);
  const appointmentCompletedRef = useRef(false);

  const cleanup = useCallback(() => {
    if (localStream) {
      localStream.getTracks().forEach((track) => track.stop());
    }
    if (peerConnectionRef.current) {
      peerConnectionRef.current.close();
      peerConnectionRef.current = null;
    }
    if (socketRef.current) {
      socketRef.current.disconnect();
      socketRef.current = null;
    }
    setInCall(false);
    setLocalStream(null);
    setRemoteStream(null);
  }, [localStream]);

  useEffect(() => {
    loadAppointment();
    return () => cleanup();
  }, [appointmentId]);

  useEffect(() => {
    let interval;
    if (inCall) {
      interval = setInterval(() => setCallDuration((prev) => prev + 1), 1000);
    }
    return () => clearInterval(interval);
  }, [inCall]);

  const loadAppointment = async () => {
    try {
      setLoading(true);
      const response = await appointmentService.getAppointmentById(appointmentId);
      setAppointment(response.data);
    } catch {
      toast.error(t('failedToLoad'));
    } finally {
      setLoading(false);
    }
  };

  const createPeerConnection = (stream) => {
    const pc = new RTCPeerConnection({
      iceServers: [
        { urls: 'stun:stun.l.google.com:19302' },
        { urls: 'stun:stun1.l.google.com:19302' },
      ],
    });

    stream.getTracks().forEach((track) => pc.addTrack(track, stream));

    pc.ontrack = (event) => {
      setRemoteStream(event.streams[0]);
      if (remoteVideoRef.current) {
        remoteVideoRef.current.srcObject = event.streams[0];
      }
    };

    pc.onicecandidate = (event) => {
      if (event.candidate && socketRef.current) {
        socketRef.current.emit('ice-candidate', { appointmentId, candidate: event.candidate });
      }
    };

    peerConnectionRef.current = pc;
    return pc;
  };

  const initializeWebRTC = async () => {
    const stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
    setLocalStream(stream);
    if (localVideoRef.current) {
      localVideoRef.current.srcObject = stream;
    }
    createPeerConnection(stream);
    return stream;
  };

  const setupSocket = () => {
    return new Promise((resolve) => {
      socketRef.current = io(SOCKET_URL, { transports: ['websocket', 'polling'] });

      socketRef.current.on('connect', () => {
        socketRef.current.emit('join-call', {
          appointmentId,
          userId: user?.id,
          role: user?.role,
        });
        resolve();
      });

      socketRef.current.on('offer', async ({ offer }) => {
        try {
          if (!peerConnectionRef.current) await initializeWebRTC();
          await peerConnectionRef.current.setRemoteDescription(new RTCSessionDescription(offer));
          const answer = await peerConnectionRef.current.createAnswer();
          await peerConnectionRef.current.setLocalDescription(answer);
          socketRef.current.emit('answer', { appointmentId, answer });
        } catch (err) {
          console.error('Offer handling error:', err);
        }
      });

      socketRef.current.on('answer', async ({ answer }) => {
        try {
          if (peerConnectionRef.current) {
            await peerConnectionRef.current.setRemoteDescription(new RTCSessionDescription(answer));
          }
        } catch (err) {
          console.error('Answer handling error:', err);
        }
      });

      socketRef.current.on('ice-candidate', async ({ candidate }) => {
        try {
          if (peerConnectionRef.current && candidate) {
            await peerConnectionRef.current.addIceCandidate(new RTCIceCandidate(candidate));
          }
        } catch (err) {
          console.error('ICE error:', err);
        }
      });

      socketRef.current.on('user-joined', ({ role }) => {
        toast.success(`${role === 'doctor' ? 'Doctor' : 'Patient'} joined the call`);
      });

      socketRef.current.on('call-ended', async () => {
        await completeDoctorAppointment();
        cleanup();
        const home = user?.role === 'doctor' ? '/doctor/dashboard' : '/dashboard';
        navigate(home);
      });
    });
  };

  const startCall = async () => {
    try {
      isInitiatorRef.current = user?.role === 'patient';
      await setupSocket();
      await initializeWebRTC();

      if (isInitiatorRef.current) {
        const offer = await peerConnectionRef.current.createOffer();
        await peerConnectionRef.current.setLocalDescription(offer);
        socketRef.current.emit('offer', { appointmentId, offer });
      }

      setInCall(true);
      toast.success(t('startCall'));
    } catch (error) {
      toast.error('Failed to access camera/microphone. Please allow permissions.');
      console.error('Call error:', error);
    }
  };

  const toggleAudio = () => {
    if (localStream) {
      localStream.getAudioTracks().forEach((track) => {
        track.enabled = !isAudioEnabled;
      });
      setIsAudioEnabled(!isAudioEnabled);
    }
  };

  const toggleVideo = () => {
    if (localStream) {
      localStream.getVideoTracks().forEach((track) => {
        track.enabled = !isVideoEnabled;
      });
      setIsVideoEnabled(!isVideoEnabled);
    }
  };

  const completeDoctorAppointment = async () => {
    if (user?.role !== 'doctor' || appointmentCompletedRef.current) return;

    try {
      await appointmentService.completeAppointment(appointmentId);
      appointmentCompletedRef.current = true;
      toast.success(t('complete') + ' ' + t('loginSuccess').replace('!', ''));
    } catch (err) {
      console.error('Complete appointment error:', err);
    }
  };

  const endCall = async () => {
    if (socketRef.current) {
      socketRef.current.emit('end-call', { appointmentId });
    }

    await completeDoctorAppointment();
    cleanup();
    const home = user?.role === 'doctor' ? '/doctor/dashboard' : '/dashboard';
    navigate(home);
  };

  const formatDuration = (seconds) => {
    const mins = Math.floor(seconds / 60);
    const secs = seconds % 60;
    return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
  };

  const otherPartyName = user?.role === 'doctor'
    ? appointment?.patient?.user?.name
    : appointment?.doctor?.user?.name;

  if (loading) {
    return (
      <div className="flex items-center justify-center h-64">
        <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600"></div>
      </div>
    );
  }

  return (
    <div className="min-h-screen flex flex-col py-4 px-3 sm:px-6">
      <div className="card mb-4">
        <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
          <div className="flex items-center gap-4">
            <div className="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center">
              <Stethoscope className="w-6 h-6 text-primary-600" />
            </div>
            <div>
              <h1 className="text-lg font-semibold text-gray-900 dark:text-white">
                {user?.role === 'doctor' ? '' : 'Dr. '}{otherPartyName}
              </h1>
              <p className="text-sm text-gray-600 dark:text-gray-400">{appointment?.doctor?.specialty}</p>
            </div>
          </div>
          {inCall && (
            <div className="flex items-center justify-between gap-2 bg-gray-100 dark:bg-gray-700 px-4 py-2 rounded-lg">
              <Clock className="w-5 h-5" />
              <span className="font-mono font-semibold">{formatDuration(callDuration)}</span>
            </div>
          )}
        </div>
      </div>

      <div className="flex-1 grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div className="card bg-gray-900 relative overflow-hidden min-h-[260px] sm:min-h-[320px]">
          <span className="absolute top-4 left-4 z-10 bg-black/50 text-white px-3 py-1 rounded-full text-sm">
            {otherPartyName || 'Remote'}
          </span>
          <video ref={remoteVideoRef} autoPlay playsInline className="w-full h-full object-cover" />
          {!remoteStream && (
            <div className="absolute inset-0 flex items-center justify-center text-white px-4 text-center">
              <p>Waiting for the other participant...</p>
            </div>
          )}
        </div>

        <div className="card bg-gray-900 relative overflow-hidden min-h-[260px] sm:min-h-[320px]">
          <span className="absolute top-4 left-4 z-10 bg-black/50 text-white px-3 py-1 rounded-full text-sm">You</span>
          <video ref={localVideoRef} autoPlay playsInline muted className="w-full h-full object-cover" />
        </div>
      </div>

      <div className="card mt-4">
        <div className="flex flex-col gap-3 items-center justify-center sm:flex-row sm:space-x-4">
          {!inCall ? (
            <button onClick={startCall} className="btn-primary flex w-full max-w-xs items-center justify-center px-6 py-3">
              <Video className="w-5 h-5 mr-2" />
              {t('startCall')}
            </button>
          ) : (
            <>
              <button
                onClick={toggleAudio}
                className={`w-full max-w-xs p-4 rounded-full ${isAudioEnabled ? 'bg-gray-200' : 'bg-red-500 text-white'}`}
              >
                {isAudioEnabled ? <Mic className="w-6 h-6" /> : <MicOff className="w-6 h-6" />}
              </button>
              <button
                onClick={toggleVideo}
                className={`w-full max-w-xs p-4 rounded-full ${isVideoEnabled ? 'bg-gray-200' : 'bg-red-500 text-white'}`}
              >
                {isVideoEnabled ? <Video className="w-6 h-6" /> : <VideoOff className="w-6 h-6" />}
              </button>
              <button onClick={endCall} className="w-full max-w-xs p-4 rounded-full bg-red-500 text-white">
                <PhoneOff className="w-6 h-6" />
              </button>
            </>
          )}
        </div>
      </div>
    </div>
  );
};

export default VideoConsultation;
