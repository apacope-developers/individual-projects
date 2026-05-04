<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>LifeLine - Emergency First Aid System</title>
<link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
<link rel="alternate icon" href="{{ asset('favicon.ico') }}">
<link rel="apple-touch-icon" href="{{ asset('favicon.svg') }}">
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'DM Sans',sans-serif;background:#09090B;color:#fafafa}
h1,h2,h3,h4,h5,h6{font-family:'Space Grotesk',sans-serif}
.bg-grid{background-image:radial-gradient(ellipse 80% 50% at 50% 0%,rgba(239,68,68,.06) 0%,transparent 60%),radial-gradient(ellipse 60% 40% at 80% 100%,rgba(45,212,191,.04) 0%,transparent 60%),linear-gradient(rgba(63,63,70,.15) 1px,transparent 1px),linear-gradient(90deg,rgba(63,63,70,.15) 1px,transparent 1px);background-size:100% 100%,100% 100%,40px 40px,40px 40px}
.hero-gradient{background:linear-gradient(135deg,rgba(239,68,68,.1) 0%,rgba(45,212,191,.05) 100%)}
.card-hover{transition:all 0.3s ease}
.card-hover:hover{transform:translateY(-8px);box-shadow:0 20px 40px rgba(0,0,0,0.3)}
.pulse-ring{animation:pulseRing 2s ease-in-out infinite}
@keyframes pulseRing{0%,100%{box-shadow:0 0 0 0 rgba(239,68,68,.4)}70%{box-shadow:0 0 0 15px rgba(239,68,68,0)}}
.float-animation{animation:float 6s ease-in-out infinite}
@keyframes float{0%,100%{transform:translateY(0)}50%{transform:translateY(-20px)}}
.feature-icon{transition:all 0.3s ease}
.feature-card:hover .feature-icon{transform:scale(1.1) rotate(5deg)}
.video-overlay{position:absolute;inset:0;background:linear-gradient(to bottom,transparent 0%,rgba(0,0,0,0.7) 100%);pointer-events:none}
.image-card{transition:all 0.4s cubic-bezier(0.4,0,0.2,1)}
.image-card:hover{transform:translateY(-8px) scale(1.02);box-shadow:0 25px 50px rgba(0,0,0,0.4)}
.pulse-glow{animation:pulseGlow 2s ease-in-out infinite}
@keyframes pulseGlow{0%,100%{box-shadow:0 0 20px rgba(239,68,68,0.4)}50%{box-shadow:0 0 40px rgba(239,68,68,0.8)}}
.slide-in{animation:slideIn 0.8s ease-out}
@keyframes slideIn{from{opacity:0;transform:translateY(30px)}to{opacity:1;transform:translateY(0)}}
.video-container{position:relative;overflow:hidden;border-radius:1rem}
.video-container::before{content:'';position:absolute;top:0;left:0;right:0;bottom:0;background:linear-gradient(45deg,transparent 30%,rgba(239,68,68,0.1) 50%,transparent 70%);animation:shimmer 3s infinite}
@keyframes shimmer{0%{transform:translateX(-100%)}100%{transform:translateX(100%)}}
</style>
</head>
<body class="bg-grid">

<!-- Navigation -->
<nav class="fixed top-0 w-full bg-[#09090B]/90 backdrop-blur-lg border-b border-zinc-800 z-50">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center h-16">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-red-600 flex items-center justify-center pulse-ring">
          <i class="fa-solid fa-heart-pulse text-white"></i>
        </div>
        <span class="text-xl font-bold">LifeLine</span>
      </div>
      <div class="hidden md:flex items-center gap-8">
        <a href="#features" class="text-zinc-300 hover:text-white transition-colors">Features</a>
        <a href="#how-it-works" class="text-zinc-300 hover:text-white transition-colors">How It Works</a>
        <a href="#emergency" class="text-zinc-300 hover:text-white transition-colors">Emergency Guide</a>
        <a href="{{ route('login') }}" class="px-4 py-2 bg-red-600 hover:bg-red-500 rounded-lg font-medium transition-colors">Sign In</a>
      </div>
      <button class="md:hidden text-zinc-300" onclick="toggleMobileMenu()">
        <i class="fa-solid fa-bars text-xl"></i>
      </button>
    </div>
  </div>
  <!-- Mobile Menu -->
  <div id="mobileMenu" class="hidden md:hidden bg-[#09090B]/95 backdrop-blur-lg border-t border-zinc-800">
    <div class="px-4 py-3 space-y-2">
      <a href="#features" class="block py-2 text-zinc-300">Features</a>
      <a href="#how-it-works" class="block py-2 text-zinc-300">How It Works</a>
      <a href="#emergency" class="block py-2 text-zinc-300">Emergency Guide</a>
      <a href="{{ route('login') }}" class="block py-2 text-red-400">Sign In</a>
    </div>
  </div>
</nav>

