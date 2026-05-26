const OpenAI = require('openai');
require('dotenv').config();

// Initialize OpenAI client
const openaiApiKey = process.env.OPENAI_API_KEY?.trim();
const openai = new OpenAI({
  apiKey: openaiApiKey
});

const hasValidOpenAIKey = () => {
  return !!openaiApiKey && !openaiApiKey.startsWith('your_') && openaiApiKey.length > 20;
};

// Comprehensive symptom correlation database for generating related symptoms
const symptomCorrelations = {
  'headache': ['dizziness', 'light sensitivity', 'nausea', 'fatigue', 'neck stiffness'],
  'fever': ['chills', 'body aches', 'fatigue', 'sweating', 'loss of appetite'],
  'cough': ['sore throat', 'runny nose', 'shortness of breath', 'chest congestion', 'phlegm'],
  'fatigue': ['weakness', 'loss of appetite', 'difficulty concentrating', 'depression', 'irritability'],
  'nausea': ['vomiting', 'diarrhea', 'abdominal pain', 'loss of appetite', 'dizziness'],
  'dizziness': ['vertigo', 'loss of balance', 'headache', 'nausea', 'tinnitus'],
  'chest pain': ['shortness of breath', 'palpitations', 'anxiety', 'sweating', 'radiating pain to arm'],
  'shortness of breath': ['chest tightness', 'wheezing', 'fatigue', 'cough', 'anxiety'],
  'stomach pain': ['nausea', 'vomiting', 'diarrhea', 'bloating', 'loss of appetite'],
  'joint pain': ['swelling', 'stiffness', 'redness', 'warmth', 'limited mobility'],
  'skin rash': ['itching', 'redness', 'hives', 'burning sensation', 'scaling'],
  'sore throat': ['difficulty swallowing', 'hoarseness', 'cough', 'fever', 'swollen tonsils'],
  'back pain': ['stiffness', 'muscle tension', 'limited mobility', 'radiating pain', 'numbness'],
  'insomnia': ['daytime fatigue', 'difficulty concentrating', 'anxiety', 'irritability', 'headache'],
  'anxiety': ['panic attacks', 'rapid heartbeat', 'shortness of breath', 'sweating', 'trembling'],
};

const generateRelatedSymptoms = (symptoms) => {
  const normalized = symptoms.map(s => s.toLowerCase());
  const relatedSymptoms = new Set(symptoms);

  // Add correlated symptoms for each mentioned symptom
  for (const symptom of normalized) {
    const related = symptomCorrelations[symptom];
    if (related) {
      related.forEach(s => relatedSymptoms.add(s));
    }
  }

  return Array.from(relatedSymptoms);
};

