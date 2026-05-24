const PDFDocument = require('pdfkit');
const fs = require('fs');
const path = require('path');

/**
 * Generate prescription PDF
 */
const generatePrescriptionPDF = async (prescriptionData) => {
  const { 
    patientName, 
    doctorName, 
    doctorSpecialty,
    medications, 
    instructions,
    date 
  } = prescriptionData;

  const fileName = `prescription-${Date.now()}.pdf`;
  const filePath = path.join(__dirname, '../uploads/prescriptions', fileName);

  const doc = new PDFDocument({ margin: 50 });
  const stream = fs.createWriteStream(filePath);

  doc.pipe(stream);

  // Header
  doc.fontSize(24).font('Helvetica-Bold').text('PRESCRIPTION', { align: 'center' });
  doc.moveDown();

  // Doctor info
  doc.fontSize(12).font('Helvetica').text(`Dr. ${doctorName}`, { align: 'right' });
  doc.text(`${doctorSpecialty}`, { align: 'right' });
  doc.moveDown();

  // Date
  doc.text(`Date: ${new Date(date).toLocaleDateString()}`);
  doc.moveDown();

  // Patient info
  doc.fontSize(14).font('Helvetica-Bold').text('Patient Information:');
  doc.fontSize(12).font('Helvetica').text(`Name: ${patientName}`);
  doc.moveDown();

  // Medications
  doc.fontSize(14).font('Helvetica-Bold').text('Medications:');
  doc.moveDown();

  medications.forEach((med, index) => {
    doc.fontSize(12).font('Helvetica-Bold').text(`${index + 1}. ${med.name}`);
    doc.fontSize(11).font('Helvetica').text(`   Dosage: ${med.dosage}`);
    doc.text(`   Duration: ${med.duration}`);
    if (med.instructions) {
      doc.text(`   Instructions: ${med.instructions}`);
    }
    doc.moveDown();
  });

  // Instructions
  if (instructions) {
    doc.fontSize(14).font('Helvetica-Bold').text('Additional Instructions:');
    doc.fontSize(12).font('Helvetica').text(instructions);
    doc.moveDown();
  }

  // Footer
  doc.fontSize(10).font('Helvetica').text('This prescription is valid for 30 days from the date of issue.', { align: 'center' });
  doc.text('Please consult your doctor if you experience any side effects.', { align: 'center' });

  doc.end();

  return new Promise((resolve, reject) => {
    stream.on('finish', () => resolve(fileName));
    stream.on('error', reject);
  });
};

module.exports = {
  generatePrescriptionPDF
};