<!-- Hero Section -->
<section class="min-h-screen flex items-center justify-center px-4 pt-16 relative">
  
  <div class="max-w-7xl mx-auto text-center relative z-10">
    <div class="mb-8">
            <h1 class="text-5xl md:text-7xl font-bold mb-6 bg-gradient-to-r from-white to-zinc-300 bg-clip-text text-transparent">
        Your Lifeline in
        <span class="text-red-500">Critical Moments</span>
      </h1>
      <p class="text-xl text-zinc-300 max-w-3xl mx-auto mb-8 leading-relaxed">
        Get instant access to life-saving first aid instructions, AI-powered symptom assessment, 
        and real-time emergency guidance when every second counts.
      </p>
    </div>
    
        

    <div class="flex flex-col sm:flex-row gap-4 justify-center mb-12">
      <a href="{{ route('register') }}" class="px-8 py-4 bg-red-600 hover:bg-red-500 rounded-xl font-semibold transition-all transform hover:scale-105 flex items-center justify-center gap-2">
        <i class="fa-solid fa-rocket"></i> Get Started Free
      </a>
    </div>

    <div class="relative">
      <div class="absolute inset-0 bg-red-600/20 blur-3xl rounded-full"></div>
      <div class="relative bg-[#18181B]/80 backdrop-blur-xl border border-zinc-800 rounded-2xl p-8 max-w-4xl mx-auto">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
          <div class="text-center">
            <div class="text-3xl font-bold text-red-400 mb-1">50+</div>
            <div class="text-sm text-zinc-500">Emergency Guides</div>
          </div>
          <div class="text-center">
            <div class="text-3xl font-bold text-teal-400 mb-1">24/7</div>
            <div class="text-sm text-zinc-500">Available</div>
          </div>
          <div class="text-center">
            <div class="text-3xl font-bold text-blue-400 mb-1">100%</div>
            <div class="text-sm text-zinc-500">Free</div>
          </div>
          <div class="text-center">
            <div class="text-3xl font-bold text-green-400 mb-1">13</div>
            <div class="text-sm text-zinc-500">Categories</div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2">
    <i class="fa-solid fa-chevron-down text-zinc-500"></i>
  </div>
</section>

<!-- Features Section -->
<section id="features" class="py-20 px-4">
  <div class="max-w-7xl mx-auto">
    <div class="text-center mb-16">
      <h2 class="text-4xl md:text-5xl font-bold mb-4">Life-Saving Features</h2>
      <p class="text-xl text-zinc-400 max-w-2xl mx-auto">
        Everything you need to handle medical emergencies with confidence
      </p>
    </div>

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
      <div class="feature-card bg-[#18181B]/50 backdrop-blur-xl border border-zinc-800 rounded-2xl p-8 card-hover">
        <div class="feature-icon w-14 h-14 rounded-xl bg-red-600/15 flex items-center justify-center mb-6">
          <i class="fa-solid fa-book-medical text-red-400 text-xl"></i>
        </div>
        <h3 class="text-xl font-semibold mb-3">Emergency First Aid Guides</h3>
        <p class="text-zinc-400 mb-4">Step-by-step instructions for 50+ critical situations including choking, bleeding, burns, and more.</p>
        <ul class="space-y-2 text-sm text-zinc-500">
          <li><i class="fa-solid fa-check text-green-400 mr-2"></i>Clear visual instructions</li>
          <li><i class="fa-solid fa-check text-green-400 mr-2"></i>Time-critical guidance</li>
          <li><i class="fa-solid fa-check text-green-400 mr-2"></i>Professional medical content</li>
        </ul>
      </div>

      <div class="feature-card bg-[#18181B]/50 backdrop-blur-xl border border-zinc-800 rounded-2xl p-8 card-hover">
        <div class="feature-icon w-14 h-14 rounded-xl bg-teal-600/15 flex items-center justify-center mb-6">
          <i class="fa-solid fa-person text-teal-400 text-xl"></i>
        </div>
        <h3 class="text-xl font-semibold mb-3">Interactive Body Map</h3>
        <p class="text-zinc-400 mb-4">Click on any body part to instantly access relevant conditions and first aid procedures.</p>
        <ul class="space-y-2 text-sm text-zinc-500">
          <li><i class="fa-solid fa-check text-green-400 mr-2"></i>Visual body exploration</li>
          <li><i class="fa-solid fa-check text-green-400 mr-2"></i>Quick condition lookup</li>
          <li><i class="fa-solid fa-check text-green-400 mr-2"></i>Targeted first aid</li>
        </ul>
      </div>

      <div class="feature-card bg-[#18181B]/50 backdrop-blur-xl border border-zinc-800 rounded-2xl p-8 card-hover">
        <div class="feature-icon w-14 h-14 rounded-xl bg-orange-600/15 flex items-center justify-center mb-6">
          <i class="fa-solid fa-stethoscope text-orange-400 text-xl"></i>
        </div>
        <h3 class="text-xl font-semibold mb-3">AI Symptom Checker</h3>
        <p class="text-zinc-400 mb-4">Smart triage system that assesses symptoms and provides prioritized medical guidance.</p>
        <ul class="space-y-2 text-sm text-zinc-500">
          <li><i class="fa-solid fa-check text-green-400 mr-2"></i>AI-powered assessment</li>
          <li><i class="fa-solid fa-check text-green-400 mr-2"></i>Priority recommendations</li>
          <li><i class="fa-solid fa-check text-green-400 mr-2"></i>Emergency level detection</li>
        </ul>
      </div>

      <div class="feature-card bg-[#18181B]/50 backdrop-blur-xl border border-zinc-800 rounded-2xl p-8 card-hover">
        <div class="feature-icon w-14 h-14 rounded-xl bg-blue-600/15 flex items-center justify-center mb-6">
          <i class="fa-solid fa-heart-pulse text-blue-400 text-xl"></i>
        </div>
        <h3 class="text-xl font-semibold mb-3">CPR Metronome</h3>
        <p class="text-zinc-400 mb-4">Real-time audio guidance for proper CPR timing and compression rate during cardiac emergencies.</p>
        <ul class="space-y-2 text-sm text-zinc-500">
          <li><i class="fa-solid fa-check text-green-400 mr-2"></i>100-120 BPM timing</li>
          <li><i class="fa-solid fa-check text-green-400 mr-2"></i>Audio cues</li>
          <li><i class="fa-solid fa-check text-green-400 mr-2"></i>Emergency standard</li>
        </ul>
      </div>

      <div class="feature-card bg-[#18181B]/50 backdrop-blur-xl border border-zinc-800 rounded-2xl p-8 card-hover">
        <div class="feature-icon w-14 h-14 rounded-xl bg-purple-600/15 flex items-center justify-center mb-6">
          <i class="fa-solid fa-kit-medical text-purple-400 text-xl"></i>
        </div>
        <h3 class="text-xl font-semibold mb-3">First Aid Kit Manager</h3>
        <p class="text-zinc-400 mb-4">Track your medical supplies, get expiration alerts, and maintain emergency readiness.</p>
        <ul class="space-y-2 text-sm text-zinc-500">
          <li><i class="fa-solid fa-check text-green-400 mr-2"></i>Inventory tracking</li>
          <li><i class="fa-solid fa-check text-green-400 mr-2"></i>Expiration reminders</li>
          <li><i class="fa-solid fa-check text-green-400 mr-2"></i>Supply recommendations</li>
        </ul>
      </div>

      <div class="feature-card bg-[#18181B]/50 backdrop-blur-xl border border-zinc-800 rounded-2xl p-8 card-hover">
        <div class="feature-icon w-14 h-14 rounded-xl bg-green-600/15 flex items-center justify-center mb-6">
          <i class="fa-solid fa-phone-volume text-green-400 text-xl"></i>
        </div>
        <h3 class="text-xl font-semibold mb-3">Emergency Contacts</h3>
        <p class="text-zinc-400 mb-4">Quick access to emergency services, hospitals, and personal contacts with one-tap calling.</p>
        <ul class="space-y-2 text-sm text-zinc-500">
          <li><i class="fa-solid fa-check text-green-400 mr-2"></i>Emergency services</li>
          <li><i class="fa-solid fa-check text-green-400 mr-2"></i>Local hospitals</li>
          <li><i class="fa-solid fa-check text-green-400 mr-2"></i>Personal contacts</li>
        </ul>
      </div>
    </div>
  </div>
