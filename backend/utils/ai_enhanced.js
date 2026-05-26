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

// Comprehensive medical knowledge base for intelligent analysis
const medicalKnowledge = {
  // Symptom categories for classification
  categories: {
    cardiovascular: ['chest pain', 'palpitations', 'arrhythmia', 'shortness of breath', 'dizziness', 'fainting', 'edema', 'heart murmur', 'hypertension', 'hypotension'],
    respiratory: ['cough', 'shortness of breath', 'wheezing', 'chest congestion', 'sore throat', 'runny nose', 'sinus congestion', 'asthma', 'pneumonia', 'bronchitis'],
    gastrointestinal: ['nausea', 'vomiting', 'diarrhea', 'constipation', 'abdominal pain', 'stomach pain', 'bloating', 'acid reflux', 'stomach cramps', 'hemorrhoids'],
    neurological: ['headache', 'dizziness', 'vertigo', 'numbness', 'tingling', 'tremor', 'seizure', 'loss of consciousness', 'brain fog', 'memory loss'],
    musculoskeletal: ['joint pain', 'muscle pain', 'back pain', 'neck pain', 'arthritis', 'cramps', 'stiffness', 'swelling', 'weakness', 'fracture'],
    dermatological: ['skin rash', 'itching', 'hives', 'acne', 'eczema', 'psoriasis', 'warts', 'moles', 'dermatitis', 'urticaria'],
    infectious: ['fever', 'chills', 'body aches', 'malaise', 'fatigue', 'night sweats', 'enlarged lymph nodes', 'flu-like symptoms'],
    endocrine: ['fatigue', 'weight loss', 'weight gain', 'excessive thirst', 'frequent urination', 'hair loss', 'temperature sensitivity'],
    psychological: ['anxiety', 'depression', 'stress', 'panic attack', 'insomnia', 'nightmares', 'panic disorder', 'PTSD', 'mood swings'],
    systemic: ['fever', 'fatigue', 'weakness', 'loss of appetite', 'weight loss', 'malaise', 'night sweats']
  },

  // Symptom relationships for generating related symptoms
  correlations: {
    'headache': ['dizziness', 'nausea', 'light sensitivity', 'neck stiffness', 'fatigue', 'sensitivity to sound', 'vision changes', 'scalp tenderness'],
    'fever': ['chills', 'body aches', 'fatigue', 'sweating', 'loss of appetite', 'muscle pain', 'headache', 'weakness', 'dehydration'],
    'cough': ['sore throat', 'runny nose', 'chest congestion', 'shortness of breath', 'phlegm', 'voice hoarseness', 'chest pain', 'sleep disruption'],
    'fatigue': ['weakness', 'loss of appetite', 'difficulty concentrating', 'depression', 'irritability', 'body aches', 'fever', 'weight changes'],
    'nausea': ['vomiting', 'diarrhea', 'abdominal pain', 'loss of appetite', 'dizziness', 'sweating', 'weakness', 'sensitivity to smells'],
    'dizziness': ['vertigo', 'loss of balance', 'headache', 'nausea', 'tinnitus', 'blurred vision', 'anxiety', 'weakness'],
    'chest pain': ['shortness of breath', 'palpitations', 'anxiety', 'sweating', 'radiating pain to arm', 'jaw pain', 'back pain', 'nausea'],
    'shortness of breath': ['chest tightness', 'wheezing', 'fatigue', 'cough', 'anxiety', 'panic attacks', 'rapid heartbeat', 'dizziness'],
    'stomach pain': ['nausea', 'vomiting', 'diarrhea', 'bloating', 'loss of appetite', 'constipation', 'gas', 'abdominal distension'],
    'joint pain': ['swelling', 'stiffness', 'redness', 'warmth', 'limited mobility', 'muscle weakness', 'inflammation', 'tenderness'],
    'skin rash': ['itching', 'redness', 'hives', 'burning sensation', 'scaling', 'blistering', 'pain', 'swelling'],
    'sore throat': ['difficulty swallowing', 'hoarseness', 'cough', 'fever', 'swollen tonsils', 'white spots', 'bad breath', 'earache'],
    'back pain': ['stiffness', 'muscle tension', 'limited mobility', 'radiating pain', 'numbness', 'tingling', 'weakness', 'leg pain'],
    'insomnia': ['daytime fatigue', 'difficulty concentrating', 'anxiety', 'irritability', 'headache', 'mood changes', 'weakened immunity'],
    'anxiety': ['panic attacks', 'rapid heartbeat', 'shortness of breath', 'sweating', 'trembling', 'chest pain', 'dizziness', 'nausea'],
    'depression': ['fatigue', 'loss of interest', 'hopelessness', 'weight changes', 'sleep disturbance', 'concentration issues', 'worthlessness'],
    'diarrhea': ['nausea', 'vomiting', 'abdominal pain', 'dehydration', 'cramping', 'bloating', 'loss of appetite', 'weakness'],
    'constipation': ['abdominal pain', 'bloating', 'loss of appetite', 'hardened stool', 'incomplete evacuation', 'straining', 'hemorrhoids'],
    'dizziness': ['vertigo', 'nausea', 'headache', 'balance problems', 'tinnitus', 'visual disturbances', 'anxiety', 'weakness'],
    'weakness': ['fatigue', 'muscle pain', 'trembling', 'loss of appetite', 'fever', 'depression', 'difficulty moving', 'balance issues'],
    'sweating': ['fever', 'anxiety', 'hypoglycemia', 'rapid heartbeat', 'nausea', 'weakness', 'chills', 'dehydration'],
    'itching': ['skin rash', 'hives', 'dry skin', 'sensitivity', 'burning sensation', 'redness', 'inflammation', 'allergic reaction'],
    'numbness': ['tingling', 'muscle weakness', 'difficulty moving', 'nerve damage symptoms', 'pain', 'stiffness', 'cold sensation'],
    'blurred vision': ['eye strain', 'headache', 'dizziness', 'difficulty focusing', 'light sensitivity', 'floaters', 'pain in eye'],
    'hair loss': ['stress', 'nutritional deficiency', 'hormonal changes', 'scalp issues', 'itching', 'burning sensation', 'anxiety'],
    'memory loss': ['difficulty concentrating', 'confusion', 'stress', 'sleep deprivation', 'depression', 'anxiety', 'cognitive issues'],
    'weight loss': ['fatigue', 'loss of appetite', 'nausea', 'fever', 'abdominal pain', 'difficulty swallowing', 'mood changes'],
    'weight gain': ['fatigue', 'depression', 'hormonal changes', 'loss of appetite paradoxically', 'bloating', 'water retention', 'mood swings'],
  }
};

