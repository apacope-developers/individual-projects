<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Dashboard - LifeLine</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'DM Sans',sans-serif;background:#09090B;color:#fafafa;min-height:100vh}
h1,h2,h3,h4,h5,h6{font-family:'Space Grotesk',sans-serif}
.bg-grid{background-image:radial-gradient(ellipse 80% 50% at 50% 0%,rgba(239,68,68,.06) 0%,transparent 60%),radial-gradient(ellipse 60% 40% at 80% 100%,rgba(45,212,191,.04) 0%,transparent 60%),linear-gradient(rgba(63,63,70,.15) 1px,transparent 1px),linear-gradient(90deg,rgba(63,63,70,.15) 1px,transparent 1px);background-size:100% 100%,100% 100%,40px 40px,40px 40px}
.card{background:#18181B;border:1px solid #27272A;border-radius:12px;transition:all .25s}
.card:hover{border-color:#3F3F46;transform:translateY(-2px);box-shadow:0 8px 24px rgba(0,0,0,.3)}
.card-s{background:#18181B;border:1px solid #27272A;border-radius:12px}
.quick-card{cursor:pointer;position:relative;overflow:hidden}
.quick-card::after{content:'';position:absolute;inset:0;background:linear-gradient(135deg,rgba(239,68,68,.08),transparent);opacity:0;transition:opacity .3s}
.quick-card:hover::after{opacity:1}
.sidebar{width:220px}.nav-item{transition:all .2s;position:relative;cursor:pointer}
.nav-item::before{content:'';position:absolute;left:0;top:50%;transform:translateY(-50%);width:3px;height:0;background:#EF4444;border-radius:0 4px 4px 0;transition:height .2s}
.nav-item.active::before{height:60%}.nav-item.active{background:rgba(239,68,68,.1);color:#EF4444}
.sos-fab{position:fixed;bottom:24px;right:24px;z-index:5000;width:56px;height:56px;border-radius:50%;background:#EF4444;color:white;border:none;font-size:18px;cursor:pointer;box-shadow:0 4px 20px rgba(239,68,68,.4);transition:all .3s}
.sos-fab:hover{transform:scale(1.1);box-shadow:0 6px 30px rgba(239,68,68,.6)}
.page{display:none;animation:fadeSlide .3s ease}.page.active{display:block}
@keyframes fadeSlide{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:translateY(0)}}
.modal-bg{position:fixed;inset:0;z-index:7000;background:rgba(0,0,0,.7);backdrop-filter:blur(8px);display:none;justify-content:center;align-items:center;padding:20px}
.modal-bg.open{display:flex}
.modal-box{background:#18181B;border:1px solid #2722A;border-radius:16px;max-width:560px;width:100%;max-height:80vh;overflow-y:auto;animation:modalIn .3s ease}
@keyframes modalIn{from{opacity:0;transform:scale(.95) translateY(10px)}to{opacity:1;transform:scale(1) translateY(0)}}
.sos-overlay{position:fixed;inset:0;z-index:9999;background:rgba(127,29,29,.95);backdrop-filter:blur(20px);display:none}
.sos-overlay.open{display:flex}
.sos-pulse-border{animation:sosBorder 1.5s ease-in-out infinite}
@keyframes sosBorder{0%,100%{box-shadow:inset 0 0 40px rgba(239,68,68,.3)}50%{box-shadow:inset 0 0 80px rgba(239,68,68,.6)}}
.toast{position:fixed;bottom:24px;right:24px;z-index:8000;padding:12px 20px;border-radius:10px;background:#27272A;border:1px solid #3F3F46;color:#FAFAFA;font-size:14px;animation:toastIn .3s ease,toastOut .3s ease 2.7s forwards;max-width:340px}
@keyframes toastIn{from{opacity:0;transform:translateX(40px)}to{opacity:1;transform:translateX(0)}}
@keyframes toastOut{from{opacity:1}to{opacity:0;transform:translateX(40px)}}
.sev-critical{background:rgba(239,68,68,.15);color:#FCA5A5;border:1px solid rgba(239,68,68,.3)}
.sev-urgent{background:rgba(249,115,22,.15);color:#FDBA74;border:1px solid rgba(249,115,22,.3)}
.sev-moderate{background:rgba(234,179,8,.15);color:#FDE047;border:1px solid rgba(234,179,8,.3)}
.sev-minor{background:rgba(34,197,94,.15);color:#86EFAC;border:1px solid rgba(34,197,94,.3)}
.step-dot{width:10px;height:10px;border-radius:50%;background:#3F3F46;transition:all .3s}
.step-dot.active{background:#EF4444;box-shadow:0 0 8px rgba(239,68,68,.5)}
.step-dot.done{background:#2DD4BF}
.search-input{background:#18181B;border:1px solid #272PA;border-radius:10px;padding:10px 16px 10px 42px;width:100%;outline:none;transition:border-color .2s}
.search-input:focus{border-color:#EF4444}.search-input::placeholder{color:#71717A}
.kit-check{appearance:none;width:20px;height:20px;border:2px solid #3F3F46;border-radius:6px;cursor:pointer;transition:all .2s;flex-shrink:0}
.kit-check:checked{background:#2DD4BF;border-color:#2DD4BF;background-image:url("data:image/svg+xml,%3Csvg viewBox='0 0 16 16' fill='%2309090B' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M12.207 4.793a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0l-2-2a1 1 0 011.414-1.414L6.5 9.086l4.293-4.293a1 1 0 011.414 0z'/%3E%3C/svg%3E")}
.kit-check:checked+span{text-decoration:line-through;color:#71717A}
.cpr-pulse{animation:cprPulse calc(60s/var(--bpm)) ease-in-out infinite}
@keyframes cprPulse{0%,100%{transform:scale(.85);opacity:.5}50%{transform:scale(1.1);opacity:1}}
@keyframes pulseSlow{0%,100%{transform:scale(1)}50%{transform:scale(1.05)}}
@keyframes pulseRing{0%,100%{box-shadow:0 0 0 0 rgba(239,68,68,.4)}70%{box-shadow:0 0 0 15px rgba(239,68,68,0)}}
.float-card{animation:floatCard 6s ease-in-out infinite}
@keyframes floatCard{0%,100%{transform:translateY(0)}50%{transform:translateY(-10px)}}
.body-zone{fill:rgba(45,212,191,.08);stroke:rgba(45,212,191,.25);stroke-width:1;cursor:pointer;transition:all .3s}
.body-zone:hover{fill:rgba(239,68,68,.2);stroke:#EF4444;stroke-width:1.5}
.body-outline{fill:none;stroke:#3F3F46;stroke-width:1.5}
@media(max-width:768px){.sidebar{display:none!important}.mob-bar{display:flex!important}.main-c{padding-bottom:80px!important}}
</style>
</head>
<body class="bg-grid">

<!-- SOS OVERLAY -->
<div id="sosOverlay" class="sos-overlay sos-pulse-border flex-col items-center justify-center text-center p-6">
<div class="mb-6"><div class="w-24 h-24 rounded-full bg-red-600/30 flex items-center justify-center mx-auto mb-4" style="animation:pulseSlow 1s ease-in-out infinite"><i class="fa-solid fa-triangle-exclamation text-5xl text-red-300"></i></div>
<h2 class="text-4xl font-bold text-red-100" style="text-shadow:0 0 20px rgba(239,68,68,.3)">SOS EMERGENCY</h2>
<p class="text-red-200/70 mt-2 text-lg">Stay calm. Help is on the way.</p></div>
<div class="flex flex-col gap-3 w-full max-w-xs mb-8">
<a href="tel:912" class="flex items-center justify-center gap-3 bg-red-600 hover:bg-red-500 text-white py-4 px-6 rounded-xl text-lg font-semibold transition-all"><i class="fa-solid fa-phone"></i> Call Emergency (912)</a>
<button onclick="closeSOS()" class="flex items-center justify-center gap-3 bg-white/10 hover:bg-white/15 text-white py-4 px-6 rounded-xl text-lg font-semibold transition-all"><i class="fa-solid fa-location-dot"></i> Share Location</button></div>
<div class="grid grid-cols-2 gap-3 w-full max-w-sm mb-6">
<button onclick="closeSOS();goTo('cpr')" class="card p-4 text-center hover:border-red-500/50 cursor-pointer"><i class="fa-solid fa-heart-pulse text-2xl text-red-400 mb-1"></i><p class="text-sm text-zinc-300">CPR</p></button>
<button onclick="closeSOS();goTo('guide')" class="card p-4 text-center hover:border-red-500/50 cursor-pointer"><i class="fa-solid fa-droplet text-2xl text-red-400 mb-1"></i><p class="text-sm text-zinc-300">Bleeding</p></button>
<button onclick="closeSOS();goTo('guide')" class="card p-4 text-center hover:border-red-500/50 cursor-pointer"><i class="fa-solid fa-lungs text-2xl text-red-400 mb-1"></i><p class="text-sm text-zinc-300">Choking</p></button>
<button onclick="closeSOS();goTo('guide')" class="card p-4 text-center hover:border-red-500/50 cursor-pointer"><i class="fa-solid fa-person-falling text-2xl text-red-400 mb-1"></i><p class="text-sm text-zinc-300">Unconscious</p></button></div>
<button onclick="closeSOS()" class="text-zinc-400 hover:text-white text-sm"><i class="fa-solid fa-xmark mr-1"></i> Exit Emergency Mode</button>
</div>

<!-- MODAL -->
<div id="modalBg" class="modal-bg" onclick="if(event.target===this)closeModal()"><div class="modal-box" id="modalBox"></div></div>

<!-- TOAST -->
<div id="toastWrap"></div>


<!-- LAYOUT -->
<div class="flex h-full">
<aside class="sidebar bg-black/50 border-r border-zinc-800/50 flex flex-col py-4 flex-shrink-0">
<div class="px-5 mt-auto">
      <div class="flex items-center gap-3 px-2">
        <div class="w-8 h-8 rounded-full bg-red-600/20 flex items-center justify-center">
          <i class="fa-solid fa-user-shield text-red-400 text-xs"></i>
        </div>
        <div>
          <p class="text-xs font-medium">{{ Auth::user()->name }}</p>
          @if(Auth::user()->is_admin)
          <p class="text-[11px] text-zinc-500">Administrator</p>
          @else
          <p class="text-[11px] text-zinc-500">User</p>
          @endif
        </div>
        @if(Auth::user()->is_admin)
          <div class="mt-3 px-2">
            <a href="/admin" class="flex items-center gap-2 px-3 py-2 bg-zinc-800 hover:bg-zinc-700 rounded-lg text-zinc-100 transition-colors">
              <i class="fa-solid fa-gauge-high w-5 text-center"></i>
              <span>Admin Panel</span>
            </a>
          </div>
        @endif
      </div>
</div>
<nav class="flex-1 flex flex-col gap-1 px-3" id="sideNav">
<button class="nav-item active flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm w-full text-left" onclick="goTo('dashboard')"><i class="fa-solid fa-house-medical w-5 text-center"></i><span>Dashboard</span></button>
<button class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm w-full text-left text-zinc-400" onclick="goTo('bodymap')"><i class="fa-solid fa-person w-5 text-center"></i><span>Body Map</span></button>
<button class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm w-full text-left text-zinc-400" onclick="goTo('checker')"><i class="fa-solid fa-stethoscope w-5 text-center"></i><span>Symptom Checker</span></button>
<button class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm w-full text-left text-zinc-400" onclick="goTo('guide')"><i class="fa-solid fa-book-medical w-5 text-center"></i><span>First Aid Guide</span></button>
<button class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm w-full text-left text-zinc-400" onclick="goTo('cpr')"><i class="fa-solid fa-heart-circle-check w-5 text-center"></i><span>CPR Assistant</span></button>
<button class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm w-full text-left text-zinc-400" onclick="goTo('kit')"><i class="fa-solid fa-kit-medical w-5 text-center"></i><span>Kit Inventory</span></button>
<button class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm w-full text-left text-zinc-400" onclick="goTo('contacts')"><i class="fa-solid fa-phone-volume w-5 text-center"></i><span>Contacts</span></button>
</nav>
<div class="px-5 mt-auto">
<div class="bg-[#18181B] border border-zinc-800 rounded-lg p-3">
<p class="text-[11px] text-zinc-500 mb-1">Emergency Number</p>
<a href="tel:912" class="text-red-400 font-bold text-lg hover:text-red-300 transition-colors">912</a>
</div>
<div class="px-5 mt-2">
<p class="text-[11px] text-zinc-600">Logged in as:</p>
<p class="text-[11px text-teal-400 font-medium truncate">{{ Auth::user()->name }}</p>
</div>
<form method="POST" action="/logout" class="mt-3 px-2">
@csrf
<button type="submit" class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm w-full text-left text-red-400 hover:bg-red-600/10"><i class="fa-solid fa-right-from-bracket w-5 text-center"></i><span>Logout</span></button>
</form>
</div>
</aside>

<main class="flex-1 overflow-y-auto main-c p-6 md:p-8">

<!-- DASHBOARD -->
<div class="page active" id="pg-dashboard">
<div class="mb-8"><h2 class="text-3xl font-bold mb-1">Welcome, {{ Auth::user()->name }}</h2><p class="text-zinc-400">Here is your emergency toolkit overview</p></div>

<!-- Quick Emergency Search -->
<div class="mb-8">
  <div class="bg-[#18181B]/80 backdrop-blur-xl border border-zinc-800 rounded-2xl p-6">
    <div class="flex items-center justify-between mb-4">
      <div class="flex items-center gap-3">
        <i class="fa-solid fa-search text-red-400 text-lg"></i>
        <h3 class="text-lg font-semibold">AI-Powered Emergency Search</h3>
        <span class="px-2 py-1 bg-red-600/20 border border-red-600/30 rounded-full text-xs text-red-300">AI Enhanced</span>
        <span class="px-2 py-1 bg-blue-600/20 border border-blue-600/30 rounded-full text-xs text-blue-300">Voice Enabled</span>
      </div>
      
      <!-- AI/Offline Toggle -->
      <div class="flex items-center gap-2">
        <span class="text-xs text-zinc-400">AI</span>
        <button 
          id="searchModeToggle" 
          class="relative w-12 h-6 bg-green-600 rounded-full transition-colors"
          onclick="toggleSearchMode()"
          title="Toggle between Free AI and offline mode"
        >
          <div id="searchModeSlider" class="absolute top-1 left-1 w-4 h-4 bg-white rounded-full transition-transform"></div>
        </button>
        <span class="text-xs text-zinc-400">Offline</span>
      </div>
    </div>
    <div class="relative">
      <input 
        type="text" 
        id="emergencySearch" 
        placeholder="Describe symptoms or speak: 'chest pain', 'bleeding', 'choking'..."
        class="search-input text-lg pr-12"
        autocomplete="off"
      >
      <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-zinc-500"></i>
      <button 
        id="voiceSearchBtn" 
        class="absolute right-3 top-1/2 -translate-y-1/2 w-8 h-8 rounded-lg bg-red-600/20 hover:bg-red-600/30 flex items-center justify-center transition-colors"
        onclick="toggleVoiceSearch()"
        title="Click to speak your emergency"
      >
        <i class="fa-solid fa-microphone text-red-400" id="voiceIcon"></i>
      </button>
      <div id="searchResults" class="absolute top-full left-0 right-0 mt-2 bg-[#18181B]/95 backdrop-blur-xl border border-zinc-800 rounded-xl shadow-2xl hidden z-50 max-h-96 overflow-y-auto">
      </div>
    </div>
    <div class="flex items-center justify-between mt-3">
      <p id="searchModeInfo" class="text-zinc-500 text-xs">
        <i class="fa-solid fa-brain text-green-400 mr-1"></i>
        Free AI provides intelligent first aid recommendations
      </p>
      <div id="voiceStatus" class="text-xs text-zinc-400 hidden">
        <i class="fa-solid fa-circle text-red-400 animate-pulse mr-1"></i>
        <span id="voiceStatusText">Listening...</span>
      </div>
    </div>
  </div>
</div>

<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
<div class="card-s p-4"><p class="text-zinc-500 text-xs mb-1">Conditions</p><p class="text-2xl font-bold" style="color:#2DD4BF">12</p></div>
<div class="card-s p-4"><p class="text-zinc-500 text-xs mb-1">Body Zones</p><p class="text-2xl font-bold" style="color:#2DD4BF">7</p></div>
<div class="card-s p-4"><p class="text-zinc-500 text-xs mb-1">Kit Items</p><p class="text-2xl font-bold" style="color:#2DD4BF">25</p></div>
<div class="card-s p-4"><p class="text-zinc-500 text-xs mb-1">Role</p><p class="text-2xl font-bold" style="color:#F97316">User</p></div>
</div>
<h3 class="text-lg font-semibold mb-4">Quick Actions</h3>
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
<div class="card quick-card p-4" onclick="goTo('guide')"><div class="w-10 h-10 rounded-lg flex items-center justify-center mb-3" style="background:rgba(239,68,68,.12)"><i class="fa-solid fa-heart-crack" style="color:#EF4444"></i></div><p class="text-sm font-semibold">Cardiac Arrest</p></div>
<div class="card quick-card p-4" onclick="goTo('guide')"><div class="w-10 h-10 rounded-lg flex items-center justify-center mb-3" style="background:rgba(239,68,68,.12)"><i class="fa-solid fa-heart" style="color:#EF4444"></i></div><p class="text-sm font-semibold">Heart Attack</p></div>
<div class="card quick-card p-4" onclick="goTo('guide')"><div class="w-10 h-10 rounded-lg flex items-center justify-center mb-3" style="background:rgba(249,115,22,.12)"><i class="fa-solid fa-lungs" style="color:#F97316"></i></div><p class="text-sm font-semibold">Choking</p></div>
<div class="card quick-card p-4" onclick="goTo('guide')"><div class="w-10 h-10 rounded-lg flex items-center justify-center mb-3" style="background:rgba(239,68,68,.12)"><i class="fa-solid fa-droplet" style="color:#EF4444"></i></div><p class="text-sm font-semibold">Severe Bleeding</p></div>
<div class="card quick-card p-4" onclick="goTo('guide')"><div class="w-10 h-10 rounded-lg flex items-center justify-center mb-3" style="background:rgba(239,68,68,.12)"><i class="fa-solid fa-brain" style="color:#EF4444"></i></div><p class="text-sm font-semibold">Stroke</p></div>
<div class="card quick-card p-4" onclick="goTo('guide')"><div class="w-10 h-10 rounded-lg flex items-center justify-center mb-3" style="background:rgba(239,68,68,.12)"><i class="fa-solid fa-syringe" style="color:#EF4444"></i></div><p class="text-sm font-semibold">Anaphylaxis</p></div>
<div class="card quick-card p-4" onclick="goTo('cpr')"><div class="w-10 h-10 rounded-lg flex items-center justify-center mb-3" style="background:rgba(45,212,191,.12)"><i class="fa-solid fa-heart-pulse" style="color:#2DD4BF"></i></div><p class="text-sm font-semibold">Start CPR</p></div>
<div class="card quick-card p-4" onclick="goTo('bodymap')"><div class="w-10 h-10 rounded-lg flex items-center justify-center mb-3" style="background:rgba(45,212,191,.12)"><i class="fa-solid fa-person" style="color:#2DD4BF"></i></div><p class="text-sm font-semibold">Body Map</p></div>
</div>
<h3 class="text-lg font-semibold mb-4">DRABC Protocol</h3>
<div class="grid grid-cols-2 md:grid-cols-5 gap-3">
<div class="card-s p-4 text-center"><div class="w-10 h-10 rounded-full bg-red-600/20 flex items-center justify-center mx-auto mb-2 text-red-400 font-bold">D</div><p class="text-sm font-semibold">Danger</p><p class="text-xs text-zinc-500 mt-1">Check hazards</p></div>
<div class="card-s p-4 text-center"><div class="w-10 h-10 rounded-full bg-orange-600/20 flex items-center justify-center mx-auto mb-2 text-orange-400 font-bold">R</div><p class="text-sm font-semibold">Response</p><p class="text-xs text-zinc-500 mt-1">Check consciousness</p></div>
<div class="card-s p-4 text-center"><div class="w-10 h-10 rounded-full bg-yellow-600/20 flex items-center justify-center mx-auto mb-2 text-yellow-400 font-bold">A</div><p class="text-sm font-semibold">Airway</p><p class="text-xs text-zinc-500 mt-1">Open and clear</p></div>
<div class="card-s p-4 text-center"><div class="w-10 h-10 rounded-full bg-teal-600/20 flex items-center justify-center mx-auto mb-2 text-teal-400 font-bold">B</div><p class="text-sm font-semibold">Breathing</p><p class="text-xs text-zinc-500 mt-1">Look, listen, feel</p></div>
<div class="card-s p-4 text-center"><div class="w-10 h-10 rounded-full bg-green-600/20 flex items-center justify-center mx-auto mb-2 text-green-400 font-bold">C</div><p class="text-sm font-semibold">Circulation</p><p class="text-xs text-zinc-500 mt-1">Pulse, bleeding</p></div>
</div>
</div>

<!-- BODY MAP -->
<div class="page" id="pg-bodymap">
<div class="mb-6"><h2 class="text-3xl font-bold mb-1">Interactive Body Map</h2><p class="text-zinc-400">Click on a body region to see related conditions</p></div>
<div class="flex flex-col lg:flex-row gap-6">
<div class="card-s p-6 flex-shrink-0 flex items-center justify-center" style="min-height:440px">
<svg viewBox="0 0 200 440" width="220" height="480">
<g class="body-outline"><ellipse cx="100" cy="42" rx="24" ry="30"/><rect x="92" y="72" width="16" height="16" rx="5"/><path d="M62,88 Q60,88 59,90 L56,192 Q55,196 60,196 L140,196 Q145,196 144,192 L141,90 Q140,88 138,88 Z"/><path d="M59,92 L38,100 L24,172 L36,175 L48,112 L59,106 Z"/><path d="M141,92 L162,100 L176,172 L164,175 L152,112 L141,106 Z"/><path d="M60,196 L52,300 L44,388 L56,390 L66,305 L86,305 L86,196 Z"/><path d="M140,196 L148,300 L156,388 L144,390 L134,305 L114,305 L114,196 Z"/></g>
<ellipse cx="100" cy="42" rx="30" ry="36" class="body-zone" onclick="showZone('head')"/>
<rect x="57" y="86" width="86" height="52" rx="6" class="body-zone" onclick="showZone('chest')"/>
<rect x="57" y="140" width="86" height="56" rx="6" class="body-zone" onclick="showZone('abdomen')"/>
<rect x="22" y="90" width="40" height="88" rx="12" class="body-zone" onclick="showZone('left-arm')" transform="rotate(-8,42,90)"/>
<rect x="138" y="90" width="40" height="88" rx="12" class="body-zone" onclick="showZone('right-arm')" transform="rotate(8,158,90)"/>
<rect x="40" y="196" width="50" height="200" rx="12" class="body-zone" onclick="showZone('left-leg')" transform="rotate(2,65,196)"/>
<rect x="110" y="196" width="50" height="200" rx="12" class="body-zone" onclick="showZone('right-leg')" transform="rotate(-2,135,196)"/>
<!-- Hands -->
<ellipse cx="28" cy="178" rx="12" ry="18" class="body-zone" onclick="showZone('left-hand')" transform="rotate(-15,28,178)"/>
<ellipse cx="172" cy="178" rx="12" ry="18" class="body-zone" onclick="showZone('right-hand')" transform="rotate(15,172,178)"/>
<!-- Feet -->
<ellipse cx="65" cy="390" rx="15" ry="20" class="body-zone" onclick="showZone('left-foot')"/>
<ellipse cx="135" cy="390" rx="15" ry="20" class="body-zone" onclick="showZone('right-foot')"/>
</svg>
</div>
<div class="flex-1" id="zonePanel"><div class="card-s p-8 text-center h-full flex flex-col items-center justify-center"><i class="fa-solid fa-hand-pointer text-4xl text-zinc-600 mb-4"></i><p class="text-zinc-400 text-lg">Select a body region to view related conditions</p></div></div>
</div>
</div>

<!-- SYMPTOM CHECKER -->
<div class="page" id="pg-checker">
<div class="mb-6"><h2 class="text-3xl font-bold mb-1">Smart Symptom Checker</h2><p class="text-zinc-400">Answer a few questions to get a triage assessment</p></div>
<div class="max-w-2xl">
<div class="flex items-center gap-2 mb-6"><div class="step-dot active"></div><div class="h-px flex-1 bg-zinc-800"></div><div class="step-dot"></div><div class="h-px flex-1 bg-zinc-800"></div><div class="step-dot"></div></div>
<div class="card-s p-6 mb-4"><p class="text-xs text-zinc-500 mb-1">Question 1</p><h3 class="font-semibold text-lg">What is the main problem?</h3></div>
<div class="space-y-3">
<button onclick="checkerNext('chest')" class="card w-full p-4 flex items-center gap-4 text-left hover:border-red-500/30"><div class="w-10 h-10 rounded-lg bg-zinc-800 flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-heart text-zinc-400"></i></div><span class="text-sm font-medium">Chest pain or discomfort</span><i class="fa-solid fa-chevron-right text-zinc-600 ml-auto text-xs"></i></button>
<button onclick="checkerNext('breathing')" class="card w-full p-4 flex items-center gap-4 text-left hover:border-red-500/30"><div class="w-10 h-10 rounded-lg bg-zinc-800 flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-lungs text-zinc-400"></i></div><span class="text-sm font-medium">Difficulty breathing</span><i class="fa-solid fa-chevron-right text-zinc-600 ml-auto text-xs"></i></button>
<button onclick="checkerNext('bleeding')" class="card w-full p-4 flex items-center gap-4 text-left hover:border-red-500/30"><div class="w-10 h-10 rounded-lg bg-zinc-800 flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-droplet text-zinc-400"></i></div><span class="text-sm font-medium">Bleeding or wound</span><i class="fa-solid fa-chevron-right text-zinc-600 ml-auto text-xs"></i></button>
<button onclick="checkerNext('unconscious')" class="card w-full p-4 flex items-center gap-4 text-left hover:border-red-500/30"><div class="w-10 h-10 rounded-lg bg-zinc-800 flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-person-falling text-zinc-400"></i></div><span class="text-sm font-medium">Person is unconscious</span><i class="fa-solid fa-chevron-right text-zinc-600 ml-auto text-xs"></i></button>
<button onclick="checkerNext('burn')" class="card w-full p-4 flex items-center gap-4 text-left hover:border-red-500/30"><div class="w-10 h-10 rounded-lg bg-zinc-800 flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-fire text-zinc-400"></i></div><span class="text-sm font-medium">Burn or scald</span><i class="fa-solid fa-chevron-right text-zinc-600 ml-auto text-xs"></i></button>
<button onclick="checkerNext('seizure')" class="card w-full p-4 flex items-center gap-4 text-left hover:border-red-500/30"><div class="w-10 h-10 rounded-lg bg-zinc-800 flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-bolt text-zinc-400"></i></div><span class="text-sm font-medium">Seizure</span><i class="fa-solid fa-chevron-right text-zinc-600 ml-auto text-xs"></i></button>
<button onclick="checkerNext('fracture')" class="card w-full p-4 flex items-center gap-4 text-left hover:border-red-500/30"><div class="w-10 h-10 rounded-lg bg-zinc-800 flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-bone text-zinc-400"></i></div><span class="text-sm font-medium">Suspected broken bone</span><i class="fa-solid fa-chevron-right text-zinc-600 ml-auto text-xs"></i></button>
<button onclick="checkerNext('allergic')" class="card w-full p-4 flex items-center gap-4 text-left hover:border-red-500/30"><div class="w-10 h-10 rounded-lg bg-zinc-800 flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-syringe text-zinc-400"></i></div><span class="text-sm font-medium">Allergic reaction</span><i class="fa-solid fa-chevron-right text-zinc-600 ml-auto text-xs"></i></button>
</div>
</div>
</div>

<!-- FIRST AID GUIDE -->
<div class="page" id="pg-guide">
<div class="mb-6"><h2 class="text-3xl font-bold mb-1">Comprehensive First Aid Guide</h2><p class="text-zinc-400">Complete emergency procedures for all known medical conditions worldwide</p></div>
<div class="relative mb-6"><i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-zinc-500"></i><input type="text" class="search-input" placeholder="Search 50+ emergency conditions..." oninput="filterGuide(this.value)"></div>
<div class="flex flex-wrap gap-2 mb-6" id="guideFilters"><button onclick="filterCat('all')" class="px-3 py-1.5 rounded-lg text-xs font-medium bg-red-600/20 text-red-400 border border-red-600/30">All</button><button onclick="filterCat('cardiac')" class="px-3 py-1.5 rounded-lg text-xs font-medium bg-zinc-800 text-zinc-400 border border-transparent hover:border-zinc-700">Cardiac</button><button onclick="filterCat('breathing')" class="px-3 py-1.5 rounded-lg text-xs font-medium bg-zinc-800 text-zinc-400 border border-transparent hover:border-zinc-700">Breathing</button><button onclick="filterCat('wounds')" class="px-3 py-1.5 rounded-lg text-xs font-medium bg-zinc-800 text-zinc-400 border border-transparent hover:border-zinc-700">Wounds</button><button onclick="filterCat('neurological')" class="px-3 py-1.5 rounded-lg text-xs font-medium bg-zinc-800 text-zinc-400 border border-transparent hover:border-zinc-700">Neurological</button><button onclick="filterCat('musculoskeletal')" class="px-3 py-1.5 rounded-lg text-xs font-medium bg-zinc-800 text-zinc-400 border border-transparent hover:border-zinc-700">Bones & Joints</button><button onclick="filterCat('allergic')" class="px-3 py-1.5 rounded-lg text-xs font-medium bg-zinc-800 text-zinc-400 border border-transparent hover:border-zinc-700">Allergic</button><button onclick="filterCat('environmental')" class="px-3 py-1.5 rounded-lg text-xs font-medium bg-zinc-800 text-zinc-400 border border-transparent hover:border-zinc-700">Environmental</button><button onclick="filterCat('toxic')" class="px-3 py-1.5 rounded-lg text-xs font-medium bg-zinc-800 text-zinc-400 border border-transparent hover:border-zinc-700">Poisoning</button><button onclick="filterCat('facial')" class="px-3 py-1.5 rounded-lg text-xs font-medium bg-zinc-800 text-zinc-400 border border-transparent hover:border-zinc-700">Head & Face</button><button onclick="filterCat('abdominal')" class="px-3 py-1.5 rounded-lg text-xs font-medium bg-zinc-800 text-zinc-400 border border-transparent hover:border-zinc-700">Abdominal</button><button onclick="filterCat('medical')" class="px-3 py-1.5 rounded-lg text-xs font-medium bg-zinc-800 text-zinc-400 border border-transparent hover:border-zinc-700">Medical</button><button onclick="filterCat('obstetric')" class="px-3 py-1.5 rounded-lg text-xs font-medium bg-zinc-800 text-zinc-400 border border-transparent hover:border-zinc-700">Pregnancy</button><button onclick="filterCat('mental')" class="px-3 py-1.5 rounded-lg text-xs font-medium bg-zinc-800 text-zinc-400 border border-transparent hover:border-zinc-700">Mental Health</button></div>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="guideGrid"></div>
</div>

<!-- CPR ASSISTANT -->
<div class="page" id="pg-cpr">
<div class="mb-6"><h2 class="text-3xl font-bold mb-1">CPR Assistant</h2><p class="text-zinc-400">Real-time metronome and compression counter</p></div>
<div class="flex flex-col lg:flex-row gap-6">
<div class="flex-1"><div class="card-s p-8 flex flex-col items-center">
<div class="relative w-48 h-48 mb-6"><div class="absolute inset-0 rounded-full border-4 border-zinc-800"></div><div class="absolute inset-3 rounded-full border-2 border-zinc-700/50"></div><div class="absolute inset-0 rounded-full bg-zinc-800/30 flex items-center justify-center" id="cprPulse"><div class="text-center"><p class="text-5xl font-bold" id="cprCount">0</p><p class="text-xs text-zinc-400 mt-1">compressions</p></div></div></div>
<div class="flex items-center gap-4 mb-4"><button onclick="adjBPM(-5)" class="w-10 h-10 rounded-lg bg-zinc-800 hover:bg-zinc-700 flex items-center justify-center"><i class="fa-solid fa-minus text-sm"></i></button><div class="text-center min-w-[120px]"><p class="text-3xl font-bold text-red-400" id="cprBPM">110</p><p class="text-xs text-zinc-500">BPM</p></div><button onclick="adjBPM(5)" class="w-10 h-10 rounded-lg bg-zinc-800 hover:bg-zinc-700 flex items-center justify-center"><i class="fa-solid fa-plus text-sm"></i></button></div>
<div class="flex items-center gap-2 mb-4 text-sm"><span class="px-3 py-1 rounded-full bg-zinc-800 text-zinc-300" id="cprPhase">Ready</span><span class="text-zinc-500">Ratio: 30:2</span></div>
<div class="flex gap-3"><button onclick="toggleCPR()" id="cprBtn" class="px-8 py-3 bg-red-600 hover:bg-red-500 rounded-xl font-semibold"><i class="fa-solid fa-play mr-2"></i>Start</button><button onclick="resetCPR()" class="px-6 py-3 bg-zinc-800 hover:bg-zinc-700 rounded-xl font-semibold"><i class="fa-solid fa-rotate-right mr-2"></i>Reset</button></div>
</div></div>
<div class="flex-1"><div class="card-s p-6"><h3 class="font-semibold text-lg mb-4">CPR Steps</h3>
<ol class="space-y-3 text-sm"><li class="flex gap-3"><span class="w-6 h-6 rounded-full bg-red-600/20 text-red-400 flex items-center justify-center flex-shrink-0 text-xs font-bold">1</span><span><strong class="text-zinc-200">Check safety</strong></span></li><li class="flex gap-3"><span class="w-6 h-6 rounded-full bg-red-600/20 text-red-400 flex items-center justify-center flex-shrink-0 text-xs font-bold">2</span><span><strong class="text-zinc-200">Check responsiveness</strong></span></li><li class="flex gap-3"><span class="w-6 h-6 rounded-full bg-red-600/20 text-red-400 flex items-center justify-center flex-shrink-0 text-xs font-bold">3</span><span><strong class="text-zinc-200">Call 912</strong></span></li><li class="flex gap-3"><span class="w-6 h-6 rounded-full bg-red-600/20 text-red-400 flex items-center justify-center flex-shrink-0 text-xs font-bold">4</span><span><strong class="text-zinc-200">Hand position</strong></span></li><li class="flex gap-3"><span class="w-6 h-6 rounded-full bg-red-600/20 text-red-400 flex items-center justify-center flex-shrink-0 text-xs font-bold">5</span><span><strong class="text-zinc-200">Compress</strong> - At least 2 inches, 100-120 BPM.</span></li><li class="flex gap-3"><span class="w-6 h-6 rounded-full bg-red-600/20 text-red-400 flex items-center justify-center flex-shrink-0 text-xs font-bold">6</span><span><strong class="text-zinc-200">Full recoil</strong></span></li><li class="flex gap-3"><span class="w-6 h-6 rounded-full bg-red-600/20 text-red-400 flex items-center justify-center flex-shrink-0 text-xs font-bold">7</span><span><strong class="text-zinc-200">Rescue breaths</strong> - 2 breaths after 30 compressions.</span></li><li class="flex gap-3"><span class="w-6 h-6 rounded-full bg-red-600/20 text-red-400 flex items-center justify-center flex-shrink-0 text-xs font-bold">8</span><span><strong class="text-zinc-200">Continue</strong> - Repeat 30:2 until help arrives.</span></li></ol>
<div class="mt-6 p-4 rounded-lg bg-red-600/10 border border-red-600/20"><p class="text-sm text-red-300"><i class="fa-solid fa-circle-info mr-2"></i><strong>Remember:</strong> Push hard, push fast, allow full recoil, minimize interruptions.</p></div></div></div>
</div>
</div>

<!-- KIT -->
<div class="page" id="pg-kit">
<div class="mb-6"><h2 class="text-3xl font-bold mb-1">Kit Inventory</h2><p class="text-zinc-400">Track your first aid supplies</p></div>
<div id="kitContainer"></div>
</div>

<!-- CONTACTS -->
<div class="page" id="pg-contacts">
<div class="mb-6"><h2 class="text-3xl font-bold mb-1">Emergency Contacts</h2><p class="text-zinc-400">One-tap access to emergency numbers</p></div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="contactsGrid"></div>
</div>

</main>
</div>

<!-- MOBILE BAR -->
<div class="mob-bar fixed bottom-0 left-0 right-0 bg-black/90 border-t border-zinc-800/50 backdrop-blur-lg z-[4000] px-2 py-2 justify-around items-center" style="display:none">
<button onclick="goTo('dashboard')" class="flex flex-col items-center gap-1 px-3 py-1 rounded-lg text-red-400"><i class="fa-solid fa-house-medical text-lg"></i><span class="text-[10px]">Home</span></button>
<button onclick="goTo('bodymap')" class="flex flex-col items-center gap-1 px-3 py-1 rounded-lg text-zinc-500"><i class="fa-solid fa-person text-lg"></i><span class="text-[10px]">Body</span></button>
<button onclick="goTo('checker')" class="flex flex-col items-center gap-1 px-3 py-1 rounded-lg text-zinc-500"><i class="fa-solid fa-stethoscope text-lg"></i><span class="text-[10px]">Check</span></button>
<button onclick="goTo('guide')" class="flex flex-col items-center gap-1 px-3 py-1 rounded-lg text-zinc-500"><i class="fa-solid fa-book-medical text-lg"></i><span class="text-[10px]">Guide</span></button>
<button onclick="goTo('cpr')" class="flex flex-col items-center gap-1 px-3 py-1 rounded-lg text-zinc-500"><i class="fa-solid fa-heart-pulse text-lg"></i><span class="text-[10px]">CPR</span></button>
</div>

<script>
// Load comprehensive emergency conditions from backend
var CONDITIONS = [];
var CATEGORIES = {};

// Initialize comprehensive emergency database
async function loadEmergencyData() {
    try {
        const response = await fetch('/api/emergency-conditions');
        const data = await response.json();
        CONDITIONS = data.conditions;
        CATEGORIES = data.categories;
        
        // Initialize UI after data loads
        initializeUI();
    } catch (error) {
        console.error('Failed to load emergency data:', error);
        // Fallback to basic conditions if API fails
        CONDITIONS = [
            {id:'cardiac-arrest',name:'Cardiac Arrest',icon:'fa-heart-crack',severity:'critical',category:'cardiac',summary:'Sudden loss of heart function.',steps:['Call 912','Begin CPR: 30 compressions to 2 breaths','Push hard and fast','Use AED if available','Continue CPR until help arrives'],dos:['Start CPR immediately','Use AED as soon as possible'],donts:['Do not delay CPR','Do not stop CPR once started'],call912:true}
        ];
        initializeUI();
    }
}

function initializeUI() {
    // Only render if data is loaded
    if (CONDITIONS.length > 0) {
        renderGuide();
        renderContacts();
        renderKit();
        console.log('Emergency data loaded:', CONDITIONS.length + ' conditions');
    } else {
        console.log('No emergency data available');
    }
}

// Load emergency data when page loads
document.addEventListener('DOMContentLoaded', function() {
    loadEmergencyData();
});

var ZONES={head:{label:'Head',color:'#EF4444',conditions:[{name:'Concussion',severity:'urgent',desc:'Brain injury from impact.',action:'Seek medical attention. Do not let person sleep for first few hours.'},{name:'Stroke',severity:'critical',desc:'Sudden weakness on one side.',action:'Call 912 immediately.'},{name:'Eye Injury',severity:'moderate',desc:'Foreign object or trauma to the eye.',action:'Do not rub. Flush with water 15 min.'},{name:'Nosebleed',severity:'minor',desc:'Bleeding from nostrils.',action:'Sit upright, lean forward. Pinch nose 10 min.'}]},chest:{label:'Chest',color:'#EF4444',conditions:[{name:'Heart Attack',severity:'critical',desc:'Chest pain, shortness of breath.',action:'Call 912. Give aspirin. Be ready for CPR.'},{name:'Cardiac Arrest',severity:'critical',desc:'No pulse, no breathing.',action:'Call 912. Begin CPR immediately.'},{name:'Rib Fracture',severity:'urgent',desc:'Sharp pain when breathing.',action:'Seek medical attention.'}]},abdomen:{label:'Abdomen',color:'#F97316',conditions:[{name:'Internal Bleeding',severity:'critical',desc:'Rigid abdomen, pale skin.',action:'Call 912. Lay flat, keep warm.'},{name:'Appendicitis',severity:'urgent',desc:'Pain moving to lower right.',action:'Call 912. Do NOT give food.'},{name:'Abdominal Wound',severity:'critical',desc:'Penetrating wound.',action:'Call 912. Do NOT push organs back.'}]},'left-arm':{label:'Left Arm',color:'#2DD4BF',conditions:[{name:'Fracture',severity:'urgent',desc:'Pain, swelling, deformity.',action:'Immobilize with sling and splint.'},{name:'Severe Cut',severity:'urgent',desc:'Heavy bleeding.',action:'Apply direct pressure. Elevate arm.'},{name:'Dislocation',severity:'urgent',desc:'Joint out of position.',action:'Do NOT pop back in place.'}]},'right-arm':{label:'Right Arm',color:'#2DD4BF',conditions:[{name:'Fracture',severity:'urgent',desc:'Pain, swelling.',action:'Immobilize with sling and splint.'},{name:'Severe Cut',severity:'urgent',desc:'Heavy bleeding.',action:'Apply direct pressure.'},{name:'Dislocation',severity:'urgent',desc:'Joint out of position.',action:'Do NOT pop back in place.'}]},'left-leg':{label:'Left Leg',color:'#2DD4BF',conditions:[{name:'Fracture',severity:'urgent',desc:'Deformity, cannot bear weight.',action:'Do not move. Immobilize.'},{name:'Sprain',severity:'moderate',desc:'Pain, swelling.',action:'RICE: Rest, Ice, Compression, Elevation.'},{name:'Snake Bite',severity:'urgent',desc:'Puncture marks, pain.',action:'Keep still. Call 912.'}]},'right-leg':{label:'Right Leg',color:'#2DD4BF',conditions:[{name:'Fracture',severity:'urgent',desc:'Deformity.',action:'Do not move.'},{name:'Sprain',severity:'moderate',desc:'Pain, swelling.',action:'RICE: Rest, Ice, Compression, Elevation.'},{name:'Snake Bite',severity:'urgent',desc:'Puncture marks.',action:'Keep still. Call 912.'}]},'left-hand':{label:'Left Hand',color:'#8B5CF6',conditions:[{name:'Hand Fracture',severity:'urgent',desc:'Break in hand bones.',action:'Immobilize with splint. Elevate hand. Seek medical attention.'},{name:'Severe Cut',severity:'moderate',desc:'Deep cut on hand.',action:'Clean wound. Apply pressure. Bandage. Update tetanus if needed.'},{name:'Burn',severity:'moderate',desc:'Burn to hand.',action:'Cool with water 20 min. Cover with sterile dressing. Seek help for severe burns.'},{name:'Sprain',severity:'minor',desc:'Ligament injury.',action:'RICE: Rest, Ice, Compression, Elevation.'},{name:'Foreign Object',severity:'moderate',desc:'Object embedded in hand.',action:'Do not remove. Bandage around object. Seek medical attention.'}]},'right-hand':{label:'Right Hand',color:'#8B5CF6',conditions:[{name:'Hand Fracture',severity:'urgent',desc:'Break in hand bones.',action:'Immobilize with splint. Elevate hand. Seek medical attention.'},{name:'Severe Cut',severity:'moderate',desc:'Deep cut on hand.',action:'Clean wound. Apply pressure. Bandage. Update tetanus if needed.'},{name:'Burn',severity:'moderate',desc:'Burn to hand.',action:'Cool with water 20 min. Cover with sterile dressing. Seek help for severe burns.'},{name:'Sprain',severity:'minor',desc:'Ligament injury.',action:'RICE: Rest, Ice, Compression, Elevation.'},{name:'Foreign Object',severity:'moderate',desc:'Object embedded in hand.',action:'Do not remove. Bandage around object. Seek medical attention.'}]},'left-foot':{label:'Left Foot',color:'#F59E0B',conditions:[{name:'Foot Fracture',severity:'urgent',desc:'Broken foot bone.',action:'Immobilize. Elevate. Seek medical attention.'},{name:'Sprain',severity:'moderate',desc:'Ligament injury.',action:'RICE: Rest, Ice, Compression, Elevation.'},{name:'Burn',severity:'moderate',desc:'Burn to foot.',action:'Cool with water 20 min. Cover with sterile dressing.'},{name:'Puncture Wound',severity:'moderate',desc:'Deep puncture.',action:'Clean thoroughly. Watch for infection. Update tetanus.'},{name:'Frostbite',severity:'urgent',desc:'Freezing injury.',action:'Warm gradually. Do not rub. Seek medical attention.'}]},'right-foot':{label:'Right Foot',color:'#F59E0B',conditions:[{name:'Foot Fracture',severity:'urgent',desc:'Broken foot bone.',action:'Immobilize. Elevate. Seek medical attention.'},{name:'Sprain',severity:'moderate',desc:'Ligament injury.',action:'RICE: Rest, Ice, Compression, Elevation.'},{name:'Burn',severity:'moderate',desc:'Burn to foot.',action:'Cool with water 20 min. Cover with sterile dressing.'},{name:'Puncture Wound',severity:'moderate',desc:'Deep puncture.',action:'Clean thoroughly. Watch for infection. Update tetanus.'},{name:'Frostbite',severity:'urgent',desc:'Freezing injury.',action:'Warm gradually. Do not rub. Seek medical attention.'}]}};

var KIT_DATA={bandages:{label:'Bandages & Dressings',icon:'fa-bandage',items:['Adhesive bandages','Gauze pads','Elastic bandage','Triangular bandage','Adhesive tape','Non-stick dressings'],checked:4},medications:{label:'Medications',icon:'fa-pills',items:['Aspirin 300mg','Ibuprofen','Antihistamine','Antiseptic cream','Hydrocortisone cream','Pain relief'],checked:2},tools:{label:'Tools & Equipment',icon:'fa-screwdriver-wrench',items:['Scissors','Tweezers','Gloves','Thermometer','Cold packs','CPR face shield','Emergency blanket','Flashlight'],checked:3},other:{label:'Other Essentials',icon:'fa-box',items:['Cling film','Safety pins','Plastic bags','Notepad','First aid manual'],checked:1}};

var CONTACTS=[{name:'Emergency Services',phone:'912',type:'emergency',icon:'fa-phone-volume',isDefault:true},{name:'Poison Control',phone:'1-800-222-1222',type:'emergency',icon:'fa-skull-crossbones',isDefault:true},{name:'Dr. Sarah Mitchell',phone:'555-0142',type:'medical',icon:'fa-user-doctor',isDefault:false},{name:'Mom',phone:'555-0198',type:'personal',icon:'fa-user',isDefault:false}];

function goTo(pg){document.querySelectorAll('.page').forEach(function(e){e.classList.remove('active')});document.getElementById('pg-'+pg).classList.add('active');document.querySelectorAll('#sideNav .nav-item').forEach(function(e){e.classList.remove('active');e.classList.add('text-zinc-400')});var i={dashboard:1,bodymap:2,checker:3,guide:4,cpr:5,kit:6,contacts:7};var b=document.querySelector('#sideNav .nav-item:nth-child('+i[pg]+')');if(b){b.classList.add('active');b.classList.remove('text-zinc-400')}}

function toast(m){var c=document.getElementById('toastWrap');var d=document.createElement('div');d.className='toast flex items-center gap-3';d.innerHTML='<i class="fa-solid fa-circle-check text-green-400"></i><span>'+m+'</span>';c.appendChild(d);setTimeout(function(){d.remove()},3000)}
function openSOS(){document.getElementById('sosOverlay').classList.add('open')}
function closeSOS(){document.getElementById('sosOverlay').classList.remove('open')}
function openModal(h){document.getElementById('modalBox').innerHTML=h;document.getElementById('modalBg').classList.add('open')}
function closeModal(){document.getElementById('modalBg').classList.remove('open')}

function showZone(z){document.querySelectorAll('.body-zone').forEach(function(e){e.classList.remove('active')});event.target.classList.add('active');var zn=ZONES[z];if(!zn)return;var h='<div class="card-s p-6"><div class="flex items-center gap-3 mb-5"><div class="w-3 h-3 rounded-full" style="background:'+zn.color+'"></div><h3 class="font-bold text-xl">'+zn.label+'</h3></div><div class="space-y-4">';zn.conditions.forEach(function(c){h+='<div class="p-4 rounded-lg bg-zinc-900/50 border border-zinc-800/50"><div class="flex items-start justify-between mb-2"><h4 class="font-semibold text-sm">'+c.name+'</h4><span class="sev-'+c.severity+' text-[11px] px-2 py-0.5 rounded-full font-medium">'+c.severity+'</span></div><p class="text-zinc-400 text-xs mb-3">'+c.desc+'</p><div class="p-3 rounded-md bg-zinc-800/50"><p class="text-xs font-semibold text-teal-400 mb-1"><i class="fa-solid fa-kit-medical mr-1"></i> First Aid:</p><p class="text-xs text-zinc-300">'+c.action+'</p></div></div></div>'});h+='</div></div>';document.getElementById('zonePanel').innerHTML=h}

var activeCat='all';
function renderGuide(f){f=(f||'').toLowerCase();var fl=CONDITIONS;if(activeCat!=='all')fl=fl.filter(function(c){return c.category===activeCat});if(f)fl=fl.filter(function(c){return c.name.toLowerCase().indexOf(f)!==-1||c.summary.toLowerCase().indexOf(f)!==-1});var g=document.getElementById('guideGrid');if(!fl.length){g.innerHTML='<div class="col-span-full text-center py-12 text-zinc-500"><i class="fa-solid fa-search text-3xl mb-3 block"></i><p>No matches found</p></div>';return}g.innerHTML=fl.map(function(c){return '<div class="card p-4 cursor-pointer hover:border-red-500/30 transition-all" onclick="showCM(\''+c.id+'\')"><div class="flex items-start justify-between mb-3"><div class="flex items-center gap-3"><div class="w-10 h-10 rounded-lg bg-zinc-800 flex items-center justify-center"><i class="fa-solid '+c.icon+' text-red-400"></i></div><div><h4 class="font-semibold text-sm">'+c.name+'</h4><p class="text-[11px] text-zinc-500 capitalize">'+c.category+'</p></div></div><span class="sev-'+c.severity+' text-[10px] px-2 py-0.5 rounded-full font-medium">'+c.severity+'</span></div><p class="text-xs text-zinc-400 line-clamp-2">'+c.summary+'</p><div class="flex items-center gap-2 mt-3 text-[11px] text-zinc-500"><span><i class="fa-solid fa-list-ol mr-1"></i>'+c.steps.length+' steps</span><span class="w-1 h-1 rounded-full bg-zinc-700"></span>'+(c.call912?'<span class="text-red-400"><i class="fa-solid fa-phone-volume mr-1"></i>Call 912</span>':'<span>Self-care</span>')+'</div></div>'}).join('')}
function filterGuide(v){renderGuide(v)}
function filterCat(c){activeCat=c;renderGuide();var bs=document.querySelectorAll('#guideFilters button');bs.forEach(function(b){b.className='px-3 py-1.5 rounded-lg text-xs font-medium bg-zinc-800 text-zinc-400 border border-transparent hover:border-zinc-700'});event.target.className='px-3 py-1.5 rounded-lg text-xs font-medium bg-red-600/20 text-red-400 border border-red-600/30'}
function showCM(id){var c=CONDITIONS.find(function(x){return x.id===id});if(!c)return;var sh=c.steps.map(function(s,i){return '<li class="flex gap-3 text-sm"><span class="w-6 h-6 rounded-full bg-red-600/20 text-red-400 flex items-center justify-center flex-shrink-0 text-xs font-bold">'+(i+1)+'</span><span class="text-zinc-300">'+s+'</span></li>'}).join('');var dh=c.dos.map(function(d){return '<li class="text-xs text-zinc-300">'+d+'</li>'}).join('');var nh=c.donts.map(function(d){return '<li class="text-xs text-zinc-300">'+d+'</li>'}).join('');var h='<div class="flex items-center justify-between p-5 border-b border-zinc-800"><h3 class="font-semibold text-lg">'+c.name+'</h3><button onclick="closeModal()" class="w-8 h-8 rounded-lg hover:bg-zinc-800 flex items-center justify-center"><i class="fa-solid fa-xmark text-zinc-400"></i></button></div><div class="p-6"><div class="flex items-center gap-3 mb-4"><span class="sev-'+c.severity+' text-xs px-3 py-1 rounded-full font-semibold uppercase">'+c.severity+'</span>'+(c.call912?'<span class="text-xs px-3 py-1 rounded-full bg-red-600/20 text-red-400 border border-red-600/30 font-semibold">Call 912</span>':'<span class="text-xs px-3 py-1 rounded-full bg-zinc-800 text-zinc-400">Self-care</span>')+'</div><p class="text-zinc-300 text-sm mb-5">'+c.summary+'</p><h4 class="font-semibold text-sm text-teal-400 mb-3"><i class="fa-solid fa-list-ol mr-2"></i>Steps</h4><ol class="space-y-2 mb-6">'+sh+'</ol><div class="grid grid-cols-1 md:grid-cols-2 gap-4"><div class="p-4 rounded-lg bg-green-600/5 border border-green-600/15"><h5 class="text-xs font-bold text-green-400 mb-2"><i class="fa-solid fa-check mr-1"></i> DO</h5><ul class="space-y-1.5">'+dh+'</ul></div><div class="p-4 rounded-lg bg-red-600/5 border border-red-600/15"><h5 class="text-xs font-bold text-red-400 mb-2"><i class="fa-solid fa-xmark mr-1"></i> DON\'T</h5><ul class="space-y-1.5">'+nh+'</ul></div></div></div>';openModal(h)}

var checkerQ1=document.getElementById('pg-checker').innerHTML;
function checkerNext(t){var R={chest:{q:'Sudden or gradual?',opts:[{t:'Sudden, crushing pain',r:{s:'critical',c:'Heart Attack',a:'Call 912 IMMEDIATELY. Give aspirin. Keep still.'}},{t:'Gradual, mild',r:{s:'urgent',c:'Angina',a:'Rest. Help with nitroglycerin if prescribed. Call 912 if persists.'}},{t:'Sharp pain when breathing',r:{s:'moderate',c:'Rib Fracture',a:'Immobilize. Support chest when coughing. Seek medical attention.'}}]},breathing:{q:'Can they speak or clutching throat?',opts:[{t:'Cannot speak, clutching throat',r:{s:'critical',c:'Choking',a:'5 back blows + 5 abdominal thrusts. Alternate until clear. If unconscious, begin CPR. Call 912.'}},{t:'Wheezing, known asthma',r:{s:'urgent',c:'Asthma Attack',a:'Use rescue inhaler. Keep upright. Call 912 if no improvement.'}},{t:'Gradual shortness of breath',r:{s:'urgent',c:'Difficulty Breathing',a:'Sit upright. Loosen clothing. Call 912. Be prepared for CPR.'}}]},bleeding:{q:'How would you describe it?',opts:[{t:'Heavy, spurting',r:{s:'critical',c:'Severe Bleeding',a:'Apply pressure. Call 912. Use tourniquet if life-threatening. Note time.'}},{t:'Moderate cut',r:{s:'moderate',c:'Moderate Bleeding',a:'Pressure 10 min. Elevate. Clean and bandage.'}},{t:'Minor scrape',r:{s:'minor',c:'Minor Wound',a:'Clean with water. Apply antiseptic. Cover with bandage.'}}]},unconscious:{q:'Is the person breathing?',opts:[{t:'Not breathing or gasping',r:{s:'critical',c:'Cardiac Arrest',a:'Call 912. Begin CPR: 30 compressions to 2 breaths.'}},{t:'Breathing normally',r:{s:'urgent',c:'Unconscious but Breathing',a:'Call 912. Recovery position. Monitor breathing.'}},{t:'Not sure',r:{s:'critical',c:'Check Breathing',a:'Look at chest 10 seconds. Not breathing = CPR. Breathing = recovery position. Call 912.'}}]},burn:{q:'How large?',opts:[{t:'Larger than palm or face/hands',r:{s:'critical',c:'Major Burn',a:'Call 912. Cool water 20 min. Do NOT remove stuck clothing.'}},{t:'Smaller than palm',r:{s:'moderate',c:'Minor Burn',a:'Cool water 20 min. Cover with cling film.'}}]},seizure:{q:'Currently seizing?',opts:[{t:'Currently seizing',r:{s:'urgent',c:'Active Seizure',a:'Clear hazards. Do NOT restrain. Protect head. Time it. Call 912 if over 5 min.'}},{t:'Seizure stopped',r:{s:'moderate',c:'Post-Seizure',a:'Recovery position. Reassure. Call 912 if first seizure.'}}]},fracture:{q:'Bone visible or deformed?',opts:[{t:'Bone visible or severe deformity',r:{s:'critical',c:'Open Fracture',a:'Call 912. Do NOT push bone back. Cover wound.'}},{t:'Swollen, no visible bone',r:{s:'urgent',c:'Closed Fracture',a:'Immobilize with splint. Ice wrapped in cloth. Elevate. Seek medical help.'}},{t:'Can bear weight',r:{s:'moderate',c:'Possible Sprain',a:'RICE: Rest, Ice, Compression, Elevation. Seek eval if not better after 24h.'}}]},allergic:{q:'Severe reaction?',opts:[{t:'Yes, severe symptoms',r:{s:'critical',c:'Anaphylaxis',a:'Use epinephrine NOW. Call 912. Second dose in 5 min if no improvement.'}},{t:'Mild rash, itching',r:{s:'moderate',c:'Mild Allergic Reaction',a:'Antihistamine. Cool compress. Monitor for worsening.'}}]}};if(!R[t])return;var q=R[t];var h='<div class="flex items-center gap-2 mb-6"><div class="step-dot"></div><div class="h-px flex-1 bg-zinc-800"></div><div class="step-dot active"></div><div class="h-px flex-1 bg-zinc-800"></div><div class="step-dot"></div></div><div class="card-s p-6 mb-4"><p class="text-xs text-zinc-500 mb-1">Question 2</p><h3 class="font-semibold text-lg">'+q.q+'</h3></div><div class="space-y-3">';q.opts.forEach(function(o){h+='<button onclick="showResult(\''+o.r.s+'\',\''+o.r.c+'\',\''+o.r.a+'\')" class="card w-full p-4 flex items-center gap-4 text-left hover:border-red-500/30"><div class="w-10 h-10 rounded-lg bg-zinc-800 flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-question text-zinc-400"></i></div><span class="text-sm font-medium">'+o.t+'</span><i class="fa-solid fa-chevron-right text-zinc-600 ml-auto text-xs"></i></button>'});h+='</div><button onclick="resetChecker()" class="text-zinc-500 hover:text-zinc-300 text-sm transition-colors"><i class="fa-solid fa-arrow-left mr-1"></i> Start Over</button>';document.getElementById('pg-checker').innerHTML=h}
function showResult(s,c,a){var h='<div class="flex items-center gap-2 mb-6"><div class="step-dot"></div><div class="h-px flex-1 bg-zinc-800"></div><div class="step-dot"></div><div class="h-px flex-1 bg-zinc-800"></div><div class="step-dot active"></div></div><div class="card-s p-6 mb-4"><div class="flex items-start justify-between mb-4"><div><h3 class="font-semibold text-lg">'+c+'</h3><span class="sev-'+s+' text-xs px-3 py-1 rounded-full font-semibold uppercase mt-2 inline-block">'+s+'</span></div></div><p class="text-zinc-300 text-sm mb-6">'+a+'</p><div class="flex gap-3"><button onclick="resetChecker()" class="px-6 py-3 bg-zinc-800 hover:bg-zinc-700 rounded-xl font-semibold"><i class="fa-solid fa-arrow-left mr-2"></i>Start Over</button>'+(s==='critical'?'<button onclick="openSOS()" class="px-6 py-3 bg-red-600 hover:bg-red-500 rounded-xl font-semibold"><i class="fa-solid fa-phone-volume mr-2"></i>Emergency</button>':'')+'</div></div>';document.getElementById('pg-checker').innerHTML=h}
function resetChecker(){document.getElementById('pg-checker').innerHTML=checkerQ1}
var cprInterval,cprCount=0,cprBPM=110,cprPhase='Ready',cprCompressions=0;
function toggleCPR(){if(cprInterval){clearInterval(cprInterval);cprInterval=null;document.getElementById('cprBtn').innerHTML='<i class="fa-solid fa-play mr-2"></i>Start';cprPhase='Ready'}else{cprInterval=setInterval(function(){if(cprCompressions<30){cprCompressions++;cprCount++;document.getElementById('cprCount').textContent=cprCount;document.getElementById('cprPulse').style.transform='scale(1.1)';setTimeout(function(){document.getElementById('cprPulse').style.transform='scale(1)'},100)}else{cprPhase='Breaths';clearInterval(cprInterval);cprInterval=setInterval(function(){cprCompressions++;if(cprCompressions>=32){cprCompressions=0;cprPhase='Compressions';clearInterval(cprInterval);toggleCPR();toggleCPR()}},2000)}},60000/cprBPM);document.getElementById('cprBtn').innerHTML='<i class="fa-solid fa-pause mr-2"></i>Pause';cprPhase='Compressions'}document.getElementById('cprPhase').textContent=cprPhase}
function adjBPM(d){cprBPM=Math.max(80,Math.min(140,cprBPM+d));document.getElementById('cprBPM').textContent=cprBPM;if(cprInterval){clearInterval(cprInterval);cprInterval=null;toggleCPR();toggleCPR()}}
function resetCPR(){clearInterval(cprInterval);cprInterval=null;cprCount=0;cprCompressions=0;cprPhase='Ready';document.getElementById('cprCount').textContent='0';document.getElementById('cprPhase').textContent='Ready';document.getElementById('cprBtn').innerHTML='<i class="fa-solid fa-play mr-2"></i>Start'}
function renderKit(){var h='';for(var cat in KIT_DATA){var d=KIT_DATA[cat];h+='<div class="card-s p-5 mb-4"><div class="flex items-center gap-3 mb-4"><div class="w-10 h-10 rounded-lg bg-zinc-800 flex items-center justify-center"><i class="fa-solid '+d.icon+' text-teal-400"></i></div><h3 class="font-semibold text-lg">'+d.label+'</h3></div><div class="space-y-2">';d.items.forEach(function(item){h+='<label class="flex items-center gap-3 p-3 rounded-lg hover:bg-zinc-800/50 cursor-pointer"><input type="checkbox" class="kit-check" '+(d.checked>0?'checked':'')+'><span class="text-sm text-zinc-300">'+item+'</span></label>'});h+='</div></div>'}document.getElementById('kitContainer').innerHTML=h}
function renderContacts(){var h='';CONTACTS.forEach(function(c){h+='<div class="card p-5"><div class="flex items-center gap-4"><div class="w-12 h-12 rounded-full bg-zinc-800 flex items-center justify-center"><i class="fa-solid '+c.icon+' text-teal-400"></i></div><div class="flex-1"><h4 class="font-semibold text-sm">'+c.name+'</h4><p class="text-zinc-400 text-xs">'+c.type+'</p></div><a href="tel:'+c.phone+'" class="px-4 py-2 bg-teal-600 hover:bg-teal-500 rounded-lg text-sm font-semibold text-white transition-colors"><i class="fa-solid fa-phone mr-2"></i>Call</a></div></div>'});document.getElementById('contactsGrid').innerHTML=h}
// Emergency Search Functionality
const searchInput = document.getElementById('emergencySearch');
const searchResults = document.getElementById('searchResults');

if (searchInput && searchResults) {
  searchInput.addEventListener('input', function() {
    const query = this.value.toLowerCase().trim();
    
    if (query.length < 2) {
      searchResults.classList.add('hidden');
      return;
    }
    
    const results = CONDITIONS.filter(condition => 
      condition.name.toLowerCase().includes(query) ||
      condition.summary.toLowerCase().includes(query) ||
      condition.category.toLowerCase().includes(query)
    );
    
    displayEmergencySearchResults(results);
  });
  
  function displayEmergencySearchResults(results) {
    if (results.length === 0) {
      searchResults.innerHTML = `
        <div class="p-4 text-center text-zinc-400">
          <i class="fa-solid fa-search mb-2 text-2xl"></i>
          <p>No emergencies found. Try different keywords.</p>
        </div>
      `;
    } else {
      searchResults.innerHTML = results.slice(0, 5).map(condition => `
        <div class="p-4 border-b border-zinc-800 hover:bg-zinc-800/50 cursor-pointer transition-colors" onclick="showCM('${condition.id}')">
          <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-lg bg-zinc-800 flex items-center justify-center flex-shrink-0">
              <i class="fa-solid ${condition.icon} text-red-400"></i>
            </div>
            <div class="flex-1">
              <div class="flex items-center gap-2 mb-1">
                <h4 class="font-semibold text-white">${condition.name}</h4>
                <span class="px-2 py-1 bg-${condition.severity === 'critical' ? 'red' : condition.severity === 'urgent' ? 'orange' : 'blue'}-600/20 border border-${condition.severity === 'critical' ? 'red' : condition.severity === 'urgent' ? 'orange' : 'blue'}-600/30 rounded-full text-xs text-${condition.severity === 'critical' ? 'red' : condition.severity === 'urgent' ? 'orange' : 'blue'}-300">
                  ${condition.severity}
                </span>
              </div>
              <p class="text-sm text-zinc-400 mb-2">${condition.summary}</p>
              <div class="flex items-center gap-4 text-xs text-zinc-500">
                <span><i class="fa-solid fa-list-ol mr-1"></i>${condition.steps.length} steps</span>
                ${condition.call912 ? '<span><i class="fa-solid fa-phone-volume mr-1"></i>Call 912</span>' : ''}
              </div>
            </div>
          </div>
        </div>
      `).join('');
    }
    
    searchResults.classList.remove('hidden');
  }
  
  // Close search results when clicking outside
  document.addEventListener('click', function(e) {
    if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
      searchResults.classList.add('hidden');
    }
  });
}

// Voice Search and AI Integration
let isRecording = false;
let recognition = null;
let isAIMode = true; // Default to AI mode

// Initialize speech recognition
function initializeSpeechRecognition() {
  if (!('webkitSpeechRecognition' in window) && !('SpeechRecognition' in window)) {
    console.warn('Speech recognition not supported in this browser');
    return false;
  }

  const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
  recognition = new SpeechRecognition();
  
  recognition.continuous = false;
  recognition.interimResults = true;
  recognition.lang = 'en-US';
  
  recognition.onstart = function() {
    console.log('Speech recognition started');
    const voiceStatusText = document.getElementById('voiceStatusText');
    if (voiceStatusText) voiceStatusText.textContent = 'Listening...';
  };
  
  recognition.onresult = function(event) {
    let finalTranscript = '';
    let interimTranscript = '';
    
    for (let i = event.resultIndex; i < event.results.length; i++) {
      const transcript = event.results[i][0].transcript;
      if (event.results[i].isFinal) {
        finalTranscript += transcript;
      } else {
        interimTranscript += transcript;
      }
    }
    
    const searchInput = document.getElementById('emergencySearch');
    const voiceStatusText = document.getElementById('voiceStatusText');
    
    if (finalTranscript) {
      console.log('Final transcript:', finalTranscript);
      searchInput.value = finalTranscript;
      voiceStatusText.textContent = 'Processing...';
      
      // Trigger AI search with the transcribed text
      setTimeout(() => {
        performAISearch(finalTranscript);
      }, 500);
    } else if (interimTranscript) {
      console.log('Interim transcript:', interimTranscript);
      voiceStatusText.textContent = 'Hearing: ' + interimTranscript;
    }
  };
  
  recognition.onerror = function(event) {
    console.error('Speech recognition error:', event.error);
    const voiceStatusText = document.getElementById('voiceStatusText');
    if (voiceStatusText) {
      switch(event.error) {
        case 'no-speech':
          voiceStatusText.textContent = 'No speech detected';
          break;
        case 'audio-capture':
          voiceStatusText.textContent = 'Microphone not available';
          break;
        case 'not-allowed':
          voiceStatusText.textContent = 'Microphone permission denied';
          break;
        case 'network':
          voiceStatusText.textContent = 'Network error';
          break;
        default:
          voiceStatusText.textContent = 'Error: ' + event.error;
      }
    }
    
    // Reset UI after error
    setTimeout(() => {
      resetVoiceUI();
    }, 2000);
  };
  
  recognition.onend = function() {
    console.log('Speech recognition ended');
    resetVoiceUI();
  };
  
  return true;
}

// Voice search functionality
async function toggleVoiceSearch() {
  const voiceBtn = document.getElementById('voiceSearchBtn');
  const voiceIcon = document.getElementById('voiceIcon');
  const voiceStatus = document.getElementById('voiceStatus');
  const voiceStatusText = document.getElementById('voiceStatusText');
  const searchInput = document.getElementById('emergencySearch');

  if (!isRecording) {
    // Initialize speech recognition if not already done
    if (!recognition && !initializeSpeechRecognition()) {
      toast('Speech recognition not supported in your browser. Please try Chrome or Edge.');
      return;
    }
    
    try {
      // Start speech recognition
      recognition.start();
      isRecording = true;

      // Update UI
      voiceIcon.className = 'fa-solid fa-stop text-red-400';
      voiceBtn.classList.add('bg-red-600/40');
      voiceStatus.classList.remove('hidden');
      voiceStatusText.textContent = 'Initializing...';

    } catch (error) {
      console.error('Error starting speech recognition:', error);
      toast('Failed to start voice recognition: ' + error.message);
      resetVoiceUI();
    }
  } else {
    // Stop speech recognition
    try {
      if (recognition) {
        recognition.stop();
      }
      isRecording = false;
    } catch (error) {
      console.error('Error stopping speech recognition:', error);
    }
    
    resetVoiceUI();
  }
}

// Reset voice UI to initial state
function resetVoiceUI() {
  const voiceBtn = document.getElementById('voiceSearchBtn');
  const voiceIcon = document.getElementById('voiceIcon');
  const voiceStatus = document.getElementById('voiceStatus');
  const voiceStatusText = document.getElementById('voiceStatusText');
  
  if (voiceIcon) voiceIcon.className = 'fa-solid fa-microphone text-red-400';
  if (voiceBtn) voiceBtn.classList.remove('bg-red-600/40');
  if (voiceStatus) voiceStatus.classList.add('hidden');
  if (voiceStatusText) voiceStatusText.textContent = 'Listening...';
  
  isRecording = false;
}

// Toggle between AI and offline search modes
function toggleSearchMode() {
  const toggle = document.getElementById('searchModeToggle');
  const slider = document.getElementById('searchModeSlider');
  const info = document.getElementById('searchModeInfo');
  
  isAIMode = !isAIMode;
  
  if (isAIMode) {
    // Free AI Mode
    toggle.classList.remove('bg-zinc-600');
    toggle.classList.add('bg-green-600');
    slider.style.transform = 'translateX(0)';
    info.innerHTML = '<i class="fa-solid fa-brain text-green-400 mr-1"></i>Free AI provides intelligent first aid recommendations';
  } else {
    // Offline Mode
    toggle.classList.remove('bg-green-600');
    toggle.classList.add('bg-zinc-600');
    slider.style.transform = 'translateX(24px)';
    info.innerHTML = '<i class="fa-solid fa-database text-zinc-400 mr-1"></i>Offline mode uses basic emergency database';
  }
  
  // Clear current search results
  const searchResults = document.getElementById('searchResults');
  if (searchResults) {
    searchResults.classList.add('hidden');
  }
}

// Note: Using browser's built-in Web Speech API for real-time voice-to-text
// No need for external API processing or blob conversion

// Enhanced search with AI integration
async function performAISearch(query) {
  const searchResults = document.getElementById('searchResults');
  
  if (isAIMode) {
    // AI Mode - Primary search method
    try {
      console.log('Starting Free AI search for:', query);
      
      // Get AI suggestions first
      const suggestionsResponse = await fetch('/api/free-ai/suggestions', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
        },
        body: JSON.stringify({ query: query })
      });

      console.log('Suggestions response status:', suggestionsResponse.status);
      let suggestionsData;
      try {
        const responseText = await suggestionsResponse.text();
        console.log('Suggestions response text:', responseText.substring(0, 200));
        suggestionsData = JSON.parse(responseText);
      } catch (e) {
        console.error('Failed to parse suggestions JSON:', e);
        throw new Error('Invalid response from suggestions API');
      }
      console.log('Suggestions data:', suggestionsData);
      
      // Get AI recommendation
      const recommendationResponse = await fetch('/api/free-ai/first-aid', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
        },
        body: JSON.stringify({ symptoms: query })
      });

      console.log('Recommendation response status:', recommendationResponse.status);
      let recommendationData;
      try {
        const responseText = await recommendationResponse.text();
        console.log('Recommendation response text:', responseText.substring(0, 200));
        recommendationData = JSON.parse(responseText);
      } catch (e) {
        console.error('Failed to parse recommendation JSON:', e);
        throw new Error('Invalid response from recommendation API');
      }
      console.log('Recommendation data:', recommendationData);
      
      // Display AI-powered results
      displayAIResults(query, suggestionsData, recommendationData);
      
    } catch (error) {
      console.error('AI search error:', error);
      // Show error message for AI mode
      displayAIError(query, error);
    }
  } else {
    // Offline Mode - Use hardcoded database
    console.log('Searching offline for:', query, 'Available conditions:', CONDITIONS.length);
    const results = CONDITIONS.filter(condition => {
      const searchLower = query.toLowerCase();
      return (
        (condition.name && condition.name.toLowerCase().includes(searchLower)) ||
        (condition.summary && condition.summary.toLowerCase().includes(searchLower)) ||
        (condition.category && condition.category.toLowerCase().includes(searchLower))
      );
    });
    console.log('Offline search results:', results);
    displayOfflineResults(query, results);
  }
}

// Display AI-powered search results
function displayAIResults(query, suggestions, recommendation) {
  const searchResults = document.getElementById('searchResults');
  
  let html = '';
  
  // AI Recommendation Section
  if (recommendation.success && recommendation.recommendation) {
    const data = recommendation.recommendation;
    html += `
      <div class="border-b border-zinc-800">
        <div class="p-3 bg-gradient-to-r from-green-600/10 to-blue-600/10">
          <div class="flex items-center gap-2 mb-2">
            <i class="fa-solid fa-brain text-green-400"></i>
            <span class="text-xs font-semibold text-green-300">FREE AI RECOMMENDATION</span>
            ${recommendation.provider === 'free_ai' ? '<span class="px-2 py-1 bg-green-600/20 border border-green-600/30 rounded-full text-xs text-green-300">Powered by Free AI</span>' : ''}
          </div>
          <div class="mb-2">
            <h4 class="font-semibold text-white mb-1">${data.condition_name}</h4>
            <span class="px-2 py-1 bg-${data.emergency_level === 'critical' ? 'red' : data.emergency_level === 'urgent' ? 'orange' : 'blue'}-600/20 border border-${data.emergency_level === 'critical' ? 'red' : data.emergency_level === 'urgent' ? 'orange' : 'blue'}-600/30 rounded-full text-xs text-${data.emergency_level === 'critical' ? 'red' : data.emergency_level === 'urgent' ? 'orange' : 'blue'}-300">
              ${data.emergency_level ? data.emergency_level.toUpperCase() : 'MODERATE'}
            </span>
          </div>
          <div class="bg-red-600/10 border border-red-600/20 rounded-lg p-2 mb-2">
            <p class="text-xs font-medium text-red-300">
              <i class="fa-solid fa-exclamation-triangle mr-1"></i>
              ${data.immediate_action || 'Seek medical attention'}
            </p>
          </div>
          <div class="flex gap-2">
            <button onclick="showAIDetails('${JSON.stringify(recommendation.recommendation).replace(/'/g, "\\'")}')" class="flex-1 px-2 py-1 bg-green-600 hover:bg-green-500 rounded text-xs font-semibold transition-colors">
              <i class="fa-solid fa-expand mr-1"></i> Details
            </button>
            ${data.emergency_call ? '<button onclick="openSOS()" class="px-2 py-1 bg-red-600 hover:bg-red-500 rounded text-xs font-semibold transition-colors"><i class="fa-solid fa-phone mr-1"></i> 912</button>' : ''}
          </div>
        </div>
      </div>
    `;
  }
  
  // AI Suggestions Section
  if (suggestions.success && suggestions.suggestions.length > 0) {
    html += `
      <div class="border-b border-zinc-800">
        <div class="p-3">
          <div class="flex items-center gap-2 mb-2">
            <i class="fa-solid fa-lightbulb text-green-400"></i>
            <span class="text-xs font-semibold text-green-300">FREE AI SUGGESTIONS</span>
          </div>
          <div class="space-y-1">
            ${suggestions.suggestions.slice(0, 3).map(suggestion => `
              <div class="p-2 rounded bg-zinc-800/50 hover:bg-zinc-800 cursor-pointer transition-colors" onclick="searchInput.value='${suggestion}'; performAISearch('${suggestion}')">
                <p class="text-xs text-zinc-300">${suggestion}</p>
              </div>
            `).join('')}
          </div>
        </div>
      </div>
    `;
  }
  
  searchResults.innerHTML = html;
  searchResults.classList.remove('hidden');
}

// Display AI error message
function displayAIError(query, error) {
  const searchResults = document.getElementById('searchResults');
  
  const html = `
    <div class="p-4 bg-red-600/10 border border-red-600/20 rounded-lg">
      <div class="flex items-center gap-2 mb-3">
        <i class="fa-solid fa-exclamation-triangle text-red-400"></i>
        <span class="text-xs font-semibold text-red-300">AI SERVICE UNAVAILABLE</span>
      </div>
      <p class="text-sm text-zinc-300 mb-3">Unable to connect to AI service. Please check your internet connection or try offline mode.</p>
      <div class="flex gap-2">
        <button onclick="toggleSearchMode(); performAISearch('${query}')" class="flex-1 px-3 py-2 bg-zinc-600 hover:bg-zinc-500 rounded-lg text-xs font-semibold transition-colors">
          <i class="fa-solid fa-database mr-1"></i> Try Offline Mode
        </button>
        <button onclick="performAISearch('${query}')" class="flex-1 px-3 py-2 bg-red-600 hover:bg-red-500 rounded-lg text-xs font-semibold transition-colors">
          <i class="fa-solid fa-refresh mr-1"></i> Retry
        </button>
      </div>
    </div>
  `;
  
  searchResults.innerHTML = html;
  searchResults.classList.remove('hidden');
}

// Display offline search results
function displayOfflineResults(query, results) {
  const searchResults = document.getElementById('searchResults');
  
  if (results.length === 0) {
    searchResults.innerHTML = `
      <div class="p-4 text-center text-zinc-400">
        <i class="fa-solid fa-search mb-2 text-2xl"></i>
        <p>No emergencies found in offline database for "${query}"</p>
        <p class="text-xs mt-2">Try AI mode for more comprehensive results</p>
      </div>
    `;
  } else {
    searchResults.innerHTML = `
      <div class="p-3 bg-zinc-600/10 border border-zinc-600/20">
        <div class="flex items-center gap-2 mb-2">
          <i class="fa-solid fa-database text-zinc-400"></i>
          <span class="text-xs font-semibold text-zinc-300">OFFLINE DATABASE</span>
        </div>
      </div>
      ${results.slice(0, 5).map(condition => `
        <div class="p-4 border-b border-zinc-800 hover:bg-zinc-800/50 cursor-pointer transition-colors" onclick="showCM('${condition.id}')">
          <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-lg bg-zinc-800 flex items-center justify-center flex-shrink-0">
              <i class="fa-solid ${condition.icon} text-red-400"></i>
            </div>
            <div class="flex-1">
              <div class="flex items-center gap-2 mb-1">
                <h4 class="font-semibold text-white">${condition.name}</h4>
                <span class="px-2 py-1 bg-${condition.severity === 'critical' ? 'red' : condition.severity === 'urgent' ? 'orange' : 'blue'}-600/20 border border-${condition.severity === 'critical' ? 'red' : condition.severity === 'urgent' ? 'orange' : 'blue'}-600/30 rounded-full text-xs text-${condition.severity === 'critical' ? 'red' : condition.severity === 'urgent' ? 'orange' : 'blue'}-300">
                  ${condition.severity}
                </span>
              </div>
              <p class="text-sm text-zinc-400 mb-2">${condition.summary}</p>
              <div class="flex items-center gap-4 text-xs text-zinc-500">
                <span><i class="fa-solid fa-list-ol mr-1"></i>${condition.steps.length} steps</span>
                ${condition.call912 ? '<span><i class="fa-solid fa-phone-volume mr-1"></i>Call 912</span>' : ''}
              </div>
            </div>
          </div>
        </div>
      `).join('')}
    `;
  }
  
  searchResults.classList.remove('hidden');
}

// Show AI details modal
function showAIDetails(data) {
  try {
    console.log('showAIDetails called with data:', data);
    const recommendation = typeof data === 'string' ? JSON.parse(data) : data;
    console.log('Parsed recommendation:', recommendation);
    
    const modalHTML = `
      <div class="flex items-center justify-between p-5 border-b border-zinc-800">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-lg bg-green-600/20 flex items-center justify-center">
            <i class="fa-solid fa-brain text-green-400"></i>
          </div>
          <div>
            <h3 class="font-semibold text-lg">Free AI First Aid Recommendation</h3>
            <span class="text-xs text-green-300">Powered by Free AI Service</span>
          </div>
        </div>
        <button onclick="closeModal()" class="w-8 h-8 rounded-lg hover:bg-zinc-800 flex items-center justify-center">
          <i class="fa-solid fa-xmark text-zinc-400"></i>
        </button>
      </div>
      <div class="p-6">
        <div class="flex items-center gap-3 mb-4">
          <span class="px-3 py-1 bg-${recommendation.emergency_level === 'critical' ? 'red' : recommendation.emergency_level === 'urgent' ? 'orange' : 'blue'}-600/20 border border-${recommendation.emergency_level === 'critical' ? 'red' : recommendation.emergency_level === 'urgent' ? 'orange' : 'blue'}-600/30 rounded-full text-sm font-semibold text-${recommendation.emergency_level === 'critical' ? 'red' : recommendation.emergency_level === 'urgent' ? 'orange' : 'blue'}-300">
            ${recommendation.emergency_level.toUpperCase()} EMERGENCY
          </span>
          ${recommendation.emergency_call ? '<span class="px-3 py-1 bg-red-600/20 border border-red-600/30 rounded-full text-sm font-semibold text-red-300">CALL 912</span>' : ''}
        </div>
        
        <div class="mb-6">
          <h4 class="font-semibold text-lg text-white mb-2">${recommendation.condition_name}</h4>
          <div class="bg-red-600/10 border border-red-600/20 rounded-xl p-4">
            <div class="flex items-center gap-2 mb-2">
              <i class="fa-solid fa-exclamation-triangle text-red-400"></i>
              <h5 class="font-semibold text-red-300">IMMEDIATE ACTION</h5>
            </div>
            <p class="text-white font-medium">${recommendation.immediate_action}</p>
          </div>
        </div>
        
        <div class="mb-6">
          <h5 class="font-semibold text-white mb-3 flex items-center gap-2">
            <i class="fa-solid fa-list-ol text-blue-400"></i>
            Step-by-Step Instructions
          </h5>
          <div class="space-y-3">
            ${recommendation.steps && recommendation.steps.length > 0 ? recommendation.steps.map((step, index) => `
              <div class="flex gap-3">
                <div class="w-6 h-6 rounded-full bg-blue-600/20 flex items-center justify-center flex-shrink-0 mt-0.5">
                  <span class="text-xs text-blue-400 font-semibold">${index + 1}</span>
                </div>
                <p class="text-zinc-300 text-sm leading-relaxed">${step || 'Step not available'}</p>
              </div>
            `).join('') : '<div class="text-zinc-400 text-sm">No detailed steps available</div>'}
          </div>
        </div>
        
        ${recommendation.warning_signs && recommendation.warning_signs.length > 0 ? `
          <div class="mb-6 p-4 rounded-lg bg-orange-600/10 border border-orange-600/20">
            <h5 class="font-semibold text-orange-300 mb-2">
              <i class="fa-solid fa-triangle-exclamation mr-2"></i>
              Warning Signs
            </h5>
            <ul class="space-y-1">
              ${recommendation.warning_signs.map(sign => `<li class="text-sm text-zinc-300">• ${sign}</li>`).join('')}
            </ul>
          </div>
        ` : ''}
        
        ${recommendation.important_notes && recommendation.important_notes.length > 0 ? `
          <div class="mb-6 p-4 rounded-lg bg-blue-600/10 border border-blue-600/20">
            <h5 class="font-semibold text-blue-300 mb-2">
              <i class="fa-solid fa-circle-info mr-2"></i>
              Important Notes
            </h5>
            <ul class="space-y-1">
              ${recommendation.important_notes.map(note => `<li class="text-sm text-zinc-300">• ${note}</li>`).join('')}
            </ul>
          </div>
        ` : ''}
        
        <div class="flex gap-3">
          <button onclick="closeModal()" class="flex-1 px-4 py-2 bg-zinc-800 hover:bg-zinc-700 rounded-lg font-semibold transition-colors">
            Close
          </button>
          ${recommendation.emergency_call ? '<button onclick="openSOS()" class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-500 rounded-lg text-center font-semibold transition-colors"><i class="fa-solid fa-phone-volume mr-2"></i>Call Emergency Services</button>' : ''}
        </div>
      </div>
    `;
    
    openModal(modalHTML);
  } catch (error) {
    console.error('Error showing AI details:', error);
    toast('Error displaying AI recommendations');
  }
}

// Enhanced search input handler with AI integration
if (searchInput && searchResults) {
  let searchTimeout;
  
  searchInput.addEventListener('input', function() {
    clearTimeout(searchTimeout);
    const query = this.value.toLowerCase().trim();
    
    if (query.length < 2) {
      searchResults.classList.add('hidden');
      return;
    }
    
    // Debounce AI search
    searchTimeout = setTimeout(() => {
      performAISearch(query);
    }, 500);
  });
  
  // Add Enter key support
  searchInput.addEventListener('keydown', function(e) {
    if (e.key === 'Enter') {
      clearTimeout(searchTimeout);
      const query = this.value.toLowerCase().trim();
      
      if (query.length >= 2) {
        performAISearch(query);
      }
    }
  });
}

// Initialize speech recognition on page load
document.addEventListener('DOMContentLoaded', function() {
  // Check for speech recognition support
  if (!('webkitSpeechRecognition' in window) && !('SpeechRecognition' in window)) {
    console.warn('Speech recognition not supported in this browser');
    const voiceBtn = document.getElementById('voiceSearchBtn');
    if (voiceBtn) {
      voiceBtn.disabled = true;
      voiceBtn.title = 'Speech recognition not supported in your browser. Please use Chrome or Edge.';
      voiceBtn.style.opacity = '0.5';
    }
  } else {
    console.log('Speech recognition is supported');
    // Initialize but don't start recognition yet
    initializeSpeechRecognition();
  }
});

renderGuide();renderKit();renderContacts();
</script>
</body>
</html>