</section>

<!-- How It Works Section -->
<section id="how-it-works" class="py-20 px-4 hero-gradient">
  <div class="max-w-7xl mx-auto">
    <div class="text-center mb-16">
      <h2 class="text-4xl md:text-5xl font-bold mb-4">How LifeLine Works</h2>
      <p class="text-xl text-zinc-400 max-w-2xl mx-auto">
        Get emergency guidance in three simple steps when every second matters
      </p>
    </div>

    <div class="grid md:grid-cols-3 gap-8 relative">
      <!-- Connection Lines -->
      <div class="hidden md:block absolute top-1/2 left-0 right-0 h-0.5 bg-gradient-to-r from-transparent via-zinc-600 to-transparent -translate-y-1/2"></div>
      
      <div class="relative">
        <div class="bg-[#18181B]/80 backdrop-blur-xl border border-zinc-800 rounded-2xl p-8 text-center card-hover">
          <div class="w-16 h-16 rounded-full bg-red-600 flex items-center justify-center mx-auto mb-6">
            <span class="text-2xl font-bold">1</span>
          </div>
          <h3 class="text-xl font-semibold mb-3">Assess Situation</h3>
          <p class="text-zinc-400 mb-4">Quickly identify the emergency type through our symptom checker or body map interface.</p>
          <div class="flex justify-center gap-2">
            <span class="px-3 py-1 bg-red-600/20 border border-red-600/30 rounded-full text-xs text-red-300">30 seconds</span>
            <span class="px-3 py-1 bg-zinc-800 rounded-full text-xs text-zinc-400">AI-powered</span>
          </div>
        </div>
      </div>

      <div class="relative">
        <div class="bg-[#18181B]/80 backdrop-blur-xl border border-zinc-800 rounded-2xl p-8 text-center card-hover">
          <div class="w-16 h-16 rounded-full bg-teal-600 flex items-center justify-center mx-auto mb-6">
            <span class="text-2xl font-bold">2</span>
          </div>
          <h3 class="text-xl font-semibold mb-3">Get Guidance</h3>
          <p class="text-zinc-400 mb-4">Receive step-by-step first aid instructions with visual aids and real-time CPR guidance.</p>
          <div class="flex justify-center gap-2">
            <span class="px-3 py-1 bg-teal-600/20 border border-teal-600/30 rounded-full text-xs text-teal-300">Visual guides</span>
            <span class="px-3 py-1 bg-zinc-800 rounded-full text-xs text-zinc-400">Audio cues</span>
          </div>
        </div>
      </div>

      <div class="relative">
        <div class="bg-[#18181B]/80 backdrop-blur-xl border border-zinc-800 rounded-2xl p-8 text-center card-hover">
          <div class="w-16 h-16 rounded-full bg-orange-600 flex items-center justify-center mx-auto mb-6">
            <span class="text-2xl font-bold">3</span>
          </div>
          <h3 class="text-xl font-semibold mb-3">Take Action</h3>
          <p class="text-zinc-400 mb-4">Follow the guided procedures while getting emergency contacts and hospital directions if needed.</p>
          <div class="flex justify-center gap-2">
            <span class="px-3 py-1 bg-orange-600/20 border border-orange-600/30 rounded-full text-xs text-orange-300">Emergency calls</span>
            <span class="px-3 py-1 bg-zinc-800 rounded-full text-xs text-zinc-400">Hospital locator</span>
          </div>
        </div>
      </div>
    </div>

    <div class="mt-16 text-center">
      <div class="bg-[#18181B]/60 backdrop-blur-xl border border-zinc-800 rounded-2xl p-8 max-w-4xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div class="text-center">
            <i class="fa-solid fa-clock text-3xl text-red-400 mb-3"></i>
            <div class="text-2xl font-bold mb-1">2 Minutes</div>
            <div class="text-sm text-zinc-500">Average response time</div>
          </div>
          <div class="text-center">
            <i class="fa-solid fa-chart-line text-3xl text-teal-400 mb-3"></i>
            <div class="text-2xl font-bold mb-1">95%</div>
            <div class="text-sm text-zinc-500">User confidence rate</div>
          </div>
          <div class="text-center">
            <i class="fa-solid fa-trophy text-3xl text-orange-400 mb-3"></i>
            <div class="text-2xl font-bold mb-1">4.9/5</div>
            <div class="text-sm text-zinc-500">User rating</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Emergency Scenarios Section -->
