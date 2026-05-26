const express = require('express');
const cors = require('cors');
const helmet = require('helmet');
const rateLimit = require('express-rate-limit');
const path = require('path');
require('dotenv').config();

// Import configurations
const { testConnection, syncDatabase } = require('./config/database');

// Import middleware
const { errorHandler, notFound } = require('./middleware/errorHandler');

// Import routes
const apiRoutes = require('./routes');

// Initialize Express app
const app = express();

// Security middleware (relaxed for local images and dev)
app.use(helmet({
  crossOriginResourcePolicy: { policy: 'cross-origin' },
  contentSecurityPolicy: process.env.NODE_ENV === 'production' ? undefined : false,
}));

// CORS — allow local dev ports (Vite may use 3000, 3001, 5173, etc.)
const allowedOrigins = [
  process.env.FRONTEND_URL,
  'http://localhost:3000',
  'http://localhost:3001',
  'http://localhost:5173',
  'http://127.0.0.1:3000',
  'http://127.0.0.1:3001',
].filter(Boolean);

app.use(cors({
  origin: (origin, callback) => {
    if (!origin) return callback(null, true);
    if (allowedOrigins.includes(origin)) return callback(null, true);
    if (process.env.NODE_ENV !== 'production' && /^https?:\/\/(localhost|127\.0\.0\.1)(:\d+)?$/.test(origin)) {
      return callback(null, true);
    }
    callback(new Error('Not allowed by CORS'));
  },
  credentials: true,
}));

// Rate limiting
const limiter = rateLimit({
  windowMs: 15 * 60 * 1000, // 15 minutes
  max: 100, // limit each IP to 100 requests per windowMs
  message: 'Too many requests from this IP, please try again later.'
});
app.use('/api/', limiter);

// Body parsing middleware
app.use(express.json({ limit: '10mb' }));
app.use(express.urlencoded({ extended: true, limit: '10mb' }));

// Static files
app.use('/uploads', express.static(path.join(__dirname, '..', 'uploads')));

// Health check endpoint
app.get('/health', (req, res) => {
  res.status(200).json({
    success: true,
    message: 'Server is running',
    timestamp: new Date().toISOString()
  });
});

// API routes
app.use('/api', apiRoutes);

// 404 handler
app.use(notFound);

// Error handler
app.use(errorHandler);

// Socket.io setup for video calls
const server = require('http').createServer(app);
const io = require('socket.io')(server, {
  cors: {
    origin: allowedOrigins.length ? allowedOrigins : ['http://localhost:3000', 'http://localhost:3001'],
    credentials: true
  }
});

// Socket.io connection handling
io.on('connection', (socket) => {
  console.log('Client connected:', socket.id);

  // WebRTC signaling for video calls
  socket.on('join-call', ({ appointmentId, userId, role }) => {
    socket.join(`appointment-${appointmentId}`);
    socket.to(`appointment-${appointmentId}`).emit('user-joined', { userId, role });
  });

  socket.on('offer', ({ appointmentId, offer }) => {
    socket.to(`appointment-${appointmentId}`).emit('offer', { offer });
  });

  socket.on('answer', ({ appointmentId, answer }) => {
    socket.to(`appointment-${appointmentId}`).emit('answer', { answer });
  });

  socket.on('ice-candidate', ({ appointmentId, candidate }) => {
    socket.to(`appointment-${appointmentId}`).emit('ice-candidate', { candidate });
  });

  socket.on('end-call', ({ appointmentId }) => {
    socket.to(`appointment-${appointmentId}`).emit('call-ended');
  });

  socket.on('toggle-audio', ({ appointmentId, enabled }) => {
    socket.to(`appointment-${appointmentId}`).emit('audio-toggled', { enabled });
  });

  socket.on('toggle-video', ({ appointmentId, enabled }) => {
    socket.to(`appointment-${appointmentId}`).emit('video-toggled', { enabled });
  });

  // Real-time notifications
  socket.on('join-notifications', (userId) => {
    socket.join(`user-${userId}`);
  });

  socket.on('disconnect', () => {
    console.log('Client disconnected:', socket.id);
  });
});

// Export io for use in controllers
app.set('io', io);

// Start server
const PORT = process.env.PORT || 5000;

const startServer = async () => {
  try {
    // Test database connection
    await testConnection();
    
    // Sync database (create tables if they don't exist)
    const alterDatabase = process.env.DB_SYNC_ALTER === 'true';
    await syncDatabase(false, alterDatabase);
    
    // Start listening
    server.listen(PORT, () => {
      console.log(`🚀 Server running on port ${PORT}`);
      console.log(`📡 Environment: ${process.env.NODE_ENV || 'development'}`);
      console.log(`🔗 API URL: http://localhost:${PORT}/api`);
      console.log(`🏥 Health check: http://localhost:${PORT}/health`);
    });
  } catch (error) {
    console.error('❌ Failed to start server:', error);
    process.exit(1);
  }
};

startServer();

// Handle unhandled promise rejections
process.on('unhandledRejection', (err) => {
  console.error('❌ Unhandled Rejection:', err);
  server.close(() => process.exit(1));
});

module.exports = { app, server, io };
