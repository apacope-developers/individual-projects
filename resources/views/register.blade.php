<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register — LifeLine</title>
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
.error-msg{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.3);color:#FCA5A5;border-radius:8px;padding:10px 14px;font-size:13px;margin-bottom:16px}
.pw-check{display:flex;align-items:center;gap:6px;margin-top:6px;font-size:12px;color:#71717A}
.pw-check.done{color:#2DD4BF}
.pw-check.done::before{content:"\2713"}
</style>
</head>
<body class="bg-grid">

<div class="min-h-screen flex items-center justify-center p-6">
  <div class="w-full max-w-md">
    <div class="flex items-center gap-3 mb-8 justify-center">
      <div class="w-11 h-11 rounded-xl bg-red-600 flex items-center justify-center"><i class="fa-solid fa-heart-pulse text-white text-lg"></i></div>
      <h1 class="text-2xl font-bold">LifeLine</h1>
    </div>

    <div class="bg-[#18181B]/80 backdrop-blur-xl border border-zinc-800 rounded-2xl p-8">
      <div class="text-center mb-8">
        <div class="w-16 h-16 rounded-2xl bg-zinc-800 flex items-center justify-center mx-auto mb-4">
          <i class="fa-solid fa-user-plus text-2xl text-teal-400"></i>
        </div>
        <h2 class="text-2xl font-bold mb-1">Create Account</h2>
        <p class="text-zinc-500 text-sm">Join LifeLine to access emergency tools</p>
      </div>

      @if($errors->any())
        <div class="error-msg mb-4">
          @foreach ($errors->all() as $error)
            <div class="flex items-start gap-2"><i class="fa-solid fa-circle-exclamation mt-0.5"></i><span>{{ $error }}</span></div>
          @endforeach
        </div>
      @endif

      <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf
        <div>
          <label class="text-xs text-zinc-400 mb-1.5 block font-medium">Full Name</label>
          <div class="relative">
            <i class="fa-solid fa-user input-icon"></i>
            <input type="text" name="name" value="{{ old('name') }}" class="input-field pl-11" placeholder="John Doe" required>
          </div>
        </div>
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
            <input type="password" name="password" id="pw1" class="input-field pl-11 pr-11" placeholder="Minimum 8 characters" required minlength="8" oninput="checkPw()">
            <button type="button" onclick="togglePw('pw1','eye1')" class="absolute right-3 top-1/2 -translate-y-1/2 text-zinc-500 hover:text-zinc-300"><i class="fa-solid fa-eye" id="eye1"></i></button>
          </div>
          <div class="pw-check" id="pwLen"><i class="fa-solid fa-circle text-[8px]"></i> At least 8 characters</div>
        </div>
        <div>
          <label class="text-xs text-zinc-400 mb-1.5 block font-medium">Confirm Password</label>
          <div class="relative">
            <i class="fa-solid fa-lock input-icon"></i>
            <input type="password" name="password_confirmation" id="pw2" class="input-field pl-11 pr-11" placeholder="Repeat your password" required oninput="checkPw()">
            <button type="button" onclick="togglePw('pw2','eye2')" class="absolute right-3 top-1/2 -translate-y-1/2 text-zinc-500 hover:text-zinc-300"><i class="fa-solid fa-eye" id="eye2"></i></button>
          </div>
          <div class="pw-check" id="pwMatch"><i class="fa-solid fa-circle text-[8px]"></i> Passwords match</div>
        </div>
        <button type="submit" class="w-full py-3.5 bg-red-600 hover:bg-red-500 rounded-xl font-semibold text-white transition-all flex items-center justify-center gap-2">
          <i class="fa-solid fa-user-plus"></i> Create Account
        </button>
      </form>

      <div class="mt-6 text-center">
        <p class="text-zinc-500 text-sm">Already have an account? <a href="{{ route('login') }}" class="text-red-400 hover:text-red-300 font-semibold transition-colors">Sign In</a></p>
      </div>
    </div>

    <p class="text-center text-zinc-600 text-xs mt-6">&copy; 2025 LifeLine Emergency First Aid System</p>
  </div>
</div>

<script>
function togglePw(id,eyeId){var f=document.getElementById(id);var i=document.getElementById(eyeId);if(f.type==='password'){f.type='text';i.className='fa-solid fa-eye-slash'}else{f.type='password';i.className='fa-solid fa-eye'}}
function checkPw(){var p1=document.getElementById('pw1').value;var p2=document.getElementById('pw2').value;var len=document.getElementById('pwLen');var match=document.getElementById('pwMatch');len.className=p1.length>=8?'pw-check done':'pw-check';match.className=(p2.length>0&&p1===p2)?'pw-check done':'pw-check'}
</script>
</body>
</html>