<section id="emergency" class="py-20 px-4">
  <div class="max-w-7xl mx-auto">
    <div class="text-center mb-16">
      <h2 class="text-4xl md:text-5xl font-bold mb-4">Emergency Scenarios Covered</h2>
      <p class="text-xl text-zinc-400 max-w-2xl mx-auto">
        Professional first aid guidance for the most critical medical emergencies
      </p>
    </div>

    
    <!-- Emergency Categories Grid -->
    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
      <div class="bg-gradient-to-br from-red-600/10 to-transparent border border-red-600/20 rounded-xl p-6 card-hover">
        <i class="fa-solid fa-lungs text-2xl text-red-400 mb-4"></i>
        <h4 class="font-semibold mb-2">Choking</h4>
        <p class="text-sm text-zinc-400">Heimlich maneuver and airway clearance techniques</p>
      </div>
      <div class="bg-gradient-to-br from-blue-600/10 to-transparent border border-blue-600/20 rounded-xl p-6 card-hover">
        <i class="fa-solid fa-heart text-2xl text-blue-400 mb-4"></i>
        <h4 class="font-semibold mb-2">Cardiac Arrest</h4>
        <p class="text-sm text-zinc-400">CPR procedures and AED guidance</p>
      </div>
      <div class="bg-gradient-to-br from-orange-600/10 to-transparent border border-orange-600/20 rounded-xl p-6 card-hover">
        <i class="fa-solid fa-fire text-2xl text-orange-400 mb-4"></i>
        <h4 class="font-semibold mb-2">Burns</h4>
        <p class="text-sm text-zinc-400">Thermal, chemical, and electrical burn treatment</p>
      </div>
      <div class="bg-gradient-to-br from-purple-600/10 to-transparent border border-purple-600/20 rounded-xl p-6 card-hover">
        <i class="fa-solid fa-bone text-2xl text-purple-400 mb-4"></i>
        <h4 class="font-semibold mb-2">Fractures</h4>
        <p class="text-sm text-zinc-400">Splinting and immobilization techniques</p>
      </div>
      <div class="bg-gradient-to-br from-teal-600/10 to-transparent border border-teal-600/20 rounded-xl p-6 card-hover">
        <i class="fa-solid fa-droplet text-2xl text-teal-400 mb-4"></i>
        <h4 class="font-semibold mb-2">Bleeding</h4>
        <p class="text-sm text-zinc-400">Control severe bleeding and wound care</p>
      </div>
      <div class="bg-gradient-to-br from-green-600/10 to-transparent border border-green-600/20 rounded-xl p-6 card-hover">
        <i class="fa-solid fa-spider text-2xl text-green-400 mb-4"></i>
        <h4 class="font-semibold mb-2">Bites & Stings</h4>
        <p class="text-sm text-zinc-400">Insect, snake, and animal bite treatment</p>
      </div>
      <div class="bg-gradient-to-br from-yellow-600/10 to-transparent border border-yellow-600/20 rounded-xl p-6 card-hover">
        <i class="fa-solid fa-temperature-high text-2xl text-yellow-400 mb-4"></i>
        <h4 class="font-semibold mb-2">Fever & Heat</h4>
        <p class="text-sm text-zinc-400">Heat stroke and temperature management</p>
      </div>
      <div class="bg-gradient-to-br from-pink-600/10 to-transparent border border-pink-600/20 rounded-xl p-6 card-hover">
        <i class="fa-solid fa-brain text-2xl text-pink-400 mb-4"></i>
        <h4 class="font-semibold mb-2">Head Injury</h4>
        <p class="text-sm text-zinc-400">Concussion and trauma assessment</p>
      </div>
    </div>
  </div>
</section>

