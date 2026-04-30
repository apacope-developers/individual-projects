<?php

return [

    /*
    |--------------------------------------------------------------------------
    | First Aid Conditions Database
    |--------------------------------------------------------------------------
    | Complete medical first aid reference data.
    | Each condition includes: steps, do's, don'ts, severity, category.
    */
    'conditions' => [
        [
            'id' => 'cardiac-arrest',
            'name' => 'Cardiac Arrest',
            'icon' => 'fa-heart-crack',
            'severity' => 'critical',
            'category' => 'cardiac',
            'summary' => 'Sudden loss of heart function — immediate CPR required.',
            'steps' => [
                'Call 912 immediately',
                'Begin CPR: 30 compressions to 2 breaths',
                'Push hard and fast on center of chest (at least 2 inches deep)',
                'Use AED if available — follow voice prompts',
                'Continue CPR until help arrives or AED advises to stop',
            ],
            'dos' => [
                'Start CPR immediately',
                'Use AED as soon as possible',
                'Keep compressions continuous and minimize pauses',
            ],
            'donts' => [
                'Do not delay CPR to check pulse for more than 10 seconds',
                'Do not stop CPR once started unless AED analyzes or victim recovers',
                'Do not remove AED pads during analysis',
            ],
            'call912' => true,
        ],
        [
            'id' => 'heart-attack',
            'name' => 'Heart Attack',
            'icon' => 'fa-heart',
            'severity' => 'critical',
            'category' => 'cardiac',
            'summary' => 'Chest pain caused by blocked blood flow to the heart muscle.',
            'steps' => [
                'Call 912 immediately',
                'Have the person sit down and rest',
                'Give aspirin (300mg) if not allergic — chew, don\'t swallow whole',
                'Help them take prescribed medication (nitroglycerin) if available',
                'Monitor consciousness and breathing',
                'Be prepared to perform CPR if they become unresponsive',
            ],
            'dos' => [
                'Keep the person calm and still',
                'Loosen tight clothing',
                'Give aspirin if available and no allergy',
            ],
            'donts' => [
                'Do not let them walk around',
                'Do not give food or drink',
                'Do not ignore symptoms even if they improve',
            ],
            'call912' => true,
        ],
        [
            'id' => 'stroke',
            'name' => 'Stroke',
            'icon' => 'fa-brain',
            'severity' => 'critical',
            'category' => 'neurological',
            'summary' => 'Brain damage from interrupted blood supply — act FAST.',
            'steps' => [
                'Remember FAST: Face drooping, Arm weakness, Speech difficulty, Time to call 912',
                'Call 912 immediately — note the time symptoms started',
                'Keep the person comfortable — lay on their side if vomiting',
                'Do NOT give any food, drink, or medication',
                'Monitor breathing and consciousness',
                'Reassure them and keep them calm until help arrives',
            ],
            'dos' => [
                'Note exact time symptoms began',
                'Keep person lying down with head slightly elevated',
                'Place on side if vomiting or unconscious',
            ],
            'donts' => [
                'Do not give food, water, or medication',
                'Do not wait to see if symptoms improve',
                'Do not drive them to hospital — call 912',
            ],
            'call912' => true,
        ],
        [
            'id' => 'choking',
            'name' => 'Choking',
            'icon' => 'fa-lungs',
            'severity' => 'critical',
            'category' => 'breathing',
            'summary' => 'Airway blocked — person cannot breathe, speak, or cough.',
            'steps' => [
                'Ask "Are you choking?" — if they nod, act immediately',
                'Give 5 back blows: bend forward, strike between shoulder blades',
                'Give 5 abdominal thrusts (Heimlich): fist above navel, thrust inward and upward',
                'Alternate 5 back blows and 5 thrusts until blockage clears',
                'If unconscious, lower to ground and begin CPR',
                'Call 912 if blockage does not clear',
            ],
            'dos' => [
                'Encourage coughing if they can still cough',
                'Use back blows and thrusts for adults and children over 1 year',
                'Call 912 if unable to clear the blockage',
            ],
            'donts' => [
                'Do not use abdominal thrusts on infants under 1 year',
                'Do not slap the back while the person is upright',
                'Do not give water — it can make it worse',
            ],
            'call912' => false,
        ],
        [
            'id' => 'severe-bleeding',
            'name' => 'Severe Bleeding',
            'icon' => 'fa-droplet',
            'severity' => 'critical',
            'category' => 'wounds',
            'summary' => 'Heavy blood loss that won\'t stop with normal pressure.',
            'steps' => [
                'Apply firm direct pressure with a clean cloth or hands',
                'Call 912 if bleeding is severe or won\'t stop',
                'Elevate the injured limb above heart level if possible',
                'If blood soaks through, add more cloth on top (don\'t remove)',
                'Apply pressure bandage firmly over the wound',
                'For life-threatening limb bleeding, use a tourniquet 2-3 inches above wound',
                'Note the time tourniquet was applied',
                'Keep person warm and monitor for shock',
            ],
            'dos' => [
                'Apply direct pressure immediately',
                'Use gloves if available',
                'Keep person lying down and warm',
                'Write tourniquet time visibly on patient',
            ],
            'donts' => [
                'Do not remove embedded objects',
                'Do not use tourniquet unless life-threatening',
                'Do not remove first cloth — add more on top',
            ],
            'call912' => true,
        ],
        [
            'id' => 'burns',
            'name' => 'Burns',
            'icon' => 'fa-fire',
            'severity' => 'urgent',
            'category' => 'wounds',
            'summary' => 'Damage to skin from heat, chemicals, electricity, or radiation.',
            'steps' => [
                'Cool the burn under cool running water for 20 minutes',
                'Remove jewelry and clothing near the burn (not if stuck)',
                'Cover with cling film or a clean non-stick dressing',
                'Do NOT apply ice, butter, or any home remedy',
                'For major burns: call 912, do not remove clothing stuck to burn',
                'Treat for shock: lay flat, keep warm, elevate legs if possible',
                'For chemical burns: remove contaminated clothing, flush with water for 20+ min',
            ],
            'dos' => [
                'Cool with running water for at least 20 minutes',
                'Cover with non-stick dressing or cling film',
                'Seek medical help for burns larger than a palm',
            ],
            'donts' => [
                'Do not use ice — it damages tissue further',
                'Do not pop blisters',
                'Do not apply butter, toothpaste, or creams',
            ],
            'call912' => false,
        ],
        [
            'id' => 'fractures',
            'name' => 'Fractures & Sprains',
            'icon' => 'fa-bone',
            'severity' => 'urgent',
            'category' => 'musculoskeletal',
            'summary' => 'Broken bones or injured joints causing pain, swelling, and deformity.',
            'steps' => [
                'Keep the injured area still — do not try to realign the bone',
                'Call 912 if the fracture is open (bone visible) or deformity is severe',
                'Immobilize with a splint: pad above and below the injury',
                'Apply ice pack wrapped in cloth for 15-20 minutes to reduce swelling',
                'Elevate the injured limb if possible',
                'For open fractures: cover wound with sterile dressing',
                'Monitor for shock and keep person warm',
            ],
            'dos' => [
                'Immobilize above and below the injury',
                'Use padding between splint and skin',
                'Keep the person still and calm',
            ],
            'donts' => [
                'Do not try to realign the bone',
                'Do not move the person unless necessary',
                'Do not apply ice directly to skin',
            ],
            'call912' => false,
        ],
        [
            'id' => 'seizures',
            'name' => 'Seizures',
            'icon' => 'fa-bolt',
            'severity' => 'urgent',
            'category' => 'neurological',
            'summary' => 'Uncontrolled electrical activity in the brain causing convulsions.',
            'steps' => [
                'Clear the area of dangerous objects',
                'Do NOT restrain the person or put anything in their mouth',
                'Protect their head with something soft',
                'Time the seizure — most last 1-3 minutes',
                'Turn them on their side once convulsions stop (recovery position)',
                'Call 912 if: seizure lasts over 5 min, person injured, pregnant, diabetic',
                'Stay with them and reassure them as they recover',
            ],
            'dos' => [
                'Time the seizure accurately',
                'Clear surrounding hazards',
                'Place on side once convulsions stop',
            ],
            'donts' => [
                'Do NOT put anything in their mouth',
                'Do NOT restrain or hold down',
                'Do NOT give food or water until fully conscious',
            ],
            'call912' => false,
        ],
        [
            'id' => 'anaphylaxis',
            'name' => 'Anaphylaxis',
            'icon' => 'fa-syringe',
            'severity' => 'critical',
            'category' => 'allergic',
            'summary' => 'Severe allergic reaction — life-threatening without treatment.',
            'steps' => [
                'Call 912 immediately',
                'Help them use their epinephrine auto-injector (EpiPen)',
                'Inject into outer thigh — can be done through clothing',
                'If no improvement in 5 minutes, give a second dose if available',
                'Lay the person flat with legs elevated',
                'Monitor breathing — be ready for CPR if needed',
                'Stay with them until emergency help arrives',
            ],
            'dos' => [
                'Use epinephrine immediately — do not delay',
                'Lay person flat, elevate legs',
                'Be prepared to give second dose',
            ],
            'donts' => [
                'Do not delay epinephrine waiting for improvement',
                'Do not give antihistamines instead of epinephrine',
                'Do not let the person stand up or walk',
            ],
            'call912' => true,
        ],
        [
            'id' => 'drowning',
            'name' => 'Drowning',
            'icon' => 'fa-water',
            'severity' => 'critical',
            'category' => 'breathing',
            'summary' => 'Difficulty breathing after submersion in water.',
            'steps' => [
                'Get the person out of water safely',
                'Call 912',
                'Check for breathing: look at chest for rise and fall',
                'If not breathing, begin CPR immediately with 5 rescue breaths first',
                'Then continue 30 compressions : 2 breaths',
                'Remove wet clothing and keep warm with blankets',
                'Always seek hospital — secondary drowning is possible',
            ],
            'dos' => [
                'Start with rescue breaths before compressions',
                'Keep person warm after rescue',
                'Always seek medical evaluation',
            ],
            'donts' => [
                'Do not attempt rescue if you can\'t swim',
                'Do not shake the person or try to drain water',
                'Do not assume they are fine after recovery',
            ],
            'call912' => true,
        ],
        [
            'id' => 'heat-stroke',
            'name' => 'Heat Stroke',
            'icon' => 'fa-temperature-high',
            'severity' => 'critical',
            'category' => 'environmental',
            'summary' => 'Body temperature over 104°F (40°C) — a medical emergency.',
            'steps' => [
                'Call 912 immediately',
                'Move person to a cool, shaded area',
                'Remove excess clothing',
                'Cool rapidly: ice packs to neck, armpits, groin; spray with cool water; fan them',
                'Immerse in cold water if available (most effective method)',
                'Do NOT give fluids if person is confused or unconscious',
                'Monitor temperature and consciousness',
                'Continue cooling until temperature drops below 101°F (38.3°C)',
            ],
            'dos' => [
                'Cool as fast as possible — every minute counts',
                'Target areas: neck, armpits, groin, head',
                'Use cold water immersion if possible',
            ],
            'donts' => [
                'Do not use ice baths for elderly or very young',
                'Do not give fluids if confused or unconscious',
                'Do not delay cooling to wait for ambulance',
            ],
            'call912' => true,
        ],
        [
            'id' => 'snake-bite',
            'name' => 'Snake Bite',
            'icon' => 'fa-worm',
            'severity' => 'urgent',
            'category' => 'environmental',
            'summary' => 'Venomous snake bite requires urgent medical treatment.',
            'steps' => [
                'Call 912 or go to nearest hospital immediately',
                'Keep the person still and calm — movement spreads venom faster',
                'Immobilize the bitten limb with a splint',
                'Remove rings and tight clothing near the bite',
                'Note the snake\'s appearance if safe — do NOT catch it',
                'Keep the bite at or below heart level',
                'Wash gently with water — do not scrub',
                'Monitor breathing and be ready for CPR',
            ],
            'dos' => [
                'Keep person completely still',
                'Immobilize the limb',
                'Seek hospital immediately — antivenom is the only effective treatment',
            ],
            'donts' => [
                'Do NOT cut the wound or attempt to suck out venom',
                'Do NOT apply a tourniquet',
                'Do NOT apply ice or heat',
                'Do NOT give alcohol or aspirin',
            ],
            'call912' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Body Map Zones
    |--------------------------------------------------------------------------
    */
    'body_zones' => [
        'head' => [
            'label' => 'Head',
            'color' => '#EF4444',
            'conditions' => [
                [
                    'name' => 'Concussion',
                    'severity' => 'urgent',
                    'desc' => 'Brain injury from impact. Symptoms: headache, confusion, dizziness, nausea, memory loss.',
                    'action' => 'Seek medical attention. Do not let person sleep for first few hours. Apply ice wrapped in cloth to the area.',
                ],
                [
                    'name' => 'Stroke',
                    'severity' => 'critical',
                    'desc' => 'Sudden weakness on one side, facial drooping, speech difficulty. Use FAST test.',
                    'action' => 'Call 912 immediately. Note time symptoms started. Keep person comfortable.',
                ],
                [
                    'name' => 'Eye Injury',
                    'severity' => 'moderate',
                    'desc' => 'Foreign object, chemical splash, or blunt trauma to the eye.',
                    'action' => 'Do not rub. Flush with clean water for 15 min (chemical). Do not remove embedded objects.',
                ],
                [
                    'name' => 'Nosebleed',
                    'severity' => 'minor',
                    'desc' => 'Bleeding from one or both nostrils, often from trauma or dry air.',
                    'action' => 'Sit upright, lean slightly forward. Pinch soft part of nose for 10 min. Apply ice to bridge of nose.',
                ],
            ],
        ],
        'chest' => [
            'label' => 'Chest',
            'color' => '#EF4444',
            'conditions' => [
                [
                    'name' => 'Heart Attack',
                    'severity' => 'critical',
                    'desc' => 'Chest pain or pressure, pain radiating to arm/jaw, shortness of breath, sweating.',
                    'action' => 'Call 912. Give aspirin (300mg chewed). Keep person calm and still. Be ready for CPR.',
                ],
                [
                    'name' => 'Cardiac Arrest',
                    'severity' => 'critical',
                    'desc' => 'Sudden collapse, no pulse, no breathing. Person is unresponsive.',
                    'action' => 'Call 912. Begin CPR immediately: 30 compressions : 2 breaths. Use AED if available.',
                ],
                [
                    'name' => 'Rib Fracture',
                    'severity' => 'urgent',
                    'desc' => 'Sharp pain when breathing or coughing, tenderness, possible deformity.',
                    'action' => 'Seek medical attention. Support the injured side when coughing. Do not strap tightly.',
                ],
            ],
        ],
        'abdomen' => [
            'label' => 'Abdomen',
            'color' => '#F97316',
            'conditions' => [
                [
                    'name' => 'Internal Bleeding',
                    'severity' => 'critical',
                    'desc' => 'Signs: rigid abdomen, pale skin, rapid pulse, coughing/vomiting blood, confusion.',
                    'action' => 'Call 912 immediately. Lay person flat, keep warm. Do NOT give food or drink.',
                ],
                [
                    'name' => 'Appendicitis Signs',
                    'severity' => 'urgent',
                    'desc' => 'Pain starting near belly button moving to lower right, fever, nausea.',
                    'action' => 'Call 912. Do NOT give pain medication or food. Keep person still and calm.',
                ],
                [
                    'name' => 'Abdominal Wound',
                    'severity' => 'critical',
                    'desc' => 'Penetrating or deep wound to the abdomen. Organs may be visible.',
                    'action' => 'Call 912. Do NOT push organs back in. Cover with moist sterile dressing.',
                ],
            ],
        ],
        'left-arm' => [
            'label' => 'Left Arm',
            'color' => '#2DD4BF',
            'conditions' => [
                [
                    'name' => 'Fracture',
                    'severity' => 'urgent',
                    'desc' => 'Pain, swelling, deformity, inability to move the arm.',
                    'action' => 'Immobilize with a sling and splint. Do not try to realign. Seek medical attention.',
                ],
                [
                    'name' => 'Severe Cut',
                    'severity' => 'urgent',
                    'desc' => 'Deep laceration with heavy bleeding.',
                    'action' => 'Apply direct pressure with clean cloth. Elevate arm. If bleeding persists, call 912.',
                ],
                [
                    'name' => 'Dislocation',
                    'severity' => 'urgent',
                    'desc' => 'Joint out of normal position, severe pain, swelling, unable to move.',
                    'action' => 'Do NOT try to pop back in place. Immobilize in current position.',
                ],
            ],
        ],
        'right-arm' => [
            'label' => 'Right Arm',
            'color' => '#2DD4BF',
            'conditions' => [
                [
                    'name' => 'Fracture',
                    'severity' => 'urgent',
                    'desc' => 'Pain, swelling, deformity, inability to move the arm.',
                    'action' => 'Immobilize with a sling and splint. Do not try to realign. Seek medical attention.',
                ],
                [
                    'name' => 'Severe Cut',
                    'severity' => 'urgent',
                    'desc' => 'Deep laceration with heavy bleeding.',
                    'action' => 'Apply direct pressure with clean cloth. Elevate arm. If bleeding persists, call 912.',
                ],
                [
                    'name' => 'Dislocation',
                    'severity' => 'urgent',
                    'desc' => 'Joint out of normal position, severe pain, swelling, unable to move.',
                    'action' => 'Do NOT try to pop back in place. Immobilize in current position.',
                ],
            ],
        ],
        'left-leg' => [
            'label' => 'Left Leg',
            'color' => '#2DD4BF',
            'conditions' => [
                [
                    'name' => 'Fracture',
                    'severity' => 'urgent',
                    'desc' => 'Pain, swelling, deformity, unable to bear weight.',
                    'action' => 'Do not move. Immobilize above and below fracture. Call 912 for femur fractures.',
                ],
                [
                    'name' => 'Sprain',
                    'severity' => 'moderate',
                    'desc' => 'Pain, swelling, bruising around a joint, usually ankle or knee.',
                    'action' => 'RICE: Rest, Ice (20 min), Compression bandage, Elevation. Seek medical if unable to bear weight.',
                ],
                [
                    'name' => 'Snake Bite',
                    'severity' => 'urgent',
                    'desc' => 'Two puncture marks, pain, swelling, nausea, blurred vision.',
                    'action' => 'Keep still. Immobilize limb. Call 912. Do NOT cut or suck the wound.',
                ],
            ],
        ],
        'right-leg' => [
            'label' => 'Right Leg',
            'color' => '#2DD4BF',
            'conditions' => [
                [
                    'name' => 'Fracture',
                    'severity' => 'urgent',
                    'desc' => 'Pain, swelling, deformity, unable to bear weight.',
                    'action' => 'Do not move. Immobilize above and below fracture. Call 912 for femur fractures.',
                ],
                [
                    'name' => 'Sprain',
                    'severity' => 'moderate',
                    'desc' => 'Pain, swelling, bruising around a joint, usually ankle or knee.',
                    'action' => 'RICE: Rest, Ice (20 min), Compression bandage, Elevation. Seek medical if unable to bear weight.',
                ],
                [
                    'name' => 'Snake Bite',
                    'severity' => 'urgent',
                    'desc' => 'Two puncture marks, pain, swelling, nausea, blurred vision.',
                    'action' => 'Keep still. Immobilize limb. Call 912. Do NOT cut or suck the wound.',
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Symptom Checker Decision Tree
    |--------------------------------------------------------------------------
    */
    'symptom_checker' => [
        'start' => [
            'question' => 'What is the main problem you are observing?',
            'options' => [
                ['text' => 'Chest pain or discomfort', 'next' => 'chest_pain', 'icon' => 'fa-heart'],
                ['text' => 'Difficulty breathing', 'next' => 'breathing', 'icon' => 'fa-lungs'],
                ['text' => 'Bleeding or wound', 'next' => 'bleeding', 'icon' => 'fa-droplet'],
                ['text' => 'Person is unconscious', 'next' => 'unconscious', 'icon' => 'fa-person-falling'],
                ['text' => 'Burn or scald', 'next' => 'burn', 'icon' => 'fa-fire'],
                ['text' => 'Seizure or convulsion', 'next' => 'seizure', 'icon' => 'fa-bolt'],
                ['text' => 'Suspected broken bone', 'next' => 'fracture', 'icon' => 'fa-bone'],
                ['text' => 'Allergic reaction', 'next' => 'allergic', 'icon' => 'fa-syringe'],
            ],
        ],
        'chest_pain' => [
            'question' => 'Is the pain sudden and severe, or has it been building gradually?',
            'options' => [
                ['text' => 'Sudden, crushing pain', 'next' => 'chest_severe', 'icon' => 'fa-circle-exclamation'],
                ['text' => 'Gradual, mild to moderate', 'next' => 'chest_mild', 'icon' => 'fa-circle-minus'],
                ['text' => 'Sharp pain when breathing', 'next' => 'chest_sharp', 'icon' => 'fa-wind'],
            ],
        ],
        'chest_severe' => [
            'question' => 'Is the pain spreading to the arm, jaw, or back? Is the person sweating?',
            'options' => [
                ['text' => 'Yes, multiple symptoms', 'result' => ['severity' => 'critical', 'condition' => 'Heart Attack', 'action' => 'Call 912 IMMEDIATELY. Give aspirin (300mg) if not allergic — chew it. Keep person still and calm. Be prepared to start CPR if they lose consciousness.']],
                ['text' => 'Person collapsed and unresponsive', 'result' => ['severity' => 'critical', 'condition' => 'Cardiac Arrest', 'action' => 'Call 912 IMMEDIATELY. Begin CPR NOW: 30 compressions to 2 breaths. Push hard and fast on center of chest. Use AED if available.']],
                ['text' => 'Only chest pain, no other symptoms', 'result' => ['severity' => 'urgent', 'condition' => 'Possible Heart Attack', 'action' => 'Call 912. Do not take chances with chest pain. Give aspirin if available. Keep person calm and monitor closely.']],
            ],
        ],
        'chest_mild' => [
            'question' => 'Does the pain get worse with activity or stress?',
            'options' => [
                ['text' => 'Yes, worse with activity', 'result' => ['severity' => 'urgent', 'condition' => 'Angina / Possible Heart Issue', 'action' => 'Have person rest and sit quietly. If they have prescribed nitroglycerin, help them take it. Call 912 if pain persists.']],
                ['text' => 'No, seems unrelated to activity', 'result' => ['severity' => 'moderate', 'condition' => 'Chest Pain — Other Causes', 'action' => 'Could be acid reflux, muscle strain, or anxiety. Still recommend medical evaluation. If pain worsens, call 912 immediately.']],
            ],
        ],
        'chest_sharp' => [
            'question' => 'Did the sharp pain start after injury, coughing, or deep breathing?',
            'options' => [
                ['text' => 'After injury to chest', 'result' => ['severity' => 'urgent', 'condition' => 'Possible Rib Fracture', 'action' => 'Immobilize the area. Support the chest when coughing. Seek medical attention for X-ray. Do not strap tightly.']],
                ['text' => 'With coughing or breathing only', 'result' => ['severity' => 'moderate', 'condition' => 'Pleuritic Pain', 'action' => 'Could be pleurisy, pneumonia, or muscle strain. Seek medical evaluation. Take shallow breaths if painful.']],
            ],
        ],
        'breathing' => [
            'question' => 'Is the person able to speak at all, or are they clutching their throat?',
            'options' => [
                ['text' => 'Cannot speak, clutching throat', 'result' => ['severity' => 'critical', 'condition' => 'Choking', 'action' => 'Give 5 back blows (bend forward, strike between shoulder blades). Then 5 abdominal thrusts. Alternate until blockage clears or person becomes unconscious. If unconscious, begin CPR. Call 912.']],
                ['text' => 'Wheezing, known asthma', 'result' => ['severity' => 'urgent', 'condition' => 'Asthma Attack', 'action' => 'Help them use their rescue inhaler (2 puffs, wait 1 min, repeat up to 3 times). Keep calm and sitting upright. Call 912 if no improvement.']],
                ['text' => 'Gradual shortness of breath', 'next' => 'breathing_gradual', 'icon' => 'fa-clock'],
            ],
        ],
        'breathing_gradual' => [
            'question' => 'Any swelling in face/throat, rash, or known allergen exposure?',
            'options' => [
                ['text' => 'Yes, swelling or rash', 'result' => ['severity' => 'critical', 'condition' => 'Anaphylaxis', 'action' => 'Use epinephrine auto-injector immediately. Inject into outer thigh. Call 912. Give second dose in 5 min if no improvement. Lay person flat.']],
                ['text' => 'No swelling or rash', 'result' => ['severity' => 'urgent', 'condition' => 'Difficulty Breathing — Unknown Cause', 'action' => 'Help person sit upright, leaning slightly forward. Loosen tight clothing. Call 912. Monitor breathing closely. Be prepared for CPR.']],
            ],
        ],
        'unconscious' => [
            'question' => 'Is the person breathing at all?',
            'options' => [
                ['text' => 'Not breathing or only gasping', 'result' => ['severity' => 'critical', 'condition' => 'Cardiac Arrest', 'action' => 'Call 912 (or have someone call). Begin CPR immediately: 30 compressions to 2 breaths. Push at least 2 inches deep at 100-120 BPM. Use AED if available.']],
                ['text' => 'Breathing normally', 'result' => ['severity' => 'urgent', 'condition' => 'Unconscious but Breathing', 'action' => 'Call 912. Place in recovery position (on side, top leg bent, hand supporting head). Check airway is clear. Monitor breathing until help arrives.']],
                ['text' => 'Not sure', 'result' => ['severity' => 'critical', 'condition' => 'Unconscious — Check Breathing', 'action' => 'Tilt head back, look at chest for 10 seconds to check breathing. If not breathing normally — begin CPR and call 912. If breathing — recovery position and call 912.']],
            ],
        ],
        'bleeding' => [
            'question' => 'How would you describe the bleeding?',
            'options' => [
                ['text' => 'Heavy, spurting, or won\'t stop', 'result' => ['severity' => 'critical', 'condition' => 'Severe Bleeding', 'action' => 'Apply firm direct pressure. Call 912. Elevate limb if possible. For life-threatening limb bleeding, use tourniquet 2-3 inches above wound. Note time applied. Keep person warm.']],
                ['text' => 'Moderate, from a cut or wound', 'result' => ['severity' => 'moderate', 'condition' => 'Moderate Bleeding', 'action' => 'Apply direct pressure with clean cloth for 10 minutes. Elevate if possible. Once stopped, clean with water, apply antiseptic, and bandage.']],
                ['text' => 'Minor scrape or small cut', 'result' => ['severity' => 'minor', 'condition' => 'Minor Wound', 'action' => 'Clean with water. Apply antiseptic. Cover with adhesive bandage. Change bandage daily. Watch for signs of infection.']],
            ],
        ],
        'burn' => [
            'question' => 'How large is the burn area?',
            'options' => [
                ['text' => 'Larger than palm, or on face/hands/genitals', 'result' => ['severity' => 'critical', 'condition' => 'Major Burn', 'action' => 'Call 912. Cool with running water for 20 min. Do NOT remove stuck clothing. Cover with cling film. Treat for shock: lay flat, keep warm.']],
                ['text' => 'Smaller than palm, not on sensitive areas', 'result' => ['severity' => 'moderate', 'condition' => 'Minor Burn', 'action' => 'Cool under running water for 20 minutes. Cover with cling film or non-stick dressing. Take over-the-counter pain relief. Seek medical advice if blistered.']],
            ],
        ],
        'seizure' => [
            'question' => 'Is the person currently having a seizure, or has it stopped?',
            'options' => [
                ['text' => 'Currently seizing', 'result' => ['severity' => 'urgent', 'condition' => 'Active Seizure', 'action' => 'Clear area of hazards. Do NOT restrain or put anything in mouth. Protect head with soft object. Time the seizure. Turn on side when convulsions stop. Call 912 if over 5 minutes.']],
                ['text' => 'Seizure has stopped', 'result' => ['severity' => 'moderate', 'condition' => 'Post-Seizure Care', 'action' => 'Place in recovery position. Stay with them and reassure. They may be confused. Call 912 if: first seizure, lasted over 5 min, injured, pregnant, or diabetic.']],
            ],
        ],
        'fracture' => [
            'question' => 'Is there an open wound with bone visible, or is the limb deformed?',
            'options' => [
                ['text' => 'Bone visible or severe deformity', 'result' => ['severity' => 'critical', 'condition' => 'Open Fracture', 'action' => 'Call 912. Do NOT push bone back. Cover wound with sterile dressing. Immobilize above and below fracture. Do NOT give food or drink. Keep person warm.']],
                ['text' => 'Swollen, painful, but no visible bone', 'result' => ['severity' => 'urgent', 'condition' => 'Suspected Closed Fracture', 'action' => 'Immobilize with a splint (pad above and below). Do not try to realign. Apply ice wrapped in cloth. Elevate if possible. Seek medical attention.']],
                ['text' => 'Can bear some weight, mild pain', 'result' => ['severity' => 'moderate', 'condition' => 'Possible Sprain or Minor Fracture', 'action' => 'RICE: Rest, Ice (20 min wrapped in cloth), Compression bandage, Elevation. If unable to bear weight after 24 hours, seek medical evaluation.']],
            ],
        ],
        'allergic' => [
            'question' => 'Are there signs of severe reaction: throat swelling, difficulty breathing, dizziness?',
            'options' => [
                ['text' => 'Yes, severe symptoms', 'result' => ['severity' => 'critical', 'condition' => 'Anaphylaxis', 'action' => 'Use epinephrine auto-injector NOW — outer thigh, through clothing if needed. Call 912. Second dose in 5 min if no improvement. Lay flat, elevate legs. Be ready for CPR.']],
                ['text' => 'Mild: localized rash, itching, mild swelling', 'result' => ['severity' => 'moderate', 'condition' => 'Mild Allergic Reaction', 'action' => 'Give antihistamine if available. Apply cool compress to rash. Remove the allergen if identified. Monitor for worsening symptoms.']],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Kit Items (seeded on first run)
    |--------------------------------------------------------------------------
    */
    'default_kit' => [
        ['name' => 'Adhesive bandages (assorted)', 'category' => 'bandages'],
        ['name' => 'Sterile gauze pads (4x4)', 'category' => 'bandages'],
        ['name' => 'Elastic bandage (3 inch)', 'category' => 'bandages'],
        ['name' => 'Triangular bandage/sling', 'category' => 'bandages'],
        ['name' => 'Adhesive tape', 'category' => 'bandages'],
        ['name' => 'Non-stick wound dressings', 'category' => 'bandages'],
        ['name' => 'Aspirin (300mg tablets)', 'category' => 'medications'],
        ['name' => 'Ibuprofen', 'category' => 'medications'],
        ['name' => 'Antihistamine tablets', 'category' => 'medications'],
        ['name' => 'Antiseptic cream/ointment', 'category' => 'medications'],
        ['name' => 'Hydrocortisone cream (1%)', 'category' => 'medications'],
        ['name' => 'Pain relief tablets', 'category' => 'medications'],
        ['name' => 'Scissors (blunt-tip)', 'category' => 'tools'],
        ['name' => 'Tweezers', 'category' => 'tools'],
        ['name' => 'Disposable gloves (latex-free)', 'category' => 'tools'],
        ['name' => 'Digital thermometer', 'category' => 'tools'],
        ['name' => 'Instant cold packs', 'category' => 'tools'],
        ['name' => 'CPR face shield', 'category' => 'tools'],
        ['name' => 'Emergency blanket (foil)', 'category' => 'tools'],
        ['name' => 'Small flashlight', 'category' => 'tools'],
        ['name' => 'Cling film (for burns)', 'category' => 'other'],
        ['name' => 'Safety pins', 'category' => 'other'],
        ['name' => 'Plastic bags (for waste)', 'category' => 'other'],
        ['name' => 'Notepad and pen', 'category' => 'other'],
        ['name' => 'First aid manual', 'category' => 'other'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Emergency Contacts (seeded on first run)
    |--------------------------------------------------------------------------
    */
    'default_contacts' => [
        ['name' => 'Emergency Services', 'phone' => '912', 'type' => 'emergency', 'icon' => 'fa-phone-volume'],
        ['name' => 'Poison Control', 'phone' => '18002221222', 'type' => 'emergency', 'icon' => 'fa-skull-crossbones'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Quick Actions for Dashboard
    |--------------------------------------------------------------------------
    */
    'quick_actions' => [
        ['label' => 'Cardiac Arrest', 'icon' => 'fa-heart-crack', 'color' => '#EF4444', 'condition' => 'cardiac-arrest'],
        ['label' => 'Heart Attack', 'icon' => 'fa-heart', 'color' => '#EF4444', 'condition' => 'heart-attack'],
        ['label' => 'Choking', 'icon' => 'fa-lungs', 'color' => '#F97316', 'condition' => 'choking'],
        ['label' => 'Severe Bleeding', 'icon' => 'fa-droplet', 'color' => '#EF4444', 'condition' => 'severe-bleeding'],
        ['label' => 'Stroke', 'icon' => 'fa-brain', 'color' => '#EF4444', 'condition' => 'stroke'],
        ['label' => 'Anaphylaxis', 'icon' => 'fa-syringe', 'color' => '#EF4444', 'condition' => 'anaphylaxis'],
        ['label' => 'Start CPR', 'icon' => 'fa-heart-pulse', 'color' => '#2DD4BF', 'route' => 'cpr'],
        ['label' => 'Body Map', 'icon' => 'fa-person', 'color' => '#2DD4BF', 'route' => 'body-map'],
    ],
];