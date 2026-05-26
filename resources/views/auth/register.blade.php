<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - Attendance System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #1a1a2e 0%, #0f0f1e 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .register-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: slideIn 0.5s ease-out;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .form-control {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #1a365d;
            box-shadow: 0 0 0 0.2rem rgba(26, 54, 93, 0.25);
            transform: translateY(-2px);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #1a1a2e 0%, #0f0f1e 100%);
            border: none;
            border-radius: 10px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(26, 54, 93, 0.3);
        }
        
        .card-title {
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 2rem;
        }
        
        .alert-danger {
            border-radius: 10px;
            border: none;
            animation: shake 0.5s ease-in-out;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        
        .input-group-text {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-right: none;
            border-radius: 10px 0 0 10px;
        }
        
        .form-control.with-icon {
            border-left: none;
            border-radius: 0 10px 10px 0;
        }
        
        .link-hover {
            color: #1a365d;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        
        .link-hover:hover {
            color: #0f0f1e;
            text-decoration: underline;
        }
        
        .password-toggle {
            cursor: pointer;
            color: #6c757d;
            transition: color 0.3s ease;
        }
        
        .password-toggle:hover {
            color: #1a365d;
        }
        
        .password-strength {
            height: 5px;
            border-radius: 3px;
            margin-top: 5px;
            transition: all 0.3s ease;
        }
        
        .strength-weak { background: #dc3545; width: 33%; }
        .strength-medium { background: #ffc107; width: 66%; }
        .strength-strong { background: #28a745; width: 100%; }
        
        .role-card {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .role-card:hover {
            border-color: #1a365d;
            transform: translateY(-2px);
        }
        
        .role-card.selected {
            border-color: #1a365d;
            background: rgba(26, 54, 93, 0.1);
        }
        
        .role-icon {
            font-size: 2rem;
            margin-bottom: 10px;
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center">
    <div class="register-container p-4" style="max-width: 500px;">
        <div class="text-center mb-4">
            <i class="fas fa-user-plus fa-3x text-primary mb-3"></i>
            <h2 class="card-title">Create Account</h2>
            <p class="text-muted">Join the attendance management system</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger mb-4">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Registration failed:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="/register" id="registerForm">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">Full Name</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-user"></i>
                    </span>
                    <input name="name" type="text" class="form-control with-icon" required 
                           placeholder="Enter your full name" value="{{ old('name') }}">
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label fw-semibold">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-envelope"></i>
                    </span>
                    <input name="email" type="email" class="form-control with-icon" required 
                           placeholder="Enter your email" value="{{ old('email') }}">
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label fw-semibold">Password</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input name="password" type="password" class="form-control with-icon" id="password" 
                           required placeholder="Create a password">
                    <span class="input-group-text password-toggle" onclick="togglePassword('password')">
                        <i class="fas fa-eye" id="passwordIcon"></i>
                    </span>
                </div>
                <div class="password-strength" id="passwordStrength"></div>
                <small class="text-muted" id="passwordHint">Use 8+ characters with letters, numbers & symbols</small>
            </div>
            
            <div class="mb-4">
                <label class="form-label fw-semibold">Confirm Password</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input name="password_confirmation" type="password" class="form-control with-icon" id="confirmPassword" 
                           required placeholder="Confirm your password">
                    <span class="input-group-text password-toggle" onclick="togglePassword('confirmPassword')">
                        <i class="fas fa-eye" id="confirmPasswordIcon"></i>
                    </span>
                </div>
            </div>
            
            <div class="mb-4">
                <label class="form-label fw-semibold">Select Role</label>
                <div class="row">
                    <div class="col-6">
                        <div class="role-card text-center" onclick="selectRole('teacher')">
                            <div class="role-icon text-primary">
                                <i class="fas fa-chalkboard-teacher"></i>
                            </div>
                            <h6 class="mb-1">Teacher</h6>
                            <small class="text-muted">Manage attendance</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="role-card text-center" onclick="selectRole('student')">
                            <div class="role-icon text-success">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <h6 class="mb-1">Student</h6>
                            <small class="text-muted">View attendance</small>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="role" id="selectedRole" value="{{ old('role') ?: 'teacher' }}" required>
            </div>
            
            <div class="mb-4" id="classroomField" style="display: none;">
                <label class="form-label fw-semibold">Classroom</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-door-open"></i>
                    </span>
                    <input name="classroom" type="text" class="form-control with-icon" 
                           placeholder="e.g. L4SWD, 5A, 3B" value="{{ old('classroom') }}">
                </div>
                <small class="text-muted">Enter your classroom assignment</small>
            </div>
            
            <div class="mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="terms" required>
                    <label class="form-check-label" for="terms">
                        I agree to the <a href="#" class="link-hover">Terms and Conditions</a>
                    </label>
                </div>
            </div>
            
            <button class="btn btn-primary w-100 mb-3" type="submit">
                <i class="fas fa-user-plus me-2"></i>
                Create Account
            </button>
            
            <div class="text-center">
                <span class="text-muted">Already have an account? </span>
                <a href="/login" class="link-hover fw-semibold">Sign In</a>
            </div>
            
            @if(session('success'))
                <div class="alert alert-success mt-3 text-center">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                </div>
                
                <div class="text-center mt-3">
                    <a href="/login" class="btn btn-outline-secondary">
                        <i class="fas fa-sign-out-alt me-2"></i>
                        Go to Login
                    </a>
                </div>
            @endif
        </form>
    </div>

    <script>
        // Initialize role selection
        document.addEventListener('DOMContentLoaded', function() {
            const selectedRole = document.getElementById('selectedRole').value;
            if (selectedRole) {
                selectRole(selectedRole);
            }
        });
        
        function selectRole(role) {
            // Remove previous selection
            document.querySelectorAll('.role-card').forEach(card => {
                card.classList.remove('selected');
            });
            
            // Add selection to clicked role
            event.currentTarget.classList.add('selected');
            document.getElementById('selectedRole').value = role;
            
            // Show/hide classroom field
            const classroomField = document.getElementById('classroomField');
            if (role === 'student') {
                classroomField.style.display = 'block';
            } else {
                classroomField.style.display = 'none';
            }
        }
        
        function togglePassword(fieldId) {
            const passwordField = document.getElementById(fieldId);
            const passwordIcon = document.getElementById(fieldId + 'Icon');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                passwordIcon.classList.remove('fa-eye');
                passwordIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                passwordIcon.classList.remove('fa-eye-slash');
                passwordIcon.classList.add('fa-eye');
            }
        }
        
        // Password strength checker
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthBar = document.getElementById('passwordStrength');
            const hint = document.getElementById('passwordHint');
            
            let strength = 0;
            
            if (password.length >= 8) strength++;
            if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
            if (password.match(/[0-9]/)) strength++;
            if (password.match(/[^a-zA-Z0-9]/)) strength++;
            
            strengthBar.className = 'password-strength';
            
            if (password.length === 0) {
                strengthBar.style.display = 'none';
                hint.textContent = 'Use 8+ characters with letters, numbers & symbols';
            } else if (strength <= 1) {
                strengthBar.classList.add('strength-weak');
                hint.textContent = 'Weak password - add more variety';
                hint.className = 'text-danger';
            } else if (strength === 2) {
                strengthBar.classList.add('strength-medium');
                hint.textContent = 'Medium strength - could be stronger';
                hint.className = 'text-warning';
            } else {
                strengthBar.classList.add('strength-strong');
                hint.textContent = 'Strong password!';
                hint.className = 'text-success';
            }
            
            strengthBar.style.display = 'block';
        });
        
        // Password confirmation check
        document.getElementById('confirmPassword').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;
            
            if (confirmPassword && password !== confirmPassword) {
                this.style.borderColor = '#dc3545';
            } else {
                this.style.borderColor = '#e9ecef';
            }
        });
        
        // Form validation
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            const terms = document.getElementById('terms').checked;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match!');
                return;
            }
            
            if (!terms) {
                e.preventDefault();
                alert('Please accept the terms and conditions');
                return;
            }
        });
        
        // Add input focus effects
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('focused');
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('focused');
            });
        });
    </script>
</body>
</html>