<!-- CTA Section -->
<section class="py-20 px-4">
  <div class="max-w-4xl mx-auto text-center">
    <div class="bg-gradient-to-r from-red-600/20 to-teal-600/20 border border-zinc-800 rounded-3xl p-12">
      <h2 class="text-3xl md:text-4xl font-bold mb-4">Ready to Save Lives?</h2>
      <p class="text-xl text-zinc-400 mb-8">
        Join thousands who trust LifeLine for emergency medical guidance. 
        Be prepared when it matters most.
      </p>
      <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <a href="{{ route('register') }}" class="px-8 py-4 bg-red-600 hover:bg-red-500 rounded-xl font-semibold transition-all transform hover:scale-105">
          <i class="fa-solid fa-user-plus mr-2"></i> Create Free Account
        </a>
        <a href="{{ route('login') }}" class="px-8 py-4 bg-zinc-800 hover:bg-zinc-700 rounded-xl font-semibold transition-all border border-zinc-700">
          <i class="fa-solid fa-sign-in-alt mr-2"></i> Sign In
        </a>
      </div>
    </div>
  </div>
</section>

<!-- Footer -->
<footer class="border-t border-zinc-800 py-12 px-4">
  <div class="max-w-7xl mx-auto">
    <div class="grid md:grid-cols-4 gap-8 mb-8">
      <div>
        <div class="flex items-center gap-3 mb-4">
          <div class="w-10 h-10 rounded-xl bg-red-600 flex items-center justify-center">
            <i class="fa-solid fa-heart-pulse text-white"></i>
          </div>
          <span class="text-xl font-bold">LifeLine</span>
        </div>
        <p class="text-zinc-400 text-sm">Emergency first aid guidance system powered by AI and medical expertise.</p>
      </div>
      <div>
        <h4 class="font-semibold mb-4">Features</h4>
        <ul class="space-y-2 text-sm text-zinc-400">
          <li><a href="#" class="hover:text-white transition-colors">First Aid Guides</a></li>
          <li><a href="#" class="hover:text-white transition-colors">Symptom Checker</a></li>
          <li><a href="#" class="hover:text-white transition-colors">Body Map</a></li>
          <li><a href="#" class="hover:text-white transition-colors">CPR Guide</a></li>
        </ul>
      </div>
      <div>
        <h4 class="font-semibold mb-4">Resources</h4>
        <ul class="space-y-2 text-sm text-zinc-400">
          <li><a href="#" class="hover:text-white transition-colors">Emergency Contacts</a></li>
          <li><a href="#" class="hover:text-white transition-colors">Hospital Locator</a></li>
          <li><a href="#" class="hover:text-white transition-colors">First Aid Kit</a></li>
          <li><a href="#" class="hover:text-white transition-colors">Training Materials</a></li>
        </ul>
      </div>
      <div>
        <h4 class="font-semibold mb-4">Company</h4>
        <ul class="space-y-2 text-sm text-zinc-400">
          <li><a href="#" class="hover:text-white transition-colors">About Us</a></li>
          <li><a href="#" class="hover:text-white transition-colors">Privacy Policy</a></li>
          <li><a href="#" class="hover:text-white transition-colors">Terms of Service</a></li>
          <li><a href="#" class="hover:text-white transition-colors">Contact</a></li>
        </ul>
      </div>
    </div>
    <div class="border-t border-zinc-800 pt-8 text-center text-sm text-zinc-500">
      <p>&copy; 2025 LifeLine Emergency First Aid System. All rights reserved.</p>
    </div>
  </div>
</footer>

