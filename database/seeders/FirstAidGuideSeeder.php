<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FirstAidGuide;

class FirstAidGuideSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $guides = [
            // Critical Emergency Conditions
            [
                'title' => 'Heart Attack',
                'description' => 'Sudden blockage of blood flow to the heart muscle',
                'category' => 'emergency',
                'severity' => 'critical',
                'symptoms' => json_encode(['Chest pain or pressure', 'Shortness of breath', 'Pain in arm/neck/jaw', 'Cold sweat', 'Nausea', 'Lightheadedness']),
                'steps' => json_encode(['Call emergency services immediately', 'Keep person calm and still', 'Give aspirin if available', 'Loosen tight clothing', 'Monitor breathing and pulse']),
                'do_tips' => json_encode(['Call 912/112/999 immediately', 'Help person sit or lie down comfortably', 'Give 300mg aspirin to chew if no allergy', 'Stay with person until help arrives']),
                'dont_tips' => json_encode(['Do not give food or drink', 'Do not leave person alone', 'Do not ignore symptoms', 'Do not delay calling for help']),
                'required_items' => json_encode(['Phone', 'Aspirin', 'Watch/clock']),
                'target_body_part' => 'chest',
                'icon' => 'fa-heart',
                'region' => 'global',
                'sort_order' => 1
            ],
            [
                'title' => 'Stroke',
                'description' => 'Brain attack caused by interrupted blood flow to brain',
                'category' => 'emergency',
                'severity' => 'critical',
                'symptoms' => json_encode(['Face drooping', 'Arm weakness', 'Speech difficulty', 'Sudden confusion', 'Vision problems', 'Severe headache']),
                'steps' => json_encode(['Recognize FAST signs', 'Call emergency services immediately', 'Note time symptoms started', 'Keep person comfortable', 'Do not give food/drink']),
                'do_tips' => json_encode(['Call emergency services immediately', 'Note exact time symptoms started', 'Keep person on side if vomiting', 'Stay calm and reassuring']),
                'dont_tips' => json_encode(['Do not give aspirin', 'Do not give food or drink', 'Do not drive to hospital', 'Do not ignore symptoms']),
                'required_items' => json_encode(['Phone', 'Watch']),
                'target_body_part' => 'head',
                'icon' => 'fa-brain',
                'region' => 'global',
                'sort_order' => 2
            ],
            [
                'title' => 'Choking',
                'description' => 'Airway obstruction preventing breathing',
                'category' => 'emergency',
                'severity' => 'critical',
                'symptoms' => json_encode(['Cannot speak or breathe', 'Clutching throat', 'Blue lips/fingers', 'Loss of consciousness']),
                'steps' => json_encode(['Ask if person can speak', 'Give 5 back blows', 'Give 5 abdominal thrusts', 'Alternate until clear', 'Call emergency services if unsuccessful']),
                'do_tips' => json_encode(['Stand behind person', 'Use heel of hand for back blows', 'Use proper thrust technique', 'Call for help if unsuccessful']),
                'dont_tips' => json_encode(['Do not slap back if person can cough', 'Do not give water', 'Do not panic', 'Do not blindly sweep mouth']),
                'required_items' => json_encode(['Phone']),
                'target_body_part' => 'chest',
                'icon' => 'fa-wind',
                'region' => 'global',
                'sort_order' => 3
            ],
            [
                'title' => 'Cardiac Arrest',
                'description' => 'Sudden stopping of heart function',
                'category' => 'emergency',
                'severity' => 'critical',
                'symptoms' => json_encode(['No pulse', 'Not breathing', 'Loss of consciousness', 'No response']),
                'steps' => json_encode(['Call emergency services', 'Start CPR immediately', 'Push hard and fast', 'Use AED if available', 'Continue until help arrives']),
                'do_tips' => json_encode(['Call 912 immediately', 'Start CPR: 30 compressions to 2 breaths', 'Push at least 2 inches deep', 'Use AED as soon as possible']),
                'dont_tips' => json_encode(['Do not delay CPR', 'Do not stop CPR once started', 'Do not worry about breaking ribs', 'Do not give up']),
                'required_items' => json_encode(['Phone', 'AED if available']),
                'target_body_part' => 'chest',
                'icon' => 'fa-heart-pulse',
                'region' => 'global',
                'sort_order' => 4
            ],
            
            // Urgent Conditions
            [
                'title' => 'Severe Bleeding',
                'description' => 'Heavy blood loss that can be life-threatening',
                'category' => 'injury',
                'severity' => 'urgent',
                'symptoms' => json_encode(['Bright red blood spurting', 'Large amount of blood', 'Weakness, dizziness', 'Pale skin', 'Rapid pulse']),
                'steps' => json_encode(['Apply direct pressure', 'Elevate wound if possible', 'Use pressure dressing', 'Apply tourniquet if life-threatening', 'Call emergency services']),
                'do_tips' => json_encode(['Apply firm, steady pressure', 'Use clean cloth or bandage', 'Elevate above heart level', 'Call for help if severe']),
                'dont_tips' => json_encode(['Do not remove embedded objects', 'Do not use tourniquet unless necessary', 'Do not give food/drink', 'Do not ignore signs of shock']),
                'required_items' => json_encode(['Clean cloth', 'Bandages', 'Gloves', 'Tourniquet']),
                'icon' => 'fa-droplet',
                'region' => 'global',
                'sort_order' => 5
            ],
            [
                'title' => 'Major Burns',
                'description' => 'Serious burns affecting large areas or critical locations',
                'category' => 'injury',
                'severity' => 'urgent',
                'symptoms' => json_encode(['Large burn area', 'Deep blisters', 'Charred skin', 'Difficulty breathing', 'Shock symptoms']),
                'steps' => json_encode(['Call emergency services', 'Cool burn with water 20 minutes', 'Cover with clean cloth', 'Treat for shock', 'Do not apply creams']),
                'do_tips' => json_encode(['Cool with lukewarm water', 'Remove jewelry/clothing near burn', 'Cover with sterile dressing', 'Monitor for shock']),
                'dont_tips' => json_encode(['Do not use ice', 'Do not break blisters', 'Do not apply butter/ointments', 'Do not use fluffy cotton']),
                'required_items' => json_encode(['Cool water', 'Clean cloth', 'Blanket', 'Phone']),
                'icon' => 'fa-fire',
                'region' => 'global',
                'sort_order' => 6
            ],
            [
                'title' => 'Fractures',
                'description' => 'Broken bones requiring immediate attention',
                'category' => 'injury',
                'severity' => 'urgent',
                'symptoms' => json_encode(['Severe pain', 'Swelling', 'Deformity', 'Inability to move limb', 'Bruising']),
                'steps' => json_encode(['Immobilize the area', 'Apply cold pack', 'Elevate if possible', 'Seek medical attention', 'Do not try to straighten']),
                'do_tips' => json_encode(['Keep person still', 'Apply cold to reduce swelling', 'Use splint if available', 'Call for medical help']),
                'dont_tips' => json_encode(['Do not try to straighten bone', 'Do not move person unless necessary', 'Do not apply heat', 'Do not give food/drink']),
                'required_items' => json_encode(['Splint', 'Cold pack', 'Bandages', 'Phone']),
                'icon' => 'fa-bone',
                'region' => 'global',
                'sort_order' => 7
            ],
            
            // Moderate Conditions
            [
                'title' => 'Asthma Attack',
                'description' => 'Sudden worsening of asthma symptoms',
                'category' => 'illness',
                'severity' => 'moderate',
                'symptoms' => json_encode(['Wheezing', 'Shortness of breath', 'Chest tightness', 'Coughing', 'Difficulty speaking']),
                'steps' => json_encode(['Help person sit upright', 'Use rescue inhaler', 'Stay calm', 'Loosen clothing', 'Call for help if no improvement']),
                'do_tips' => json_encode(['Sit upright, lean forward', 'Use rescue inhaler with spacer', 'Stay calm and reassure', 'Call emergency services if no improvement']),
                'dont_tips' => json_encode(['Do not lie person flat', 'Do not panic', 'Do not ignore worsening symptoms', 'Do not give food/drink']),
                'required_items' => json_encode(['Rescue inhaler', 'Spacer', 'Phone']),
                'icon' => 'fa-lungs',
                'region' => 'global',
                'sort_order' => 8
            ],
            [
                'title' => 'Diabetic Emergency',
                'description' => 'Extremely high or low blood sugar levels',
                'category' => 'illness',
                'severity' => 'moderate',
                'symptoms' => json_encode(['Confusion', 'Dizziness', 'Sweating', 'Rapid heartbeat', 'Loss of consciousness']),
                'steps' => json_encode(['Check for medical alert', 'Give sugar if conscious', 'Call emergency services', 'Monitor breathing', 'Do not give insulin']),
                'do_tips' => json_encode(['Give sugar/candy if conscious', 'Call emergency services', 'Monitor vital signs', 'Stay with person']),
                'dont_tips' => json_encode(['Do not give insulin', 'Do not force food if unconscious', 'Do not leave person alone', 'Do not ignore symptoms']),
                'required_items' => json_encode(['Sugar/candy', 'Phone', 'Glucose meter']),
                'icon' => 'fa-vial',
                'region' => 'global',
                'sort_order' => 9
            ],
            
            // Environmental Emergencies
            [
                'title' => 'Heat Stroke',
                'description' => 'Severe heat illness with body temperature over 104°F',
                'category' => 'environmental',
                'severity' => 'critical',
                'symptoms' => json_encode(['High body temperature', 'Hot dry skin', 'Confusion', 'Rapid pulse', 'Headache', 'Loss of consciousness']),
                'steps' => json_encode(['Call emergency services', 'Move to cool place', 'Remove excess clothing', 'Cool with water/fans', 'Monitor consciousness']),
                'do_tips' => json_encode(['Call 912 immediately', 'Cool rapidly with water/fans', 'Apply ice packs to neck/armpits', 'Monitor temperature']),
                'dont_tips' => json_encode(['Do not give alcohol to rub', 'Do not give fever medication', 'Do not leave person alone', 'Do not use ice bath']),
                'required_items' => json_encode(['Phone', 'Water', 'Ice packs', 'Fan']),
                'icon' => 'fa-temperature-high',
                'region' => 'global',
                'sort_order' => 10
            ],
            [
                'title' => 'Hypothermia',
                'description' => 'Dangerously low body temperature below 95°F',
                'category' => 'environmental',
                'severity' => 'urgent',
                'symptoms' => json_encode(['Shivering', 'Confusion', 'Slurred speech', 'Drowsiness', 'Slow breathing', 'Loss of coordination']),
                'steps' => json_encode(['Call emergency services', 'Move to warm place', 'Remove wet clothing', 'Warm with blankets', 'Give warm fluids if conscious']),
                'do_tips' => json_encode(['Remove wet clothes', 'Warm gradually', 'Use blankets/sleeping bags', 'Give warm sweet drinks if conscious']),
                'dont_tips' => json_encode(['Do not apply direct heat', 'Do not massage limbs', 'Do not give alcohol', 'Do not ignore symptoms']),
                'required_items' => json_encode(['Blankets', 'Warm drinks', 'Phone', 'Thermometer']),
                'icon' => 'fa-temperature-low',
                'region' => 'global',
                'sort_order' => 11
            ],
            
            // Poisoning
            [
                'title' => 'Poisoning',
                'description' => 'Exposure to toxic substances',
                'category' => 'emergency',
                'severity' => 'urgent',
                'symptoms' => json_encode(['Nausea/vomiting', 'Abdominal pain', 'Drowsiness', 'Confusion', 'Difficulty breathing', 'Burns around mouth']),
                'steps' => json_encode(['Call poison control', 'Do not induce vomiting', 'Save substance container', 'Follow medical advice', 'Go to emergency room']),
                'do_tips' => json_encode(['Call poison control immediately', 'Save container/bottle', 'Follow professional advice', 'Go to ER if instructed']),
                'dont_tips' => json_encode(['Do not induce vomiting unless told', 'Do not give food/drink', 'Do not delay calling for help', 'Do not ignore symptoms']),
                'required_items' => json_encode(['Phone', 'Poison container', 'Water']),
                'icon' => 'fa-skull-crossbones',
                'region' => 'global',
                'sort_order' => 12
            ],
            
            // Regional Specific Guides
            [
                'title' => 'Snake Bite',
                'description' => 'Venomous snake bite requiring urgent care',
                'category' => 'injury',
                'severity' => 'urgent',
                'symptoms' => json_encode(['Puncture marks', 'Swelling', 'Pain', 'Bruising', 'Nausea', 'Difficulty breathing']),
                'steps' => json_encode(['Call emergency services', 'Keep calm and still', 'Remove tight clothing', 'Note snake appearance', 'Do not cut or suck']),
                'do_tips' => json_encode(['Stay calm and still', 'Remove jewelry/clothing', 'Note snake color/size', 'Keep bite below heart level']),
                'dont_tips' => json_encode(['Do not cut wound', 'Do not suck venom', 'Do not apply tourniquet', 'Do not apply ice']),
                'required_items' => json_encode(['Phone', 'Bandage', 'Camera for photo']),
                'icon' => 'fa-dragon',
                'region' => 'africa',
                'sort_order' => 13
            ],
            [
                'title' => 'Malaria Symptoms',
                'description' => 'Recognition of malaria requiring urgent treatment',
                'category' => 'illness',
                'severity' => 'urgent',
                'symptoms' => json_encode(['High fever', 'Chills', 'Headache', 'Muscle pain', 'Fatigue', 'Sweating']),
                'steps' => json_encode(['Seek medical care immediately', 'Take temperature', 'Note symptom pattern', 'Stay hydrated', 'Take prescribed medication']),
                'do_tips' => json_encode(['Seek medical care urgently', 'Rest and stay hydrated', 'Take antimalarial medication if prescribed', 'Use mosquito nets']),
                'dont_tips' => json_encode(['Do not delay treatment', 'Do not self-medicate without diagnosis', 'Do not ignore recurring fever', 'Do not travel alone']),
                'required_items' => json_encode(['Phone', 'Thermometer', 'Medication', 'Mosquito net']),
                'icon' => 'fa-mosquito',
                'region' => 'africa',
                'sort_order' => 14
            ],
            [
                'title' => 'Dengue Fever',
                'description' => 'Mosquito-borne viral infection',
                'category' => 'illness',
                'severity' => 'moderate',
                'symptoms' => json_encode(['High fever', 'Severe headache', 'Joint/muscle pain', 'Rash', 'Bleeding gums', 'Fatigue']),
                'steps' => json_encode(['Seek medical evaluation', 'Rest and hydrate', 'Take fever medication', 'Monitor for warning signs', 'Prevent mosquito bites']),
                'do_tips' => json_encode(['Get medical diagnosis', 'Rest and drink fluids', 'Take acetaminophen for fever', 'Use mosquito repellent']),
                'dont_tips' => json_encode(['Do not take aspirin/ibuprofen', 'Do not ignore warning signs', 'Do not spread infection', 'Do not delay medical care']),
                'required_items' => json_encode(['Phone', 'Acetaminophen', 'Fluids', 'Mosquito repellent']),
                'icon' => 'fa-virus',
                'region' => 'asia',
                'sort_order' => 15
            ],
            [
                'title' => 'Altitude Sickness',
                'description' => 'Illness from rapid ascent to high altitude',
                'category' => 'environmental',
                'severity' => 'moderate',
                'symptoms' => json_encode(['Headache', 'Nausea', 'Dizziness', 'Shortness of breath', 'Fatigue', 'Loss of appetite']),
                'steps' => json_encode(['Stop ascending', 'Rest and hydrate', 'Descend if symptoms worsen', 'Take medication if prescribed', 'Monitor for severe symptoms']),
                'do_tips' => json_encode(['Stop going up immediately', 'Descend if severe symptoms', 'Drink plenty of water', 'Take acetazolamide if prescribed']),
                'dont_tips' => json_encode(['Do not continue ascending', 'Do not ignore symptoms', 'Do not drink alcohol', 'Do not overexert']),
                'required_items' => json_encode(['Water', 'Acetazolamide', 'Oxygen if available', 'Phone']),
                'icon' => 'fa-mountain',
                'region' => 'americas',
                'sort_order' => 16
            ],
            
            // Common Minor Issues
            [
                'title' => 'Minor Cuts and Scrapes',
                'description' => 'Small skin injuries requiring basic care',
                'category' => 'injury',
                'severity' => 'minor',
                'symptoms' => json_encode(['Small break in skin', 'Minimal bleeding', 'Slight pain', 'No deep tissue damage']),
                'steps' => json_encode(['Clean with water', 'Apply antiseptic', 'Cover with bandage', 'Monitor for infection', 'Change dressing daily']),
                'do_tips' => json_encode(['Clean with clean water', 'Apply antiseptic ointment', 'Cover with sterile bandage', 'Keep dry and clean']),
                'dont_tips' => json_encode(['Do not use hydrogen peroxide', 'Do not blow on wound', 'Do not ignore signs of infection', 'Do not pick at scabs']),
                'required_items' => json_encode(['Clean water', 'Antiseptic', 'Bandage', 'Gloves']),
                'icon' => 'fa-bandage',
                'region' => 'global',
                'sort_order' => 17
            ],
            [
                'title' => 'Nosebleed',
                'description' => 'Bleeding from the nose',
                'category' => 'injury',
                'severity' => 'minor',
                'symptoms' => json_encode(['Blood from nostrils', 'Dripping blood', 'Slight discomfort']),
                'steps' => json_encode(['Sit upright and lean forward', 'Pinch nostrils', 'Hold for 10 minutes', 'Apply cold compress', 'Seek help if continues']),
                'do_tips' => json_encode(['Sit upright, lean forward', 'Pinch soft part of nose', 'Apply cold pack to bridge', 'Breathe through mouth']),
                'dont_tips' => json_encode(['Do not tilt head back', 'Do not blow nose', 'Do not pick nose', 'Do not stuff tissue deep in nose']),
                'required_items' => json_encode(['Cold pack', 'Tissue', 'Clock']),
                'icon' => 'fa-handkerchief',
                'region' => 'global',
                'sort_order' => 18
            ],
            [
                'title' => 'Insect Stings',
                'description' => 'Painful stings from bees, wasps, ants',
                'category' => 'injury',
                'severity' => 'minor',
                'symptoms' => json_encode(['Sharp pain', 'Swelling', 'Redness', 'Itching', 'Small welt']),
                'steps' => json_encode(['Remove stinger if present', 'Clean area', 'Apply cold pack', 'Take antihistamine', 'Monitor for allergic reaction']),
                'do_tips' => json_encode(['Scrape out stinger sideways', 'Wash with soap and water', 'Apply ice pack', 'Take oral antihistamine']),
                'dont_tips' => json_encode(['Do not squeeze stinger', 'Do not apply heat', 'Do not ignore allergic symptoms', 'Do not scratch']),
                'required_items' => json_encode(['Credit card', 'Soap', 'Ice pack', 'Antihistamine']),
                'icon' => 'fa-bug',
                'region' => 'global',
                'sort_order' => 19
            ],
            
            // Specialized Guides
            [
                'title' => 'CPR for Adults',
                'description' => 'Cardiopulmonary resuscitation technique',
                'category' => 'emergency',
                'severity' => 'critical',
                'symptoms' => json_encode(['Unconscious', 'No breathing', 'No pulse']),
                'steps' => json_encode(['Check responsiveness', 'Call emergency services', 'Open airway', 'Check breathing', 'Start chest compressions', 'Give rescue breaths']),
                'do_tips' => json_encode(['Push hard and fast in center of chest', 'Allow full recoil between compressions', 'Minimize interruptions', 'Use 30:2 ratio']),
                'dont_tips' => json_encode(['Do not bend elbows', 'Do not pause compressions', 'Do not worry about breaking ribs', 'Do not give up']),
                'required_items' => json_encode(['Phone', 'Protective barrier', 'AED if available']),
                'icon' => 'fa-heart-circle-check',
                'region' => 'global',
                'sort_order' => 20
            ],
            [
                'title' => 'CPR for Children',
                'description' => 'Modified CPR for children under 8',
                'category' => 'emergency',
                'severity' => 'critical',
                'symptoms' => json_encode(['Unconscious', 'No breathing', 'No pulse']),
                'steps' => json_encode(['Check responsiveness', 'Call emergency services', 'Use heel of one hand', 'Compress 1/3 depth of chest', 'Give smaller breaths']),
                'do_tips' => json_encode(['Use one hand for compressions', 'Press about 2 inches deep', 'Give gentle breaths', 'Use 30:2 ratio']),
                'dont_tips' => json_encode(['Do not use two hands', 'Do not compress too hard', 'Do not give full breaths', 'Do not delay starting']),
                'required_items' => json_encode(['Phone', 'Protective barrier']),
                'icon' => 'fa-child',
                'region' => 'global',
                'sort_order' => 21
            ],
            [
                'title' => 'Seizure First Aid',
                'description' => 'Help during epileptic seizure',
                'category' => 'illness',
                'severity' => 'urgent',
                'symptoms' => json_encode(['Convulsions', 'Loss of consciousness', 'Uncontrolled movements', 'Drooling', 'Confusion after']),
                'steps' => json_encode(['Protect from injury', 'Time seizure', 'Place on side', 'Do not restrain', 'Call emergency services if prolonged']),
                'do_tips' => json_encode(['Clear area of dangerous objects', 'Place something soft under head', 'Turn person on side', 'Stay with person']),
                'dont_tips' => json_encode(['Do not restrain person', 'Do not put anything in mouth', 'Do not give food/drink', 'Do not try to stop movements']),
                'required_items' => json_encode(['Watch', 'Soft cushion', 'Phone']),
                'icon' => 'fa-brain',
                'region' => 'global',
                'sort_order' => 22
            ],
            [
                'title' => 'Eye Injury',
                'description' => 'Various types of eye trauma',
                'category' => 'injury',
                'severity' => 'urgent',
                'symptoms' => json_encode(['Eye pain', 'Vision changes', 'Redness', 'Tearing', 'Foreign body sensation']),
                'steps' => json_encode(['Do not rub eye', 'Flush with water', 'Cover both eyes', 'Seek medical care', 'Do not remove embedded objects']),
                'do_tips' => json_encode(['Flush with clean water', 'Cover with clean cloth', 'Keep person calm', 'Seek medical attention']),
                'dont_tips' => json_encode(['Do not rub eye', 'Do not remove embedded objects', 'Do not apply pressure', 'Do not use eye drops']),
                'required_items' => json_encode(['Clean water', 'Eye patch', 'Phone']),
                'icon' => 'fa-eye',
                'region' => 'global',
                'sort_order' => 23
            ],
            [
                'title' => 'Electric Shock',
                'description' => 'Injury from electrical current',
                'category' => 'emergency',
                'severity' => 'urgent',
                'symptoms' => json_encode(['Burns at contact points', 'Muscle pain', 'Confusion', 'Heart rhythm changes', 'Unconsciousness']),
                'steps' => json_encode(['Turn off power source', 'Call emergency services', 'Check breathing/pulse', 'Treat burns', 'Monitor for shock']),
                'do_tips' => json_encode(['Do not touch person until power off', 'Use non-conductive object to move person', 'Call for emergency help', 'Treat any injuries']),
                'dont_tips' => json_encode(['Do not touch person while connected', 'Do not move if neck injury suspected', 'Do not give food/drink', 'Do not delay calling help']),
                'required_items' => json_encode(['Phone', 'Non-conductive object', 'First aid kit']),
                'icon' => 'fa-bolt',
                'region' => 'global',
                'sort_order' => 24
            ],
            [
                'title' => 'Food Poisoning',
                'description' => 'Illness from contaminated food',
                'category' => 'illness',
                'severity' => 'moderate',
                'symptoms' => json_encode(['Nausea', 'Vomiting', 'Diarrhea', 'Stomach cramps', 'Fever', 'Dehydration']),
                'steps' => json_encode(['Rest and hydrate', 'Eat bland foods', 'Avoid dairy/fatty foods', 'Seek medical care if severe', 'Report to health authorities']),
                'do_tips' => json_encode(['Drink clear fluids', 'Start with bland foods', 'Rest completely', 'Monitor for dehydration']),
                'dont_tips' => json_encode(['Do not eat solid food initially', 'Do not take anti-diarrhea medication', 'Do not ignore severe symptoms', 'Do not prepare food for others']),
                'required_items' => json_encode(['Fluids', 'Oral rehydration solution', 'Phone']),
                'icon' => 'fa-utensils',
                'region' => 'global',
                'sort_order' => 25
            ],
            [
                'title' => 'Animal Bite',
                'description' => 'Bite from animals requiring wound care',
                'category' => 'injury',
                'severity' => 'moderate',
                'symptoms' => json_encode(['Puncture wounds', 'Bleeding', 'Swelling', 'Redness', 'Risk of infection']),
                'steps' => json_encode(['Clean wound thoroughly', 'Apply antiseptic', 'Cover with bandage', 'Seek medical care for animal bites', 'Report animal control']),
                'do_tips' => json_encode(['Wash with soap and water', 'Apply pressure to stop bleeding', 'Cover with clean dressing', 'Get medical evaluation']),
                'dont_tips' => json_encode(['Do not ignore animal bites', 'Do not apply tourniquet', 'Do not delay medical care', 'Do not try to catch animal']),
                'required_items' => json_encode(['Soap', 'Water', 'Bandage', 'Phone']),
                'icon' => 'fa-paw',
                'region' => 'global',
                'sort_order' => 26
            ],
            [
                'title' => 'Drowning Rescue',
                'description' => 'Water emergency requiring immediate action',
                'category' => 'emergency',
                'severity' => 'critical',
                'symptoms' => json_encode(['Unconscious in water', 'Not breathing', 'Blue skin', 'No pulse']),
                'steps' => json_encode(['Get person out of water', 'Call emergency services', 'Check breathing', 'Start CPR if needed', 'Keep person warm']),
                'do_tips' => json_encode(['Reach or throw before rowing', 'Start CPR immediately if needed', 'Keep person warm', 'Monitor continuously']),
                'dont_tips' => json_encode(['Do not endanger yourself', 'Do not waste time', 'Do not give up CPR', 'Do not ignore cold water effects']),
                'required_items' => json_encode(['Phone', 'Rescue equipment', 'Blankets', 'CPR mask']),
                'icon' => 'fa-water',
                'region' => 'global',
                'sort_order' => 27
            ],
            [
                'title' => 'Fainting',
                'description' => 'Temporary loss of consciousness',
                'category' => 'illness',
                'severity' => 'minor',
                'symptoms' => json_encode(['Temporary unconsciousness', 'Pale skin', 'Weak pulse', 'Sweating']),
                'steps' => json_encode(['Check for breathing', 'Lay person flat', 'Elevate legs', 'Loosen clothing', 'Monitor recovery']),
                'do_tips' => json_encode(['Lay person on back', 'Elevate legs 12 inches', 'Check for injuries', 'Stay with person']),
                'dont_tips' => json_encode(['Do not slap face', 'Do not throw water', 'Do not prop head up', 'Do not leave person alone']),
                'required_items' => json_encode(['Pillow', 'Phone']),
                'icon' => 'fa-face-dizzy',
                'region' => 'global',
                'sort_order' => 28
            ],
            [
                'title' => 'Hyperventilation',
                'description' => 'Rapid breathing causing imbalance',
                'category' => 'illness',
                'severity' => 'minor',
                'symptoms' => json_encode(['Rapid breathing', 'Dizziness', 'Tingling fingers', 'Chest tightness', 'Anxiety']),
                'steps' => json_encode(['Stay calm', 'Breathe slowly', 'Focus on breathing', 'Use paper bag method', 'Reassure person']),
                'do_tips' => json_encode(['Encourage slow breathing', 'Use paper bag if available', 'Stay calm and reassuring', 'Sit or lie down comfortably']),
                'dont_tips' => json_encode(['Do not panic', 'Do not force breathing', 'Do not leave person alone', 'Do not ignore symptoms']),
                'required_items' => json_encode(['Paper bag', 'Watch']),
                'icon' => 'fa-wind',
                'region' => 'global',
                'sort_order' => 29
            ],
            [
                'title' => 'Splinter Removal',
                'description' => 'Removing foreign objects from skin',
                'category' => 'injury',
                'severity' => 'minor',
                'symptoms' => json_encode(['Foreign object in skin', 'Pain', 'Redness', 'Swelling']),
                'steps' => json_encode(['Clean area', 'Use tweezers', 'Pull in same direction', 'Clean wound', 'Apply bandage']),
                'do_tips' => json_encode(['Clean tweezers with alcohol', 'Pull steadily in direction of entry', 'Clean wound after removal', 'Apply antibiotic ointment']),
                'dont_tips' => json_encode(['Do not squeeze splinter', 'Do not dig with needle', 'Do not ignore deep splinters', 'Do not break splinter']),
                'required_items' => json_encode(['Tweezers', 'Alcohol', 'Antibiotic ointment', 'Bandage']),
                'icon' => 'fa-splinter',
                'region' => 'global',
                'sort_order' => 30
            ]
        ];

        foreach ($guides as $guide) {
            FirstAidGuide::create($guide);
        }
    }
}