// Enhanced function to generate comprehensive related symptoms for ANY input
const generateRelatedSymptoms = (symptoms) => {
  const normalized = symptoms.map(s => s.toLowerCase().trim());
  const relatedSymptoms = new Set(symptoms);

  // Add correlated symptoms for each mentioned symptom
  for (const symptom of normalized) {
    // Exact match
    const exactRelated = medicalKnowledge.correlations[symptom];
    if (exactRelated) {
      exactRelated.forEach(s => relatedSymptoms.add(s));
    }

    // Partial match - for symptoms not in database, find related category
    let foundCategory = false;
    for (const [category, categorySymptoms] of Object.entries(medicalKnowledge.categories)) {
      if (categorySymptoms.some(cs => cs.includes(symptom) || symptom.includes(cs))) {
        // Add other symptoms from the same category as related
        categorySymptoms.forEach(cs => {
          if (!normalized.includes(cs) && cs !== symptom) {
            relatedSymptoms.add(cs);
          }
        });
        foundCategory = true;
        break;
      }
    }

    // If still not found, generate intelligent guesses based on similarity
    if (!foundCategory) {
      // Check for symptom patterns (e.g., "severe" prefix, "chronic" prefix)
      const hasPrefix = symptom.match(/^(severe|chronic|mild|acute|extreme)\s+/i);
      const baseSymptom = hasPrefix ? symptom.replace(/^(severe|chronic|mild|acute|extreme)\s+/i, '').trim() : symptom;
      
      // Try to find similar conditions
      for (const [key, relatedList] of Object.entries(medicalKnowledge.correlations)) {
        if (key.includes(baseSymptom) || baseSymptom.includes(key)) {
          relatedList.forEach(s => relatedSymptoms.add(s));
        }
      }
    }
  }

  // Remove original symptoms from related set to show only NEW related symptoms
  symptoms.forEach(s => relatedSymptoms.delete(s));
  
  return Array.from(relatedSymptoms);
};