<!-- Video Demo Modal -->
<div id="videoModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm flex items-center justify-center z-50 p-4 hidden">
  <div class="bg-[#18181B] border border-zinc-800 rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden">
    <!-- Modal Header -->
    <div class="flex items-center justify-between p-6 border-b border-zinc-800">
      <div>
        <h3 class="text-2xl font-bold text-white mb-2">LifeLine Demo Video</h3>
        <p class="text-zinc-400">See how LifeLine helps in emergency situations</p>
      </div>
      <button id="closeVideoModal" class="text-zinc-400 hover:text-white transition-colors">
        <i class="fa-solid fa-times text-xl"></i>
      </button>
    </div>
    
    <!-- Video Container -->
    <div class="relative aspect-video bg-black">
      <!-- YouTube Embed Placeholder -->
      <div id="videoContainer" class="w-full h-full flex items-center justify-center">
        <div class="text-center">
          <div class="mb-8">
            <i class="fa-solid fa-play-circle text-6xl text-red-500 mb-4"></i>
            <h4 class="text-xl font-semibold text-white mb-2">LifeLine Demo Video</h4>
            <p class="text-zinc-400 mb-6">Watch how LifeLine saves lives in critical moments</p>
          </div>
          
          <!-- Video Content Preview -->
          <div class="bg-[#09090B] border border-zinc-800 rounded-xl p-6 max-w-2xl mx-auto">
            <h5 class="text-lg font-semibold text-white mb-4">What you'll see in this demo:</h5>
            <div class="space-y-3 text-left">
              <div class="flex items-start gap-3">
                <i class="fa-solid fa-check-circle text-green-400 mt-1"></i>
                <div>
                  <strong class="text-white">Emergency Assessment:</strong>
                  <p class="text-sm text-zinc-400">Quick symptom checking and AI-powered triage</p>
                </div>
              </div>
              <div class="flex items-start gap-3">
                <i class="fa-solid fa-check-circle text-green-400 mt-1"></i>
                <div>
                  <strong class="text-white">Interactive Body Map:</strong>
                  <p class="text-sm text-zinc-400">Click-to-explore emergency guidance</p>
                </div>
              </div>
              <div class="flex items-start gap-3">
                <i class="fa-solid fa-check-circle text-green-400 mt-1"></i>
                <div>
                  <strong class="text-white">Step-by-Step First Aid:</strong>
                  <p class="text-sm text-zinc-400">Clear visual instructions for emergencies</p>
                </div>
              </div>
              <div class="flex items-start gap-3">
                <i class="fa-solid fa-check-circle text-green-400 mt-1"></i>
                <div>
                  <strong class="text-white">CPR Guidance:</strong>
                  <p class="text-sm text-zinc-400">Real-time metronome and audio cues</p>
                </div>
              </div>
              <div class="flex items-start gap-3">
                <i class="fa-solid fa-check-circle text-green-400 mt-1"></i>
                <div>
                  <strong class="text-white">Emergency Contacts:</strong>
                  <p class="text-sm text-zinc-400">One-tap emergency services access</p>
                </div>
              </div>
            </div>
            
            <div class="mt-6 p-4 bg-red-600/10 border border-red-600/20 rounded-lg">
              <p class="text-sm text-red-300">
                <i class="fa-solid fa-info-circle mr-2"></i>
                Demo video coming soon! This will showcase real emergency scenarios.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Modal Footer -->
    <div class="p-6 border-t border-zinc-800 bg-[#09090B]/50">
      <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="text-sm text-zinc-400">
          <i class="fa-solid fa-clock mr-2"></i>
          Duration: ~3 minutes
        </div>
        <div class="flex gap-3">
          <button id="closeVideoModalFooter" class="px-6 py-2 bg-zinc-800 hover:bg-zinc-700 rounded-lg transition-colors">
            Close
          </button>
          <a href="{{ route('register') }}" class="px-6 py-2 bg-red-600 hover:bg-red-500 rounded-lg transition-colors text-white font-medium">
            Try LifeLine Now
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
// Emergency Database
const emergencyDatabase = [
  {
    name: "Choking",
    keywords: ["choking", "can't breathe", "airway blocked", "suffocating"],
    severity: "critical",
    icon: "fa-lungs",
    color: "red",
    immediateAction: "Perform Heimlich maneuver",
    steps: [
      "Stand behind the person and wrap your arms around their waist",
      "Make a fist with one hand and place it above the navel",
      "Grasp fist with other hand and perform quick upward thrusts",
      "Continue until object is expelled or person becomes unconscious",
      "If unconscious, call emergency services and start CPR"
    ],
    emergencyCall: true
  },
  {
    name: "Cardiac Arrest",
    keywords: ["heart attack", "cardiac arrest", "no pulse", "unconscious", "not breathing"],
    severity: "critical",
    icon: "fa-heart",
    color: "red",
    immediateAction: "Start CPR immediately",
    steps: [
      "Call emergency services immediately",
      "Check for breathing and pulse",
      "Start chest compressions: 30 compressions at 100-120 BPM",
      "Give 2 rescue breaths if trained",
      "Continue 30:2 ratio until help arrives"
    ],
    emergencyCall: true
  },
  {
    name: "Severe Bleeding",
    keywords: ["bleeding", "blood loss", "cut", "wound", "hemorrhage"],
    severity: "critical",
    icon: "fa-droplet",
    color: "red",
    immediateAction: "Apply direct pressure",
    steps: [
      "Apply firm pressure with clean cloth or bandage",
      "Elevate the injured area above heart level",
      "Maintain pressure until bleeding stops",
      "Apply pressure bandage if available",
      "Call emergency services if bleeding doesn't stop"
    ],
    emergencyCall: false
  },
  {
    name: "Burns",
    keywords: ["burn", "scald", "heat burn", "chemical burn", "electrical burn"],
    severity: "moderate",
    icon: "fa-fire",
    color: "orange",
    immediateAction: "Cool the burn with water",
    steps: [
      "Remove from heat source immediately",
      "Cool burn with cool (not cold) water for 15-20 minutes",
      "Remove jewelry or tight clothing near burn",
      "Cover with sterile, non-stick bandage",
      "Seek medical attention for severe burns"
    ],
    emergencyCall: false
  },
  {
    name: "Fractures",
    keywords: ["fracture", "broken bone", "sprain", "dislocation", "injury"],
    severity: "moderate",
    icon: "fa-bone",
    color: "purple",
    immediateAction: "Immobilize the area",
    steps: [
      "Do not move the injured person unless necessary",
      "Apply ice to reduce swelling",
      "Immobilize with splint if available",
      "Keep the person comfortable and warm",
      "Seek medical attention promptly"
    ],
    emergencyCall: false
  },
  {
    name: "Snake Bite",
    keywords: ["snake bite", "venom", "snake", "bite"],
    severity: "critical",
    icon: "fa-spider",
    color: "green",
    immediateAction: "Call emergency services",
    steps: [
      "Call emergency services immediately",
      "Keep calm and immobilize the bitten area",
      "Remove tight clothing near bite area",
      "Do not cut wound or apply ice",
      "Note snake appearance if possible"
    ],
    emergencyCall: true
  },
  {
    name: "Heat Stroke",
    keywords: ["heat stroke", "overheating", "dehydration", "sunstroke"],
    severity: "critical",
    icon: "fa-temperature-high",
    color: "yellow",
    immediateAction: "Cool the person immediately",
    steps: [
      "Move to cool, shaded area",
      "Remove excess clothing",
      "Apply cool water to skin",
      "Fan the person to increase cooling",
      "Give cool water if conscious",
      "Call emergency services if severe"
    ],
    emergencyCall: false
  },
  {
    name: "Head Injury",
    keywords: ["head injury", "concussion", "head trauma", "brain injury"],
    severity: "critical",
    icon: "fa-brain",
    color: "pink",
    immediateAction: "Monitor consciousness",
    steps: [
      "Check for responsiveness and breathing",
      "Apply cold compress to reduce swelling",
      "Monitor for confusion or vomiting",
      "Do not move person if neck injury suspected",
      "Seek immediate medical attention"
    ],
    emergencyCall: true
  },
  {
    name: "Fainting",
    keywords: ["fainting", "unconscious", "passed out", "dizzy"],
    severity: "mild",
    icon: "fa-face-dizzy",
    color: "blue",
    immediateAction: "Lay person flat",
    steps: [
      "Lay person flat on their back",
      "Elevate legs above heart level",
      "Check for breathing",
      "Loosen tight clothing",
      "Monitor for 2-3 minutes",
      "Call emergency if not responsive"
    ],
    emergencyCall: false
  },
  {
    name: "Seizure",
    keywords: ["seizure", "convulsion", "epilepsy", "fit"],
    severity: "moderate",
    icon: "fa-bolt",
    color: "indigo",
    immediateAction: "Protect from injury",
    steps: [
      "Clear area of dangerous objects",
      "Place something soft under head",
      "Do not restrain person",
      "Time the seizure",
      "Turn on side if possible",
      "Call emergency if >5 minutes"
    ],
    emergencyCall: false
  },
  {
    name: "Poisoning",
    keywords: ["poison", "overdose", "toxic", "ingested poison"],
    severity: "critical",
    icon: "fa-skull-crossbones",
    color: "red",
    immediateAction: "Call poison control",
    steps: [
      "Call poison control immediately",
      "Do not induce vomiting unless instructed",
      "Save container or substance sample",
      "Monitor breathing and consciousness",
      "Follow medical instructions exactly"
    ],
    emergencyCall: true
  },
  {
    name: "Electric Shock",
    keywords: ["electric shock", "electrocution", "electrical injury"],
    severity: "critical",
    icon: "fa-bolt-lightning",
    color: "yellow",
    immediateAction: "Separate from power source",
    steps: [
      "Turn off power source if safe",
      "Use non-conductive object to separate person",
      "Check for breathing and pulse",
      "Call emergency services",
      "Treat burns if present",
      "Monitor for cardiac issues"
    ],
    emergencyCall: true
  }
];