const buildFallbackResponse = (symptoms) => {
  const normalized = symptoms.map(s => s.toLowerCase());
  const allSymptoms = generateRelatedSymptoms(symptoms);
  
  const conditions = [];
  let urgency = 'medium';
  let suggested_specialty = 'General Practitioner';
  let recommendations = 'Consult a doctor for a proper diagnosis and next steps.';

  const has = (keywords) => keywords.some(keyword => normalized.some(text => text.includes(keyword)));
  const hasAny = (keywords) => keywords.some(keyword => normalized.includes(keyword));

  // Emergency conditions (urgent assessment)
  if (has(['chest pain', 'shortness of breath', 'pressure in chest', 'tightness']) || 
      has(['severe chest pain', 'difficulty breathing', 'crushing sensation'])) {
    conditions.push({ 
      name: 'Acute Coronary Syndrome', 
      probability: '35%', 
      details: 'Chest pain with breathing difficulty could indicate heart-related emergency requiring immediate evaluation.' 
    });
    conditions.push({ 
      name: 'Pulmonary Embolism', 
      probability: '30%', 
      details: 'Sudden chest pain and shortness of breath may indicate blood clot in lungs.' 
    });
    conditions.push({ 
      name: 'Severe Anxiety or Panic Attack', 
      probability: '25%', 
      details: 'Acute stress can mimic cardiac symptoms with chest tightness and hyperventilation.' 
    });
    conditions.push({ 
      name: 'Pneumothorax', 
      probability: '10%', 
      details: 'Collapsed lung causes sudden sharp chest pain and difficulty breathing.' 
    });
    urgency = 'emergency';
    suggested_specialty = 'Cardiologist/Emergency Medicine';
    recommendations = 'SEEK IMMEDIATE EMERGENCY CARE. Call 911 or go to the nearest emergency department.';
  } 
  // High urgency conditions
  else if (has(['severe abdominal pain', 'uncontrollable vomiting', 'blood in stool', 'severe bleeding'])) {
    conditions.push({ 
      name: 'Acute Surgical Abdomen', 
      probability: '40%', 
      details: 'Severe abdominal pain may indicate appendicitis, perforation, or other surgical emergency.' 
    });
    conditions.push({ 
      name: 'Gastrointestinal Bleeding', 
      probability: '35%', 
      details: 'Severe vomiting with blood suggests upper GI bleeding requiring urgent intervention.' 
    });
    conditions.push({ 
      name: 'Acute Pancreatitis', 
      probability: '25%', 
      details: 'Severe upper abdominal pain with vomiting can indicate pancreatic inflammation.' 
    });
    urgency = 'high';
    suggested_specialty = 'Emergency Medicine/General Surgery';
    recommendations = 'Seek immediate emergency care. Do not delay, go to the nearest hospital.';
  }
  // Respiratory infections
  else if (has(['fever', 'cough', 'sore throat', 'runny nose', 'congestion']) || hasAny(['cough', 'fever', 'sore throat'])) {
    conditions.push({ 
      name: 'Viral Respiratory Infection', 
      probability: '50%', 
      details: 'Classic viral infection pattern with fever, cough, and throat symptoms.' 
    });
    conditions.push({ 
      name: 'Influenza', 
      probability: '35%', 
      details: 'Combination of fever, cough, and body aches typical of flu virus.' 
    });
    conditions.push({ 
      name: 'Acute Bronchitis', 
      probability: '15%', 
      details: 'Persistent cough with fever indicates bronchial inflammation.' 
    });
    urgency = has(['high fever', 'difficulty breathing', 'severe cough']) ? 'high' : 'medium';
    suggested_specialty = 'General Practitioner/Pulmonologist';
    recommendations = 'Rest, hydrate well, and use over-the-counter remedies. See a doctor if symptoms persist over 10 days or worsen.';
  }
  // Neurological symptoms
  else if (has(['headache', 'dizziness', 'sensitivity to light', 'nausea', 'neck stiffness'])) {
    conditions.push({ 
      name: 'Migraine', 
      probability: '45%', 
      details: 'Severe headache with light sensitivity and nausea is characteristic of migraine.' 
    });
    conditions.push({ 
      name: 'Meningitis', 
      probability: '15%', 
      details: 'Headache with neck stiffness and light sensitivity could indicate meningitis - requires urgent evaluation.' 
    });
    conditions.push({ 
      name: 'Tension Headache', 
      probability: '40%', 
      details: 'Stress-related headache with muscle tension and dizziness.' 
    });
    urgency = has(['high fever', 'neck stiffness', 'confusion']) ? 'emergency' : 'medium';
    suggested_specialty = 'Neurologist';
    recommendations = urgency === 'emergency' 
      ? 'SEEK IMMEDIATE EMERGENCY CARE - possible meningitis.'
      : 'Monitor symptoms, rest in dark room, and see a doctor if headaches become frequent.';
  }
  // Gastrointestinal issues
  else if (has(['stomach pain', 'nausea', 'vomiting', 'diarrhea']) || hasAny(['nausea', 'vomiting', 'diarrhea'])) {
    conditions.push({ 
      name: 'Viral Gastroenteritis', 
      probability: '50%', 
      details: 'Stomach flu symptoms with nausea, vomiting, and diarrhea.' 
    });
    conditions.push({ 
      name: 'Food Poisoning', 
      probability: '30%', 
      details: 'Acute GI symptoms after eating contaminated food.' 
    });
    conditions.push({ 
      name: 'Peptic Ulcer Disease', 
      probability: '20%', 
      details: 'Chronic stomach pain with nausea may indicate ulcer or gastritis.' 
    });
    urgency = has(['severe abdominal pain', 'blood in stool', 'uncontrollable vomiting']) ? 'high' : 'medium';
    suggested_specialty = 'Gastroenterologist';
    recommendations = 'Stay hydrated, avoid solid foods initially, rest. Seek medical care if symptoms persist beyond 48 hours or worsen.';
  }
  // Dermatological issues
  else if (has(['skin rash', 'itching', 'redness', 'hives', 'rash spreading'])) {
    conditions.push({ 
      name: 'Allergic Dermatitis', 
      probability: '45%', 
      details: 'Contact with allergen causing skin reaction and itching.' 
    });
    conditions.push({ 
      name: 'Eczema', 
      probability: '30%', 
      details: 'Chronic inflammatory skin condition with redness and itching.' 
    });
    conditions.push({ 
      name: 'Urticaria', 
      probability: '25%', 
      details: 'Allergic reaction causing hives and itching rash.' 
    });
    urgency = has(['difficulty breathing', 'rash spreading rapidly', 'swelling']) ? 'high' : 'medium';
    suggested_specialty = 'Dermatologist';
    recommendations = 'Avoid identified triggers, use moisturizers, and avoid scratching. See a dermatologist if rash spreads or persists.';
  }
  // Musculoskeletal pain
  else if (has(['joint pain', 'swelling', 'stiffness', 'muscle pain']) || hasAny(['joint pain', 'back pain', 'arthritis'])) {
    conditions.push({ 
      name: 'Osteoarthritis', 
      probability: '40%', 
      details: 'Wear-and-tear joint degeneration causing pain and stiffness.' 
    });
    conditions.push({ 
      name: 'Rheumatoid Arthritis', 
      probability: '30%', 
      details: 'Autoimmune joint inflammation causing symmetric pain and swelling.' 
    });
    conditions.push({ 
      name: 'Muscle Strain or Sprain', 
      probability: '30%', 
      details: 'Overuse or injury causing localized pain and stiffness.' 
    });
    urgency = 'medium';
    suggested_specialty = 'Rheumatologist/Orthopedist';
    recommendations = 'Apply ice/heat, rest affected area, and use over-the-counter pain relief. Physical therapy may help. See specialist if pain is severe.';
  }
  // General symptoms suggesting systemic issues
  else if (hasAny(['fatigue', 'weakness', 'weight loss']) || has(['chronic fatigue', 'unexplained weight loss'])) {
    conditions.push({ 
      name: 'Chronic Fatigue Syndrome', 
      probability: '35%', 
      details: 'Persistent fatigue affecting daily functioning.' 
    });
    conditions.push({ 
      name: 'Anemia', 
      probability: '25%', 
      details: 'Low red blood cell count causing fatigue and weakness.' 
    });
    conditions.push({ 
      name: 'Thyroid Disorder', 
      probability: '25%', 
      details: 'Hypothyroidism causes fatigue, weight changes, and weakness.' 
    });
    conditions.push({ 
      name: 'Depression', 
      probability: '15%', 
      details: 'Mental health condition manifesting as fatigue and low energy.' 
    });
    urgency = 'medium';
    suggested_specialty = 'General Practitioner';
    recommendations = 'Get comprehensive blood work done. Consult a doctor for complete evaluation including thyroid function tests.';
  }
  // Anxiety or panic-related
  else if (hasAny(['anxiety', 'panic attack', 'nervousness']) || has(['rapid heartbeat', 'sweating', 'trembling'])) {
    conditions.push({ 
      name: 'Anxiety Disorder', 
      probability: '50%', 
      details: 'Excessive worry and physical anxiety symptoms.' 
    });
    conditions.push({ 
      name: 'Panic Disorder', 
      probability: '30%', 
      details: 'Sudden intense fear with physical panic symptoms.' 
    });
    conditions.push({ 
      name: 'Hyperthyroidism', 
      probability: '10%', 
      details: 'Overactive thyroid can mimic anxiety symptoms.' 
    });
    conditions.push({ 
      name: 'Medication Side Effect', 
      probability: '10%', 
      details: 'Some medications can cause anxiety-like symptoms.' 
    });
    urgency = 'medium';
    suggested_specialty = 'Psychiatrist/Psychologist';
    recommendations = 'Practice relaxation techniques, regular exercise, and mindfulness. Consider therapy or consult psychiatrist for proper diagnosis.';
  }
  // Default for uncommon symptom combinations
  else {
    conditions.push({ 
      name: 'General Health Assessment Required', 
      probability: '100%', 
      details: 'Comprehensive medical evaluation needed to determine cause of symptoms.' 
    });
    urgency = 'medium';
    suggested_specialty = 'General Practitioner';
    recommendations = 'Schedule a thorough evaluation with your healthcare provider including physical exam and appropriate tests.';
  }

  return {
    possible_conditions: conditions,
    urgency,
    suggested_specialty,
    recommendations,
    generated_related_symptoms: generateRelatedSymptoms(symptoms).slice(symptoms.length),
    disclaimer: 'This is AI-generated information for informational purposes only and should NOT replace professional medical advice. Always consult a healthcare provider for proper diagnosis and treatment.'
  };
};

