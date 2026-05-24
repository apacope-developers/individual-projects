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

const buildFallbackResponse = (symptoms) => {
  const normalized = symptoms.map(s => s.toLowerCase());
  const conditions = [];
  let urgency = 'medium';
  let suggested_specialty = 'General Practitioner';
  let recommendations = 'Consult a doctor for a proper diagnosis and next steps.';

  const has = (keywords) => keywords.some(keyword => normalized.some(text => text.includes(keyword)));

  if (has(['chest pain', 'shortness of breath', 'pressure in chest', 'tightness'])) {
    conditions.push({ name: 'Angina or heart-related issue', probability: '40%', details: 'Chest pain and breathing difficulty can signal cardiovascular risk.' });
    conditions.push({ name: 'Gastroesophageal reflux disease (GERD)', probability: '30%', details: 'Acid reflux may produce chest discomfort and shortness of breath.' });
    conditions.push({ name: 'Panic or anxiety attack', probability: '30%', details: 'Stress can cause chest tightness, breathlessness, and rapid heart rate.' });
    urgency = 'high';
    suggested_specialty = 'Cardiologist';
    recommendations = 'Seek medical attention promptly, especially if symptoms are severe or worsening.';
  } else if (has(['fever', 'cough', 'sore throat', 'runny nose', 'congestion'])) {
    conditions.push({ name: 'Viral respiratory infection', probability: '45%', details: 'Fever and cough commonly occur with viral infections like colds or flu.' });
    conditions.push({ name: 'Seasonal influenza', probability: '30%', details: 'Flu often causes fever, cough, fatigue, and body aches.' });
    conditions.push({ name: 'Acute bronchitis', probability: '25%', details: 'Bronchitis may cause cough, mucus, and chest discomfort.' });
    urgency = has(['high fever', 'difficulty breathing']) ? 'high' : 'medium';
    suggested_specialty = 'General Practitioner';
    recommendations = 'Rest, hydrate, and seek medical care if symptoms persist or worsen.';
  } else if (has(['headache', 'dizziness', 'sensitivity to light', 'nausea'])) {
    conditions.push({ name: 'Tension headache', probability: '40%', details: 'Tension headaches are common with stress, poor sleep, or dehydration.' });
    conditions.push({ name: 'Migraine', probability: '35%', details: 'Migraines can cause intense head pain, nausea, and light sensitivity.' });
    conditions.push({ name: 'Sinusitis', probability: '25%', details: 'Sinus inflammation may produce headache and facial pressure.' });
    urgency = 'low';
    suggested_specialty = 'Neurologist';
    recommendations = 'Monitor pain levels and consult a doctor if headaches become frequent or severe.';
  } else if (has(['stomach pain', 'nausea', 'vomiting', 'diarrhea'])) {
    conditions.push({ name: 'Gastroenteritis', probability: '45%', details: 'Stomach flu often causes nausea, vomiting, and diarrhea.' });
    conditions.push({ name: 'Food poisoning', probability: '30%', details: 'Contaminated food can lead to abdominal pain and vomiting.' });
    conditions.push({ name: 'Peptic ulcer', probability: '25%', details: 'Stomach discomfort with nausea may indicate an ulcer or gastritis.' });
    urgency = has(['blood in stool', 'severe abdominal pain']) ? 'high' : 'medium';
    suggested_specialty = 'Gastroenterologist';
    recommendations = 'Stay hydrated and see a doctor if symptoms are intense or last more than a day.';
  } else if (has(['skin rash', 'itching', 'redness', 'hives'])) {
    conditions.push({ name: 'Contact dermatitis', probability: '40%', details: 'Skin irritation is often due to contact with allergens or irritants.' });
    conditions.push({ name: 'Allergic reaction', probability: '30%', details: 'Rashes and itching may indicate an allergy.' });
    conditions.push({ name: 'Eczema', probability: '30%', details: 'Chronic skin inflammation can cause redness and persistent irritation.' });
    urgency = 'medium';
    suggested_specialty = 'Dermatologist';
    recommendations = 'Avoid irritants and see a specialist for proper treatment.';
  } else if (has(['joint pain', 'swelling', 'stiffness'])) {
    conditions.push({ name: 'Osteoarthritis', probability: '40%', details: 'Wear-and-tear joint pain is common with stiffness and swelling.' });
    conditions.push({ name: 'Rheumatoid arthritis', probability: '30%', details: 'Autoimmune joint inflammation may cause pain and stiffness.' });
    conditions.push({ name: 'Gout', probability: '30%', details: 'Sudden, intense joint pain often affects the big toe or ankle.' });
    urgency = 'medium';
    suggested_specialty = 'Rheumatologist';
    recommendations = 'Consult a provider for joint evaluation and pain management.';
  } else if (has(['fatigue', 'weakness', 'weight loss'])) {
    conditions.push({ name: 'Chronic fatigue syndrome', probability: '40%', details: 'Persistent tiredness can be a sign of chronic fatigue or stress.' });
    conditions.push({ name: 'Anemia', probability: '30%', details: 'Low blood iron often causes fatigue and weakness.' });
    conditions.push({ name: 'Thyroid disorder', probability: '30%', details: 'Thyroid problems can lead to fatigue and weight changes.' });
    urgency = 'medium';
    suggested_specialty = 'General Practitioner';
    recommendations = 'Get lab work and a medical evaluation to identify underlying causes.';
  } else {
    conditions.push({ name: 'General medical review recommended', probability: '100%', details: 'These symptoms require evaluation by a healthcare provider for accurate diagnosis.' });
    urgency = 'medium';
    suggested_specialty = 'General Practitioner';
    recommendations = 'Book a consultation to review your symptoms in detail and get proper care.';
  }

  return {
    possible_conditions: conditions,
    urgency,
    suggested_specialty,
    recommendations,
    disclaimer: 'This is AI-generated information and should not replace professional medical advice.'
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
 * Analyze symptoms using AI
 */
const analyzeSymptoms = async (symptoms) => {
  try {
    if (!hasValidOpenAIKey()) {
      console.warn('OpenAI API key missing or invalid. Using local symptom estimator fallback.');
      return buildFallbackResponse(symptoms);
    }

    const prompt = `You are a medical AI assistant. Analyze the following symptoms and provide:
1. Possible conditions (3-5 most likely) with estimated probability and a short detail for each.
2. Urgency level: low, medium, high, or emergency.
3. Suggested medical specialty.
4. Clear recommendations for the patient.
5. A short medical disclaimer.

Symptoms:
${symptoms.map((symptom, index) => `${index + 1}. ${symptom}`).join('\n')}

Respond only with valid JSON in this exact format:
{
  "possible_conditions": [
    {"name": "Condition name", "probability": "XX%", "details": "Short detail."},
    ...
  ],
  "urgency": "low|medium|high|emergency",
  "suggested_specialty": "specialty name",
  "recommendations": "Brief recommendations",
  "disclaimer": "Short disclaimer"
}
`;    

    const completion = await openai.chat.completions.create({
      model: 'gpt-3.5-turbo',
      messages: [
        {
          role: 'system',
          content: 'You are a medical assistant. Always produce valid JSON only, with no markdown formatting or extra text.'
        },
        {
          role: 'user',
          content: prompt
        }
      ],
      temperature: 0.4,
      max_tokens: 500
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
  analyzeSymptoms
};