function toggleMobileMenu() {
  const menu = document.getElementById('mobileMenu');
  menu.classList.toggle('hidden');
}

// Emergency Search Functionality
const searchInput = document.getElementById('emergencySearch');
const searchResults = document.getElementById('searchResults');

searchInput.addEventListener('input', function() {
  const query = this.value.toLowerCase().trim();
  
  if (query.length < 2) {
    searchResults.classList.add('hidden');
    return;
  }
  
  const results = emergencyDatabase.filter(emergency => 
    emergency.name.toLowerCase().includes(query) ||
    emergency.keywords.some(keyword => keyword.includes(query))
  );
  
  displaySearchResults(results);
});

function displaySearchResults(results) {
  if (results.length === 0) {
    searchResults.innerHTML = `
      <div class="p-4 text-center text-zinc-400">
        <i class="fa-solid fa-search mb-2 text-2xl"></i>
        <p>No emergencies found. Try different keywords.</p>
      </div>
    `;
  } else {
    searchResults.innerHTML = results.slice(0, 5).map(emergency => `
      <div class="p-4 border-b border-zinc-800 hover:bg-zinc-800/50 cursor-pointer transition-colors" onclick="showEmergencyDetails('${emergency.name}')">
        <div class="flex items-start gap-3">
          <div class="w-10 h-10 rounded-lg bg-${emergency.color}-600/20 flex items-center justify-center flex-shrink-0">
            <i class="fa-solid ${emergency.icon} text-${emergency.color}-400"></i>
          </div>
          <div class="flex-1">
            <div class="flex items-center gap-2 mb-1">
              <h4 class="font-semibold text-white">${emergency.name}</h4>
              <span class="px-2 py-1 bg-${emergency.severity === 'critical' ? 'red' : emergency.severity === 'moderate' ? 'orange' : 'blue'}-600/20 border border-${emergency.severity === 'critical' ? 'red' : emergency.severity === 'moderate' ? 'orange' : 'blue'}-600/30 rounded-full text-xs text-${emergency.severity === 'critical' ? 'red' : emergency.severity === 'moderate' ? 'orange' : 'blue'}-300">
                ${emergency.severity}
              </span>
            </div>
            <p class="text-sm text-zinc-400 mb-2">${emergency.immediateAction}</p>
            <div class="flex items-center gap-4 text-xs text-zinc-500">
              <span><i class="fa-solid fa-list-ol mr-1"></i>${emergency.steps.length} steps</span>
              ${emergency.emergencyCall ? '<span><i class="fa-solid fa-phone-volume mr-1"></i>Call emergency</span>' : ''}
            </div>
          </div>
        </div>
      </div>
    `).join('');
  }
  
  searchResults.classList.remove('hidden');
}

