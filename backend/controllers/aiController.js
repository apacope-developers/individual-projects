const { Patient, AICheck } = require('../models');
const { analyzeSymptoms } = require('../utils/ai');

/**
 * Submit symptoms for AI analysis
 */
const submitSymptoms = async (req, res, next) => {
  try {
    const user = req.user;
    const { symptoms } = req.body;

    if (!symptoms || !Array.isArray(symptoms) || symptoms.length === 0) {
      return res.status(400).json({
        success: false,
        message: 'Symptoms are required and must be an array'
      });
    }

    const patient = await Patient.findOne({ where: { user_id: user.id } });
    if (!patient) {
      return res.status(404).json({
        success: false,
        message: 'Patient profile not found'
      });
    }

    // Analyze symptoms using AI
    const aiResponse = await analyzeSymptoms(symptoms);

    // Save AI check to database
    const aiCheck = await AICheck.create({
      patient_id: patient.id,
      symptoms,
      ai_response: aiResponse,
      urgency: aiResponse.urgency,
      suggested_specialty: aiResponse.suggested_specialty,
      possible_conditions: aiResponse.possible_conditions,
      follow_up_action: aiResponse.recommendations
    });

    res.status(200).json({
      success: true,
      message: 'Symptoms analyzed successfully',
      data: {
        ...aiResponse,
        id: aiCheck.id,
        symptoms: aiCheck.symptoms,
        createdAt: aiCheck.createdAt
      }
    });
  } catch (error) {
    next(error);
  }
};

/**
 * Get AI check by ID
 */
const getAICheckById = async (req, res, next) => {
  try {
    const { id } = req.params;

    const aiCheck = await AICheck.findByPk(id, {
      include: [
        {
          model: Patient,
          include: [
            {
              model: require('../models').User,
              attributes: ['id', 'name']
            }
          ]
        }
      ]
    });

    if (!aiCheck) {
      return res.status(404).json({
        success: false,
        message: 'AI check not found'
      });
    }

    // Check authorization
    if (aiCheck.patient.user_id !== req.user.id && req.user.role !== 'admin') {
      return res.status(403).json({
        success: false,
        message: 'Not authorized to view this AI check'
      });
    }

    res.status(200).json({
      success: true,
      data: aiCheck
    });
  } catch (error) {
    next(error);
  }
};

module.exports = {
  submitSymptoms,
  getAICheckById
};