// Intelligent fallback analyzer for comprehensive unbound analysis
const buildFallbackResponse = (symptoms) => {
  const normalized = symptoms.map(s => s.toLowerCase().trim());
  const allRelatedSymptoms = generateRelatedSymptoms(symptoms);
  
  const conditions = [];
  let urgency = 'medium';
  let suggested_specialty = 'General Practitioner';
  let recommendations = 'Consult a doctor for a thorough evaluation.';

  const has = (keywords) => keywords.some(keyword => 
    normalized.some(text => text.includes(keyword) || keyword.includes(text))
  );
  
  const hasAll = (keywords) => keywords.every(keyword => 
    normalized.some(text => text.includes(keyword) || keyword.includes(text))
  );

  // Helper function to detect severity modifiers
  const isSevere = () => normalized.some(s => s.includes('severe') || s.includes('extreme') || s.includes('critical'));
  const isChronic = () => normalized.some(s => s.includes('chronic') || s.includes('persistent'));

  // EMERGENCY CONDITIONS
  if (has(['chest pain', 'shortness of breath']) || has(['difficulty breathing', 'chest pressure'])) {
    conditions.push({ 
      name: 'Acute Coronary Syndrome (Heart Attack)', 
      probability: '35%', 
      details: 'Chest pain with breathing difficulty can indicate acute heart condition requiring immediate evaluation.' 
    });
    conditions.push({ 
      name: 'Pulmonary Embolism', 
      probability: '30%', 
      details: 'Sudden chest pain and shortness of breath may indicate blood clot in lungs.' 
    });
    conditions.push({ 
      name: 'Aortic Dissection', 
      probability: '15%', 
      details: 'Severe sudden chest pain with difficulty breathing requires emergency assessment.' 
    });
    conditions.push({ 
      name: 'Acute Asthma/COPD Exacerbation', 
      probability: '15%', 
      details: 'Sudden breathing difficulty with chest tightness in susceptible patients.' 
    });
    conditions.push({ 
      name: 'Panic Attack/Severe Anxiety', 
      probability: '5%', 
      details: 'Psychological emergency presenting with cardiac symptoms.' 
    });
    urgency = 'emergency';
    suggested_specialty = 'Emergency Medicine/Cardiology';
    recommendations = '⚠️ SEEK IMMEDIATE EMERGENCY CARE. Call emergency services or go to nearest emergency department NOW.';
  }
  // Severe bleeding or shock symptoms
  else if (has(['severe bleeding', 'uncontrolled bleeding', 'blood in vomit']) || has(['loss of consciousness', 'severe dizziness', 'confusion'])) {
    conditions.push({ 
      name: 'Gastrointestinal Hemorrhage', 
      probability: '40%', 
      details: 'Internal bleeding requiring urgent intervention.' 
    });
    conditions.push({ 
      name: 'Hemorrhagic Shock', 
      probability: '35%', 
      details: 'Severe blood loss causing shock symptoms.' 
    });
    conditions.push({ 
      name: 'Severe Coagulopathy', 
      probability: '25%', 
      details: 'Blood clotting disorder causing uncontrolled bleeding.' 
    });
    urgency = 'emergency';
    suggested_specialty = 'Emergency Medicine/Hematology';
    recommendations = '⚠️ CALL EMERGENCY SERVICES IMMEDIATELY. This is a life-threatening condition.';
  }
  // Severe neurological symptoms
  else if (has(['loss of consciousness', 'seizure', 'severe headache']) || hasAll(['headache', 'stiff neck', 'fever'])) {
    conditions.push({ 
      name: 'Meningitis', 
      probability: '40%', 
      details: 'Headache with stiff neck and fever is classic triad requiring emergency assessment.' 
    });
    conditions.push({ 
      name: 'Encephalitis', 
      probability: '25%', 
      details: 'Brain inflammation causing severe headache and neurological symptoms.' 
    });
    conditions.push({ 
      name: 'Stroke or TIA', 
      probability: '20%', 
      details: 'Sudden neurological symptoms require emergency evaluation.' 
    });
    conditions.push({ 
      name: 'Severe Migraine with Aura', 
      probability: '15%', 
      details: 'Severe headache with neurological symptoms.' 
    });
    urgency = 'emergency';
    suggested_specialty = 'Emergency Medicine/Neurology';
    recommendations = '⚠️ SEEK EMERGENCY CARE IMMEDIATELY. Time is critical for neurological emergencies.';
  }
  // Severe abdominal pain
  else if (has(['severe abdominal pain', 'acute abdominal pain']) || hasAll(['abdominal pain', 'vomiting', 'fever'])) {
    conditions.push({ 
      name: 'Acute Appendicitis', 
      probability: '30%', 
      details: 'Severe abdominal pain starting around navel, moving to lower right.' 
    });
    conditions.push({ 
      name: 'Acute Pancreatitis', 
      probability: '25%', 
      details: 'Severe upper abdominal pain radiating to back with vomiting.' 
    });
    conditions.push({ 
      name: 'Bowel Obstruction', 
      probability: '20%', 
      details: 'Severe crampy pain with vomiting and inability to pass stool.' 
    });
    conditions.push({ 
      name: 'Ruptured Ovarian Cyst', 
      probability: '15%', 
      details: 'Sudden severe abdominal pain in female patients.' 
    });
    conditions.push({ 
      name: 'Acute Gastroenteritis', 
      probability: '10%', 
      details: 'Severe GI infection with pain, vomiting, and diarrhea.' 
    });
    urgency = 'high';
    suggested_specialty = 'Emergency Medicine/General Surgery';
    recommendations = 'Seek immediate medical attention. Severe abdominal pain can indicate surgical emergency.';
  }
  // Respiratory infections
  else if (has(['fever', 'cough']) || has(['sore throat', 'runny nose', 'congestion'])) {
    conditions.push({ 
      name: 'Viral Respiratory Infection/Common Cold', 
      probability: '50%', 
      details: 'Upper respiratory tract infection with fever and cough.' 
    });
    conditions.push({ 
      name: 'Influenza', 
      probability: '30%', 
      details: 'Flu virus causing fever, cough, and body aches.' 
    });
    conditions.push({ 
      name: 'Acute Bronchitis', 
      probability: '12%', 
      details: 'Lower respiratory infection with persistent cough.' 
    });
    conditions.push({ 
      name: 'Bacterial Respiratory Infection', 
      probability: '5%', 
      details: 'Bacterial infection requiring potential antibiotic treatment.' 
    });
    conditions.push({ 
      name: 'COVID-19', 
      probability: '3%', 
      details: 'Respiratory illness with fever, cough, and systemic symptoms.' 
    });
    urgency = has(['high fever', 'difficulty breathing', 'confusion']) ? 'high' : 'medium';
    suggested_specialty = 'General Practitioner/Pulmonologist';
    recommendations = 'Rest, stay hydrated, use fever-reducing medication. Monitor temperature. See doctor if symptoms worsen or persist beyond 7-10 days.';
  }
  // Gastrointestinal infections
  else if (has(['nausea', 'vomiting', 'diarrhea']) || has(['stomach pain', 'cramps'])) {
    conditions.push({ 
      name: 'Viral Gastroenteritis (Stomach Flu)', 
      probability: '50%', 
      details: 'Viral infection of digestive tract with nausea and diarrhea.' 
    });
    conditions.push({ 
      name: 'Food Poisoning', 
      probability: '25%', 
      details: 'Contaminated food causing acute GI symptoms.' 
    });
    conditions.push({ 
      name: 'Bacterial Gastroenteritis', 
      probability: '12%', 
      details: 'Bacterial infection requiring potential antibiotic treatment.' 
    });
    conditions.push({ 
      name: 'Peptic Ulcer Disease', 
      probability: '8%', 
      details: 'Stomach lining erosion causing pain and nausea.' 
    });
    conditions.push({ 
      name: 'IBS (Irritable Bowel Syndrome)', 
      probability: '5%', 
      details: 'Chronic functional GI disorder with variable symptoms.' 
    });
    urgency = has(['severe abdominal pain', 'blood in stool', 'uncontrollable vomiting']) ? 'high' : 'medium';
    suggested_specialty = 'Gastroenterologist';
    recommendations = 'Stay hydrated with electrolyte solutions. Rest and eat light foods. Avoid dairy and fatty foods. Seek care if symptoms persist beyond 3 days.';
  }
  // Neurological issues
  else if (has(['headache']) || has(['dizziness', 'vertigo'])) {
    conditions.push({ 
      name: 'Migraine Headache', 
      probability: '40%', 
      details: 'Intense headache often with light sensitivity and nausea.' 
    });
    conditions.push({ 
      name: 'Tension Headache', 
      probability: '35%', 
      details: 'Stress-related headache with muscle tension.' 
    });
    conditions.push({ 
      name: 'Sinusitis', 
      probability: '15%', 
      details: 'Sinus inflammation causing referred headache and pressure.' 
    });
    conditions.push({ 
      name: 'Benign Positional Vertigo', 
      probability: '7%', 
      details: 'Inner ear disorder causing spinning sensation.' 
    });
    conditions.push({ 
      name: 'Medication Side Effect', 
      probability: '3%', 
      details: 'Certain medications can cause headaches or dizziness.' 
    });
    urgency = isSevere() ? 'high' : 'medium';
    suggested_specialty = 'Neurologist';
    recommendations = 'Rest in quiet, dark room. Use over-the-counter pain relief. Apply warm compress. See neurologist if headaches become frequent.';
  }
  // Skin conditions
  else if (has(['rash', 'itching']) || has(['hives', 'eczema', 'dermatitis'])) {
    conditions.push({ 
      name: 'Allergic Dermatitis', 
      probability: '40%', 
      details: 'Allergic reaction causing rash and itching.' 
    });
    conditions.push({ 
      name: 'Contact Dermatitis', 
      probability: '25%', 
      details: 'Reaction to irritant causing localized rash.' 
    });
    conditions.push({ 
      name: 'Eczema', 
      probability: '20%', 
      details: 'Chronic inflammatory skin condition.' 
    });
    conditions.push({ 
      name: 'Urticaria (Hives)', 
      probability: '10%', 
      details: 'Allergic reaction causing raised itchy welts.' 
    });
    conditions.push({ 
      name: 'Fungal Infection', 
      probability: '5%', 
      details: 'Fungal rash often in warm, moist areas.' 
    });
    urgency = has(['swelling of face/lips', 'difficulty breathing']) ? 'emergency' : 'medium';
    suggested_specialty = 'Dermatologist';
    recommendations = 'Avoid triggers, use hypoallergenic products, apply soothing lotion. See dermatologist if rash spreads or persists.';
  }
  // Joint and muscle pain
  else if (has(['joint pain', 'muscle pain']) || has(['arthritis', 'arthralgia'])) {
    conditions.push({ 
      name: 'Osteoarthritis', 
      probability: '35%', 
      details: 'Degenerative joint disease causing pain and stiffness.' 
    });
    conditions.push({ 
      name: 'Rheumatoid Arthritis', 
      probability: '20%', 
      details: 'Autoimmune joint inflammation typically symmetric.' 
    });
    conditions.push({ 
      name: 'Muscle Strain/Sprain', 
      probability: '25%', 
      details: 'Overuse or injury causing muscle or ligament damage.' 
    });
    conditions.push({ 
      name: 'Gout', 
      probability: '12%', 
      details: 'Crystalline arthritis often affecting big toe.' 
    });
    conditions.push({ 
      name: 'Fibromyalgia', 
      probability: '8%', 
      details: 'Widespread musculoskeletal pain syndrome.' 
    });
    urgency = isSevere() ? 'high' : 'medium';
    suggested_specialty = 'Rheumatologist/Orthopedist';
    recommendations = 'Rest affected area, ice for acute injuries, heat for chronic pain. Physical therapy recommended. See specialist for proper diagnosis.';
  }
  // Systemic/constitutional symptoms
  else if (has(['fatigue', 'weakness']) || isChronic()) {
    conditions.push({ 
      name: 'Chronic Fatigue Syndrome/ME', 
      probability: '25%', 
      details: 'Persistent debilitating fatigue affecting daily life.' 
    });
    conditions.push({ 
      name: 'Anemia', 
      probability: '20%', 
      details: 'Low red blood cell count causing fatigue and weakness.' 
    });
    conditions.push({ 
      name: 'Thyroid Disorder', 
      probability: '20%', 
      details: 'Hypothyroidism or hyperthyroidism affecting metabolism.' 
    });
    conditions.push({ 
      name: 'Depression/Mental Health Issue', 
      probability: '18%', 
      details: 'Mental health conditions manifesting as physical fatigue.' 
    });
    conditions.push({ 
      name: 'Chronic Infection', 
      probability: '12%', 
      details: 'Ongoing infection draining energy reserves.' 
    });
    conditions.push({ 
      name: 'Sleep Disorder', 
      probability: '5%', 
      details: 'Poor sleep quality causing daytime fatigue.' 
    });
    urgency = 'medium';
    suggested_specialty = 'General Practitioner';
    recommendations = 'Get comprehensive blood work including thyroid, CBC, and metabolic panel. Ensure adequate sleep and nutrition. Consult GP if fatigue persists.';
  }
  // Anxiety/panic symptoms
  else if (has(['anxiety', 'panic']) || has(['rapid heartbeat', 'sweating', 'nervousness'])) {
    conditions.push({ 
      name: 'Anxiety Disorder', 
      probability: '50%', 
      details: 'Persistent worry with physical anxiety manifestations.' 
    });
    conditions.push({ 
      name: 'Panic Disorder', 
      probability: '25%', 
      details: 'Sudden severe anxiety attacks with physical symptoms.' 
    });
    conditions.push({ 
      name: 'Generalized Anxiety Disorder', 
      probability: '15%', 
      details: 'Chronic pervasive anxiety.' 
    });
    conditions.push({ 
      name: 'Thyroid Disorder', 
      probability: '7%', 
      details: 'Hyperthyroidism can mimic anxiety symptoms.' 
    });
    conditions.push({ 
      name: 'Caffeine Sensitivity', 
      probability: '3%', 
      details: 'Excessive caffeine causing anxiety-like symptoms.' 
    });
    urgency = 'medium';
    suggested_specialty = 'Psychiatrist/Psychologist';
    recommendations = 'Practice relaxation techniques, meditation, and exercise. Consider therapy. Limit caffeine. Consult mental health professional for proper diagnosis.';
  }
  // Default comprehensive analysis for any other symptoms
  else {
    conditions.push({ 
      name: 'General Health Assessment Required', 
      probability: '100%', 
      details: `The symptoms reported (${symptoms.join(', ')}) require a comprehensive medical evaluation to determine underlying cause.` 
    });
    conditions.push({ 
      name: 'Primary Care Consultation Recommended', 
      probability: '100%', 
      details: 'Complete history, physical examination, and appropriate diagnostic tests needed.' 
    });
    urgency = isSevere() ? 'high' : (isChronic() ? 'medium' : 'medium');
    suggested_specialty = 'General Practitioner';
    recommendations = 'Schedule a thorough evaluation with your healthcare provider. Bring a list of your symptoms, any recent changes, and all current medications.';
  }

  return {
    possible_conditions: conditions,
    urgency,
    suggested_specialty,
    recommendations,
    generated_related_symptoms: allRelatedSymptoms.slice(0, 15), // Return up to 15 related symptoms
    disclaimer: 'IMPORTANT: This AI analysis is for educational purposes only. It is NOT a substitute for professional medical diagnosis or treatment. Always consult with a qualified healthcare provider for medical advice, diagnosis, and treatment recommendations. In case of emergency, call emergency services immediately.'
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
 * Comprehensive unbounded symptom analysis with AI enhancement
 */
const analyzeSymptoms = async (symptoms) => {
  try {
    // Validate input
    if (!symptoms || !Array.isArray(symptoms) || symptoms.length === 0) {
      return buildFallbackResponse(['no symptoms reported']);
    }

    // Generate related symptoms for comprehensive analysis
    const relatedSymptoms = generateRelatedSymptoms(symptoms);

    // If OpenAI is available, use enhanced prompt
    if (hasValidOpenAIKey()) {
      console.log('Using OpenAI for comprehensive symptom analysis');
      
      const prompt = `You are a comprehensive medical AI assistant providing educational health information. 

PATIENT SYMPTOMS REPORTED:
${symptoms.map((s, i) => `${i + 1}. ${s}`).join('\n')}

POTENTIALLY RELATED SYMPTOMS (based on medical knowledge):
${relatedSymptoms.slice(0, 15).map(s => `• ${s}`).join('\n')}

ANALYSIS REQUIREMENTS:
1. Analyze the reported symptoms comprehensively
2. Consider all possible medical explanations, from common to rare
3. Generate 5-7 most likely conditions with probability estimates
4. List symptoms the patient might develop
5. Assess urgency level: low, medium, high, or emergency
6. Recommend appropriate medical specialty
7. Provide detailed recommendations for patient care
8. Include prominent medical disclaimer

IMPORTANT: Consider symptom combinations, duration indicators (if present), severity modifiers (if present), and pattern matching.

Respond ONLY with valid JSON (no markdown, no extra text):
{
  "possible_conditions": [
    {"name": "Condition Name", "probability": "XX%", "details": "Detailed explanation of how symptoms correlate."},
    ...
  ],
  "related_symptoms": ["symptom1", "symptom2", ...],
  "urgency": "low|medium|high|emergency",
  "suggested_specialty": "Medical Specialty",
  "recommendations": "Detailed patient care recommendations",
  "disclaimer": "Medical disclaimer"
}`;

      const completion = await openai.chat.completions.create({
        model: 'gpt-3.5-turbo',
        messages: [
          {
            role: 'system',
            content: 'You are a medical AI assistant. Provide comprehensive analysis. Always respond with ONLY valid JSON in the specified format. No markdown, no explanations, no extra text.'
          },
          {
            role: 'user',
            content: prompt
          }
        ],
        temperature: 0.6,
        max_tokens: 1200
      });

      const response = completion.choices?.[0]?.message?.content?.trim();
      let parsedResponse = null;
      
      if (response) {
        parsedResponse = extractJSON(response);
      }

      if (parsedResponse && parsedResponse.possible_conditions) {
        return {
          possible_conditions: parsedResponse.possible_conditions,
          generated_related_symptoms: relatedSymptoms.slice(0, 15),
          urgency: parsedResponse.urgency || 'medium',
          suggested_specialty: parsedResponse.suggested_specialty || 'General Practitioner',
          recommendations: parsedResponse.recommendations || 'Consult a doctor for proper evaluation.',
          disclaimer: parsedResponse.disclaimer || 'This is AI-generated information for educational purposes only and should not replace professional medical advice.'
        };
      }
    }

    // Use advanced local analyzer as fallback or primary
    console.log('Using comprehensive local analyzer for symptom analysis');
    const fallbackResult = buildFallbackResponse(symptoms);
    
    return {
      ...fallbackResult,
      generated_related_symptoms: relatedSymptoms.slice(0, 15)
    };

  } catch (error) {
    console.error('AI Analysis Error:', error);
    // Return safe fallback on error
    return buildFallbackResponse(symptoms);
  }
};

module.exports = {
  analyzeSymptoms,
  generateRelatedSymptoms
};