function showEmergencyDetails(emergencyName) {
  const emergency = emergencyDatabase.find(e => e.name === emergencyName);
  if (!emergency) return;
  
  const modal = document.createElement('div');
  modal.className = 'fixed inset-0 bg-black/80 backdrop-blur-sm flex items-center justify-center z-50 p-4';
  modal.innerHTML = `
    <div class="bg-[#18181B] border border-zinc-800 rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
      <div class="p-6">
        <div class="flex items-start justify-between mb-6">
          <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl bg-${emergency.color}-600/20 flex items-center justify-center">
              <i class="fa-solid ${emergency.icon} text-${emergency.color}-400 text-xl"></i>
            </div>
            <div>
              <h3 class="text-2xl font-bold text-white">${emergency.name}</h3>
              <span class="px-3 py-1 bg-${emergency.severity === 'critical' ? 'red' : emergency.severity === 'moderate' ? 'orange' : 'blue'}-600/20 border border-${emergency.severity === 'critical' ? 'red' : emergency.severity === 'moderate' ? 'orange' : 'blue'}-600/30 rounded-full text-sm text-${emergency.severity === 'critical' ? 'red' : emergency.severity === 'moderate' ? 'orange' : 'blue'}-300">
                ${emergency.severity.toUpperCase()} EMERGENCY
              </span>
            </div>
          </div>
          <button onclick="closeModal()" class="text-zinc-400 hover:text-white transition-colors">
            <i class="fa-solid fa-times text-xl"></i>
          </button>
        </div>
        
        <div class="bg-red-600/10 border border-red-600/20 rounded-xl p-4 mb-6">
          <div class="flex items-center gap-2 mb-2">
            <i class="fa-solid fa-exclamation-triangle text-red-400"></i>
            <h4 class="font-semibold text-red-300">IMMEDIATE ACTION</h4>
          </div>
          <p class="text-white font-medium">${emergency.immediateAction}</p>
        </div>
        
        <div class="mb-6">
          <h4 class="font-semibold text-white mb-3 flex items-center gap-2">
            <i class="fa-solid fa-list-ol text-blue-400"></i>
            Step-by-Step Instructions
          </h4>
          <div class="space-y-3">
            ${emergency.steps.map((step, index) => `
              <div class="flex gap-3">
                <div class="w-6 h-6 rounded-full bg-blue-600/20 flex items-center justify-center flex-shrink-0 mt-0.5">
                  <span class="text-xs text-blue-400 font-semibold">${index + 1}</span>
                </div>
                <p class="text-zinc-300 text-sm leading-relaxed">${step}</p>
              </div>
            `).join('')}
          </div>
        </div>
        
        ${emergency.emergencyCall ? `
          <div class="bg-red-600/10 border border-red-600/20 rounded-xl p-4">
            <div class="flex items-center gap-3">
              <i class="fa-solid fa-phone-volume text-red-400 text-xl"></i>
              <div>
                <h4 class="font-semibold text-red-300 mb-1">CALL EMERGENCY SERVICES</h4>
                <p class="text-zinc-300 text-sm">Dial 912 or your local emergency number immediately</p>
              </div>
            </div>
          </div>
        ` : ''}
        
        <div class="mt-6 flex gap-3">
          <button onclick="closeModal()" class="flex-1 px-4 py-2 bg-zinc-800 hover:bg-zinc-700 rounded-lg transition-colors">
            Close
          </button>
          <a href="{{ route('register') }}" class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-500 rounded-lg text-center font-semibold transition-colors">
            Get Full Access
          </a>
        </div>
      </div>
    </div>
  `;
  
  document.body.appendChild(modal);
}

function closeModal() {
  const modal = document.querySelector('.fixed.inset-0');
  if (modal) {
    modal.remove();
  }
  searchInput.value = '';
  searchResults.classList.add('hidden');
}

// Close search results when clicking outside
document.addEventListener('click', function(e) {
  if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
    searchResults.classList.add('hidden');
  }
});

// Smooth scrolling for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function (e) {
    e.preventDefault();
    const target = document.querySelector(this.getAttribute('href'));
    if (target) {
      target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  });
});

// Add scroll effect to navigation
window.addEventListener('scroll', function() {
  const nav = document.querySelector('nav');
  if (window.scrollY > 50) {
    nav.classList.add('bg-[#09090B]');
  } else {
    nav.classList.remove('bg-[#09090B]');
  }
});

// Video Modal functionality
document.addEventListener('DOMContentLoaded', function() {
  const openVideoModal = document.getElementById('openVideoModal');
  const closeVideoModal = document.getElementById('closeVideoModal');
  const closeVideoModalFooter = document.getElementById('closeVideoModalFooter');
  const videoModal = document.getElementById('videoModal');

  if (!openVideoModal || !closeVideoModal || !closeVideoModalFooter || !videoModal) {
    return;
  }

  // Open video modal
  openVideoModal.addEventListener('click', function(e) {
    e.preventDefault();
    videoModal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
  });

  // Close video modal
  function closeModal() {
    videoModal.classList.add('hidden');
    document.body.style.overflow = 'auto';
  }

  closeVideoModal.addEventListener('click', closeModal);
  closeVideoModalFooter.addEventListener('click', closeModal);

  // Close modal when clicking outside
  videoModal.addEventListener('click', function(e) {
    if (e.target === videoModal) {
      closeModal();
    }
  });

  // Close modal with Escape key
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !videoModal.classList.contains('hidden')) {
      closeModal();
    }
  });
});
</script>

</body>
</html>
