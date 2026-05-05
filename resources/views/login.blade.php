<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login — LifeLine</title>
<link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
<link rel="alternate icon" href="{{ asset('favicon.ico') }}">
<link rel="apple-touch-icon" href="{{ asset('favicon.svg') }}">
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'DM Sans',sans-serif;background:#09090B;color:#fafafa;min-height:100vh}
h1,h2,h3,h4,h5,h6{font-family:'Space Grotesk',sans-serif}
.bg-grid{background-image:radial-gradient(ellipse 80% 50% at 50% 0%,rgba(239,68,68,.06) 0%,transparent 60%),radial-gradient(ellipse 60% 40% at 80% 100%,rgba(45,212,191,.04) 0%,transparent 60%),linear-gradient(rgba(63,63,70,.15) 1px,transparent 1px),linear-gradient(90deg,rgba(63,63,70,.15) 1px,transparent 1px);background-size:100% 100%,100% 100%,40px 40px,40px 40px}
.input-field{background:#18181B;border:1px solid #27272A;color:#FAFAFA;border-radius:10px;padding:12px 16px;width:100%;outline:none;transition:border-color .2s;font-size:14px}
.input-field:focus{border-color:#EF4444}
.input-field::placeholder{color:#71717A}
.input-icon{position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#71717A;pointer-events:none}
.pulse-ring{animation:pulseRing 2s ease-in-out infinite}
@keyframes pulseRing{0%,100%{box-shadow:0 0 0 0 rgba(239,68,68,.4)}70%{box-shadow:0 0 0 15px rgba(239,68,68,0)}}
.float-card{animation:floatCard 6s ease-in-out infinite}
@keyframes floatCard{0%,100%{transform:translateY(0)}50%{transform:translateY(-10px)}}
.error-msg{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.3);color:#FCA5A5;border-radius:8px;padding:10px 14px;font-size:13px;margin-bottom:16px}
</style>
</head>
<body class="bg-grid">

<div class="min-h-screen flex">
  <!-- Left: Branding -->
  <div class="hidden lg:flex flex-1 flex-col justify-center items-center p-12 relative overflow-hidden">
    <div class="absolute top-0 left-0 w-72 h-72 bg-red-600/10 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-teal-600/5 rounded-full blur-3xl"></div>

    <div class="relative z-10 max-w-md">
      <div class="flex items-center gap-4 mb-8">
        <div class="w-14 h-14 rounded-2xl bg-red-600 flex items-center justify-center pulse-ring">
          <i class="fa-solid fa-heart-pulse text-white text-2xl"></i>
        </div>
        <div>
          <h1 class="text-4xl font-bold tracking-tight">LifeLine</h1>
          <p class="text-zinc-500 text-sm">Emergency First Aid System</p>
        </div>
      </div>

      <p class="text-zinc-400 text-lg leading-relaxed mb-10">Your intelligent companion for emergency medical guidance. Get instant access to life-saving first aid instructions, symptom triage, and CPR assistance.</p>

      <div class="space-y-5">
        <div class="flex items-center gap-4">
          <div class="w-10 h-10 rounded-xl bg-red-600/15 flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-book-medical text-red-400"></i></div>
          <div><p class="font-semibold text-sm">12 Emergency Guides</p><p class="text-xs text-zinc-500">Step-by-step instructions</p></div>
        </div>
        <div class="flex items-center gap-4">
          <div class="w-10 h-10 rounded-xl bg-teal-600/15 flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-person text-teal-400"></i></div>
          <div><p class="font-semibold text-sm">Interactive Body Map</p><p class="text-xs text-zinc-500">Click to find conditions</p></div>
        </div>
        <div class="flex items-center gap-4">
          <div class="w-10 h-10 rounded-xl bg-orange-600/15 flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-stethoscope text-orange-400"></i></div>
          <div><p class="font-semibold text-sm">Smart Symptom Checker</p><p class="text-xs text-zinc-500">AI-powered triage assessment</p></div>
        </div>
        <div class="flex items-center gap-4">
          <div class="w-10 h-10 rounded-xl bg-blue-600/15 flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-heart-pulse text-blue-400"></i></div>
          <div><p class="font-semibold text-sm">CPR Metronome</p><p class="text-xs text-zinc-500">Real-time audio guidance</p></div>
        </div>
      </div>
    </div>
  </div>

  <!-- Right: Login Form -->
  <div class="flex-1 flex items-center justify-center p-6">
    <div class="w-full max-w-md">
      <div class="flex lg:hidden items-center gap-3 mb-8 justify-center">
        <div class="w-10 h-10 rounded-xl bg-red-600 flex items-center justify-center"><i class="fa-solid fa-heart-pulse text-white"></i></div>
        <h1 class="text-2xl font-bold">LifeLine</h1>
      </div>

      <div class="bg-[#18181B]/80 backdrop-blur-xl border border-zinc-800 rounded-2xl p-8">
        <div class="text-center mb-8">
          <div class="w-16 h-16 rounded-2xl bg-zinc-800 flex items-center justify-center mx-auto mb-4 float-card">
            <i class="fa-solid fa-shield-halved text-2xl text-red-400"></i>
          </div>
          <h2 class="text-2xl font-bold mb-1">Welcome Back</h2>
          <p class="text-zinc-500 text-sm">Sign in to access the emergency system</p>
        </div>

        @if(session('error'))
          <div class="error-msg"><i class="fa-solid fa-circle-exclamation mr-2"></i>{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
          @csrf
          <div>
            <label class="text-xs text-zinc-400 mb-1.5 block font-medium">Email Address</label>
            <div class="relative">
              <i class="fa-solid fa-envelope input-icon"></i>
              <input type="email" name="email" value="{{ old('email') }}" class="input-field pl-11" placeholder="you@example.com" required>
            </div>
          </div>
          <div>
            <label class="text-xs text-zinc-400 mb-1.5 block font-medium">Password</label>
            <div class="relative">
              <i class="fa-solid fa-lock input-icon"></i>
              <input type="password" name="password" class="input-field pl-11 pr-11" placeholder="Enter your password" required>
              <button type="button" onclick="togglePass()" class="absolute right-3 top-1/2 -translate-y-1/2 text-zinc-500 hover:text-zinc-300 transition-colors">
                <i class="fa-solid fa-eye" id="eyeIcon"></i>
              </button>
            </div>
          </div>
          <button type="submit" class="w-full py-3.5 bg-red-600 hover:bg-red-500 rounded-xl font-semibold text-white transition-all flex items-center justify-center gap-2">
            <i class="fa-solid fa-right-to-bracket"></i> Sign In
          </button>
        </form>

        <div class="mt-6 text-center">
          <p class="text-zinc-500 text-sm">Don't have an account? <a href="{{ route('register') }}" class="text-red-400 hover:text-red-300 font-semibold transition-colors">Create Account</a></p>
          <p class="text-zinc-500 text-sm mt-2">or <a href="/" class="text-red-400 hover:text-red-300 font-semibold transition-colors">Go back to landing page</a></p>
        </div>

        
      </div>

      <p class="text-center text-zinc-600 text-xs mt-6">&copy; 2025 LifeLine Emergency First Aid System</p>
    </div>
  </div>
</div>

<script>
function togglePass(){
  var f=document.querySelector('input[name="password"]');
  var i=document.getElementById('eyeIcon');
  if(f.type==='password'){f.type='text';i.className='fa-solid fa-eye-slash'}
  else{f.type='password';i.className='fa-solid fa-eye'}
}
</script>
</body>
</html>
