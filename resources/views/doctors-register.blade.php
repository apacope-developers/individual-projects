<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join LifeLine Doctors Network - Rwanda</title>
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
        .step-indicator{transition:all 0.3s ease}
        .step-indicator.active{background:#EF4444;color:white;transform:scale(1.1)}
        .form-input{background:#18181B;border:1px solid #27272A;color:#FAFAFA;transition:all 0.2s}
        .form-input:focus{border-color:#EF4444;outline:none;box-shadow:0 0 0 3px rgba(239,68,68,0.1)}
        .btn-primary{background:#EF4444;color:white;transition:all 0.2s}
        .btn-primary:hover{background:#DC2626;transform:translateY(-2px)}
        .btn-secondary{background:#18181B;color:#FAFAFA;border:1px solid #27272A;transition:all 0.2s}
        .btn-secondary:hover{background:#27272A}
    </style>
</head>
<body class="bg-grid">
    <!-- Navigation -->
    <nav class="fixed top-0 w-full bg-[#09090B]/90 backdrop-blur-lg border-b border-zinc-800 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-red-600 flex items-center justify-center">
                        <i class="fa-solid fa-heart-pulse text-white"></i>
                    </div>
                    <span class="text-xl font-bold">LifeLine</span>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('login') }}" class="text-zinc-300 hover:text-white transition-colors">Admin Login</a>
                    <a href="/" class="px-4 py-2 bg-red-600 hover:bg-red-500 rounded-lg font-medium transition-colors">
                        <i class="fa-solid fa-home mr-2"></i> Home
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="min-h-screen flex items-center justify-center px-4 pt-16">
        <div class="max-w-4xl mx-auto text-center">
            <div class="mb-8">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-red-600/20 backdrop-blur-sm border border-red-600/30 rounded-full mb-6">
                    <i class="fa-solid fa-user-doctor text-red-400"></i>
                    <span class="text-sm text-red-300">Rwanda Healthcare Network</span>
                </div>
                <h1 class="text-5xl md:text-6xl font-bold mb-6 bg-gradient-to-r from-white to-zinc-300 bg-clip-text text-transparent">
                    Join Our Doctor Network
                </h1>
                <p class="text-xl text-zinc-300 max-w-3xl mx-auto mb-8 leading-relaxed">
                    Connect with patients across Rwanda through LifeLine. Save lives with your medical expertise and be part of Rwanda's digital healthcare revolution.
                </p>
            </div>

            <!-- Benefits -->
            <div class="grid md:grid-cols-3 gap-6 mb-12">
                <div class="bg-[#18181B]/50 backdrop-blur-xl border border-zinc-800 rounded-2xl p-6 card-hover">
                    <div class="w-12 h-12 rounded-xl bg-red-600/15 flex items-center justify-center mb-4">
                        <i class="fa-solid fa-users text-red-400 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Reach More Patients</h3>
                    <p class="text-zinc-400 text-sm">Connect with thousands of patients across Rwanda who need your expertise</p>
                </div>
                <div class="bg-[#18181B]/50 backdrop-blur-xl border border-zinc-800 rounded-2xl p-6 card-hover">
                    <div class="w-12 h-12 rounded-xl bg-teal-600/15 flex items-center justify-center mb-4">
                        <i class="fa-solid fa-shield-alt text-teal-400 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Verified Professional</h3>
                    <p class="text-zinc-400 text-sm">Join a trusted network of verified healthcare professionals</p>
                </div>
                <div class="bg-[#18181B]/50 backdrop-blur-xl border border-zinc-800 rounded-2xl p-6 card-hover">
                    <div class="w-12 h-12 rounded-xl bg-blue-600/15 flex items-center justify-center mb-4">
                        <i class="fa-solid fa-mobile-alt text-blue-400 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Digital Platform</h3>
                    <p class="text-zinc-400 text-sm">Modern tools to manage your practice and patient communications</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Registration Form -->
    <section class="py-20 px-4">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold mb-4">Apply to Join</h2>
                <p class="text-xl text-zinc-400">Complete the form below to start your journey with LifeLine</p>
            </div>

            @if(session('success'))
                <div class="bg-green-600/10 border border-green-600/20 rounded-lg p-4 mb-8">
                    <div class="flex items-center">
                        <i class="fa-solid fa-check-circle text-green-400 mr-3"></i>
                        <p class="text-green-300">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-600/10 border border-red-600/20 rounded-lg p-4 mb-8">
                    <div class="flex items-center">
                        <i class="fa-solid fa-exclamation-triangle text-red-400 mr-3"></i>
                        <p class="text-red-300">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.store-doctor') }}" class="space-y-8">
                @csrf
                
                <!-- Personal Information -->
                <div class="bg-[#18181B] border border-zinc-800 rounded-2xl p-8">
                    <h3 class="text-2xl font-bold mb-6 flex items-center">
                        <i class="fa-solid fa-user text-red-400 mr-3"></i>
                        Personal Information
                    </h3>
                    
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-zinc-300 mb-2">First Name *</label>
                            <input type="text" name="first_name" required class="form-input w-full px-4 py-3 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-zinc-300 mb-2">Last Name *</label>
                            <input type="text" name="last_name" required class="form-input w-full px-4 py-3 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-zinc-300 mb-2">Email *</label>
                            <input type="email" name="email" required class="form-input w-full px-4 py-3 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-zinc-300 mb-2">Phone *</label>
                            <input type="tel" name="phone" required placeholder="+250 7XX XXX XXX" class="form-input w-full px-4 py-3 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-zinc-300 mb-2">WhatsApp (Optional)</label>
                            <input type="tel" name="whatsapp" placeholder="+250 7XX XXX XXX" class="form-input w-full px-4 py-3 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-zinc-300 mb-2">Gender *</label>
                            <select name="gender" required class="form-input w-full px-4 py-3 rounded-lg">
                                <option value="">Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-zinc-300 mb-2">Date of Birth *</label>
                            <input type="date" name="date_of_birth" required class="form-input w-full px-4 py-3 rounded-lg">
                        </div>
                        </div>
                </div>

                <!-- Professional Information -->
                <div class="bg-[#18181B] border border-zinc-800 rounded-2xl p-8">
                    <h3 class="text-2xl font-bold mb-6 flex items-center">
                        <i class="fa-solid fa-stethoscope text-blue-400 mr-3"></i>
                        Professional Information
                    </h3>
                    
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-zinc-300 mb-2">Specialty *</label>
                            <select name="specialty" required class="form-input w-full px-4 py-3 rounded-lg">
                                <option value="">Select Specialty</option>
                                @foreach(\App\Models\Doctor::getSpecialties() as $specialty)
                                <option value="{{ $specialty }}">{{ $specialty }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-zinc-300 mb-2">Hospital/Clinic *</label>
                            <input type="text" name="hospital_clinic" required class="form-input w-full px-4 py-3 rounded-lg">
                        </div>
                                            </div>
                </div>

                <!-- Location Information -->
                <div class="bg-[#18181B] border border-zinc-800 rounded-2xl p-8">
                    <h3 class="text-2xl font-bold mb-6 flex items-center">
                        <i class="fa-solid fa-map-marker-alt text-teal-400 mr-3"></i>
                        Location Information
                    </h3>
                    
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-zinc-300 mb-2">Province *</label>
                            <select name="province" required class="form-input w-full px-4 py-3 rounded-lg">
                                <option value="">Select Province</option>
                                @foreach(\App\Models\Doctor::getProvinces() as $key => $province)
                                <option value="{{ $key }}">{{ $province }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-zinc-300 mb-2">District *</label>
                            <input type="text" name="district" required class="form-input w-full px-4 py-3 rounded-lg">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-zinc-300 mb-2">Address *</label>
                            <input type="text" name="address" required class="form-input w-full px-4 py-3 rounded-lg">
                        </div>
                    </div>
                </div>

                <!-- Emergency Contact -->
                <div class="bg-[#18181B] border border-zinc-800 rounded-2xl p-8">
                    <h3 class="text-2xl font-bold mb-6 flex items-center">
                        <i class="fa-solid fa-phone-alt text-orange-400 mr-3"></i>
                        Emergency Contact
                    </h3>
                    
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-zinc-300 mb-2">Emergency Contact Name *</label>
                            <input type="text" name="emergency_contact_name" required class="form-input w-full px-4 py-3 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-zinc-300 mb-2">Emergency Contact Phone *</label>
                            <input type="tel" name="emergency_contact_phone" required class="form-input w-full px-4 py-3 rounded-lg">
                        </div>
                    </div>
                </div>

                
                <!-- Terms and Submit -->
                <div class="text-center">
                    <div class="mb-6">
                        <label class="flex items-start justify-center">
                            <input type="checkbox" required class="mt-1 mr-3 rounded">
                            <span class="text-sm text-zinc-300">
                                I certify that all information provided is accurate and I am a licensed medical professional in Rwanda. I understand that my application will be reviewed before activation.
                            </span>
                        </label>
                    </div>
                    
                    <button type="submit" class="btn-primary px-8 py-4 rounded-xl font-semibold text-lg">
                        <i class="fa-solid fa-paper-plane mr-2"></i>
                        Submit Application
                    </button>
                </div>
            </form>
        </div>
    </section>

    <!-- Footer -->
    <footer class="border-t border-zinc-800 py-12 px-4">
        <div class="max-w-7xl mx-auto text-center">
            <p class="text-zinc-500">&copy; 2025 LifeLine Emergency First Aid System — Rwanda Doctors Network</p>
        </div>
    </footer>
</body>
</html>