const extractJSON = (text) => {
  const jsonMatch = text.match(/\{[\s\S]*\}$/);
  if (!jsonMatch) return null;

  try {
    return JSON.parse(jsonMatch[0]);
  } catch {
    return null;
  }
};

/**
 * Analyze symptoms using AI with comprehensive generation of related symptoms
 */
const analyzeSymptoms = async (symptoms) => {
  try {
    if (!hasValidOpenAIKey()) {
      console.warn('OpenAI API key missing or invalid. Using advanced local symptom analyzer with symptom generation.');
      return buildFallbackResponse(symptoms);
    }

    const relatedSymptoms = generateRelatedSymptoms(symptoms);
    const prompt = `You are a comprehensive medical AI assistant. Analyze the following patient symptoms DEEPLY:

Primary Symptoms Provided:
${symptoms.map((symptom, index) => `${index + 1}. ${symptom}`).join('\n')}

Related Symptoms To Consider (commonly associated):
${relatedSymptoms.filter(s => !symptoms.includes(s)).slice(0, 10).map(s => `- ${s}`).join('\n')}

Provide a comprehensive analysis that includes:
1. Possible conditions (4-6 most likely) with probability, detailed explanation
2. Related symptoms the patient might develop
3. Urgency level: low, medium, high, or emergency
4. Suggested medical specialty
5. Detailed recommendations for patient
6. Medical disclaimer

Consider:
- Symptom combinations and their medical significance
- Severity indicators (if symptoms are described as severe)
- Age-related conditions (assume adult unless indicated)
- Common vs rare conditions based on symptom frequency
- Conditions that require urgent vs routine care

Respond ONLY with valid JSON in this exact format:
{
  "possible_conditions": [
    {"name": "Condition", "probability": "XX%", "details": "Detailed explanation of how symptoms relate."},
    ...
  ],
  "related_symptoms": ["symptom1", "symptom2"],
  "urgency": "low|medium|high|emergency",
  "suggested_specialty": "specialty name",
  "recommendations": "Detailed recommendations",
  "disclaimer": "Medical disclaimer"
}`;    

    const completion = await openai.chat.completions.create({
      model: 'gpt-3.5-turbo',
      messages: [
        {
          role: 'system',
          content: 'You are a medical assistant AI. Analyze symptoms comprehensively. Always produce valid JSON only with no markdown or extra text.'
        },
        {
          role: 'user',
          content: prompt
        }
      ],
      temperature: 0.5,
      max_tokens: 800
    });

    const response = completion.choices?.[0]?.message?.content?.trim();
    let parsedResponse = null;
    if (response) {
      parsedResponse = extractJSON(response);
    }

    if (!parsedResponse) {
      return buildFallbackResponse(symptoms);
    }

    return {
      possible_conditions: parsedResponse.possible_conditions || buildFallbackResponse(symptoms).possible_conditions,
      generated_related_symptoms: parsedResponse.related_symptoms || [],
      urgency: parsedResponse.urgency || 'medium',
      suggested_specialty: parsedResponse.suggested_specialty || 'General Practitioner',
      recommendations: parsedResponse.recommendations || 'Consult a doctor for proper diagnosis.',
      disclaimer: parsedResponse.disclaimer || 'This is AI-generated information and should not replace professional medical advice.'
    };
  } catch (error) {
    console.error('AI Analysis Error:', error);
    return buildFallbackResponse(symptoms);
  }
};

module.exports = {
  analyzeSymptoms,
  generateRelatedSymptoms
};
