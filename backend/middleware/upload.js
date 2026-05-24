const multer = require('multer');
const path = require('path');
const fs = require('fs');

// Ensure upload directories exist
const uploadDirs = ['uploads/profiles', 'uploads/documents', 'uploads/prescriptions'];
uploadDirs.forEach(dir => {
  const fullPath = path.join(__dirname, '../..', dir);
  if (!fs.existsSync(fullPath)) {
    fs.mkdirSync(fullPath, { recursive: true });
  }
});

// Storage configuration
const storage = multer.diskStorage({
  destination: (req, file, cb) => {
    let uploadPath = 'uploads/documents';
    
    if (req.path.includes('profile') || req.path.includes('profile-photo')) {
      uploadPath = 'uploads/profiles';
    } else if (req.path.includes('prescription')) {
      uploadPath = 'uploads/prescriptions';
    }
    
    const fullPath = path.join(__dirname, '../..', uploadPath);
    cb(null, fullPath);
  },
  filename: (req, file, cb) => {
    const uniqueSuffix = Date.now() + '-' + Math.round(Math.random() * 1E9);
    cb(null, file.fieldname + '-' + uniqueSuffix + path.extname(file.originalname));
  }
});

// File filter
const fileFilter = (req, file, cb) => {
  const allowedTypes = /jpeg|jpg|png|pdf|doc|docx/;
  const extname = allowedTypes.test(path.extname(file.originalname).toLowerCase());
  const mimetype = allowedTypes.test(file.mimetype);

  if (extname && mimetype) {
    return cb(null, true);
  } else {
    cb(new Error('Only images (jpeg, jpg, png) and documents (pdf, doc, docx) are allowed'));
  }
};

// Multer instance
const upload = multer({
  storage: storage,
  limits: {
    fileSize: 5 * 1024 * 1024 // 5MB limit
  },
  fileFilter: fileFilter
});

// Single file upload middleware
const uploadSingle = (fieldName) => upload.single(fieldName);

// Multiple files upload middleware
const uploadMultiple = (fieldName, maxCount) => upload.array(fieldName, maxCount);

module.exports = {
  uploadSingle,
  uploadMultiple
};
