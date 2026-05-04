@extends('admin')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-white mb-2">Rwanda Doctors Network</h2>
        <p class="text-zinc-400">Manage healthcare professionals across Rwanda</p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-[#18181B] border border-zinc-800 rounded-xl p-6">
            <div class="flex items-center justify-between mb-2">
                <span class="text-zinc-400 text-sm">Total Doctors</span>
                <i class="fa-solid fa-user-doctor text-blue-400"></i>
            </div>
            <div class="text-2xl font-bold text-blue-400">{{ $stats['total'] }}</div>
        </div>
        <div class="bg-[#18181B] border border-zinc-800 rounded-xl p-6">
            <div class="flex items-center justify-between mb-2">
                <span class="text-zinc-400 text-sm">Active</span>
                <i class="fa-solid fa-check-circle text-green-400"></i>
            </div>
            <div class="text-2xl font-bold text-green-400">{{ $stats['active'] }}</div>
        </div>
        <div class="bg-[#18181B] border border-zinc-800 rounded-xl p-6">
            <div class="flex items-center justify-between mb-2">
                <span class="text-zinc-400 text-sm">Pending</span>
                <i class="fa-solid fa-clock text-yellow-400"></i>
            </div>
            <div class="text-2xl font-bold text-yellow-400">{{ $stats['pending'] }}</div>
        </div>
        <div class="bg-[#18181B] border border-zinc-800 rounded-xl p-6">
            <div class="flex items-center justify-between mb-2">
                <span class="text-zinc-400 text-sm">Available</span>
                <i class="fa-solid fa-phone text-teal-400"></i>
            </div>
            <div class="text-2xl font-bold text-teal-400">{{ $stats['available'] }}</div>
        </div>
    </div>

    <!-- Add Doctor Button -->
    <div class="mb-6">
        <button onclick="openAddDoctorModal()" class="px-6 py-3 bg-red-600 hover:bg-red-500 text-white rounded-lg font-medium transition-colors">
            <i class="fa-solid fa-plus mr-2"></i> Add New Doctor
        </button>
    </div>

    <!-- Doctors Table -->
    <div class="bg-[#18181B] border border-zinc-800 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-zinc-800">
                        <th class="px-6 py-4 text-left text-xs font-medium text-zinc-400 uppercase tracking-wider">Doctor</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-zinc-400 uppercase tracking-wider">Specialty</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-zinc-400 uppercase tracking-wider">Location</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-zinc-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-zinc-400 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-zinc-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800">
                    @forelse($doctors as $doctor)
                    <tr class="hover:bg-zinc-800/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-r from-red-600/20 to-teal-600/20 flex items-center justify-center mr-3">
                                    <i class="fa-solid fa-user-doctor text-teal-400"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-white">{{ $doctor->full_name }}</div>
                                    <div class="text-xs text-zinc-400">{{ $doctor->hospital_clinic }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full bg-blue-600/20 text-blue-400">
                                {{ $doctor->specialty }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-zinc-300">{{ $doctor->location }}</div>
                            <div class="text-xs text-zinc-500">{{ $doctor->years_of_experience }} years exp.</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-1 text-xs rounded-full {{ $doctor->status === 'active' ? 'bg-green-600/20 text-green-400' : ($doctor->status === 'pending' ? 'bg-yellow-600/20 text-yellow-400' : 'bg-red-600/20 text-red-400') }}">
                                    {{ ucfirst($doctor->status) }}
                                </span>
                                @if($doctor->is_available)
                                    <span class="px-2 py-1 text-xs rounded-full bg-teal-600/20 text-teal-400">
                                        Available
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm">
                                <div class="text-zinc-300">{{ $doctor->phone }}</div>
                                @if($doctor->whatsapp)
                                    <div class="text-xs text-green-400">
                                        <i class="fab fa-whatsapp mr-1"></i>{{ $doctor->whatsapp }}
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @if($doctor->status === 'pending')
                                    <form method="POST" action="{{ route('admin.verify-doctor', $doctor) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="px-3 py-1 bg-green-600 hover:bg-green-500 text-white text-xs rounded transition-colors">
                                            <i class="fa-solid fa-check mr-1"></i>Verify
                                        </button>
                                    </form>
                                @endif
                                
                                <button onclick="toggleAvailability({{ $doctor->id }}, '{{ $doctor->is_available ? 'unavailable' : 'available' }}')" 
                                        class="px-3 py-1 {{ $doctor->is_available ? 'bg-orange-600 hover:bg-orange-500' : 'bg-teal-600 hover:bg-teal-500' }} text-white text-xs rounded transition-colors">
                                    <i class="fa-solid fa-{{ $doctor->is_available ? 'pause' : 'play' }} mr-1"></i>
                                    {{ $doctor->is_available ? 'Pause' : 'Activate' }}
                                </button>
                                
                                <button onclick="editDoctor({{ $doctor->id }})" class="px-3 py-1 bg-blue-600 hover:bg-blue-500 text-white text-xs rounded transition-colors">
                                    <i class="fa-solid fa-edit"></i>
                                </button>
                                
                                <form method="POST" action="{{ route('admin.delete-doctor', $doctor) }}" onsubmit="return confirm('Are you sure you want to delete this doctor?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1 bg-red-600 hover:bg-red-500 text-white text-xs rounded transition-colors">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <i class="fa-solid fa-user-doctor text-4xl text-zinc-600 mb-4"></i>
                            <p class="text-zinc-400">No doctors registered yet</p>
                            <button onclick="openAddDoctorModal()" class="mt-4 px-4 py-2 bg-red-600 hover:bg-red-500 text-white rounded-lg text-sm transition-colors">
                                <i class="fa-solid fa-plus mr-2"></i> Add First Doctor
                            </button>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Doctor Modal -->
<div id="addDoctorModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm flex items-center justify-center z-50 p-4 hidden">
    <div class="bg-[#18181B] border border-zinc-800 rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-zinc-800">
            <h3 class="text-2xl font-bold text-white mb-2">Add New Doctor</h3>
            <p class="text-zinc-400">Register a healthcare professional for Rwanda network</p>
        </div>
        
        <form method="POST" action="{{ route('admin.store-doctor') }}" class="p-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Personal Information -->
                <div class="space-y-4">
                    <h4 class="text-lg font-semibold text-white border-b border-zinc-700 pb-2">Personal Information</h4>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-zinc-300 mb-2">First Name</label>
                            <input type="text" name="first_name" required class="w-full px-3 py-2 bg-[#09090B] border border-zinc-700 rounded-lg text-white focus:outline-none focus:border-red-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-zinc-300 mb-2">Last Name</label>
                            <input type="text" name="last_name" required class="w-full px-3 py-2 bg-[#09090B] border border-zinc-700 rounded-lg text-white focus:outline-none focus:border-red-500">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-zinc-300 mb-2">Email</label>
                        <input type="email" name="email" required class="w-full px-3 py-2 bg-[#09090B] border border-zinc-700 rounded-lg text-white focus:outline-none focus:border-red-500">
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-zinc-300 mb-2">Phone</label>
                            <input type="tel" name="phone" required class="w-full px-3 py-2 bg-[#09090B] border border-zinc-700 rounded-lg text-white focus:outline-none focus:border-red-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-zinc-300 mb-2">WhatsApp (Optional)</label>
                            <input type="tel" name="whatsapp" class="w-full px-3 py-2 bg-[#09090B] border border-zinc-700 rounded-lg text-white focus:outline-none focus:border-red-500">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-zinc-300 mb-2">Gender</label>
                        <select name="gender" required class="w-full px-3 py-2 bg-[#09090B] border border-zinc-700 rounded-lg text-white focus:outline-none focus:border-red-500">
                            <option value="">Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-zinc-300 mb-2">Date of Birth</label>
                        <input type="date" name="date_of_birth" required class="w-full px-3 py-2 bg-[#09090B] border border-zinc-700 rounded-lg text-white focus:outline-none focus:border-red-500">
                    </div>
                </div>
                
                <!-- Professional Information -->
                <div class="space-y-4">
                    <h4 class="text-lg font-semibold text-white border-b border-zinc-700 pb-2">Professional Information</h4>
                    
                                        
                    <div>
                        <label class="block text-sm font-medium text-zinc-300 mb-2">Specialty</label>
                        <select name="specialty" required class="w-full px-3 py-2 bg-[#09090B] border border-zinc-700 rounded-lg text-white focus:outline-none focus:border-red-500">
                            <option value="">Select Specialty</option>
                            @foreach(App\Models\Doctor::getSpecialties() as $specialty)
                            <option value="{{ $specialty }}">{{ $specialty }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                                        
                    <div>
                        <label class="block text-sm font-medium text-zinc-300 mb-2">Hospital/Clinic</label>
                        <input type="text" name="hospital_clinic" required class="w-full px-3 py-2 bg-[#09090B] border border-zinc-700 rounded-lg text-white focus:outline-none focus:border-red-500">
                    </div>
                    
                                    </div>
            </div>
            
            <!-- Location Information -->
            <div class="mt-6 space-y-4">
                <h4 class="text-lg font-semibold text-white border-b border-zinc-700 pb-2">Location Information</h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-zinc-300 mb-2">Province</label>
                        <select name="province" required class="w-full px-3 py-2 bg-[#09090B] border border-zinc-700 rounded-lg text-white focus:outline-none focus:border-red-500">
                            <option value="">Select Province</option>
                            @foreach(App\Models\Doctor::getProvinces() as $key => $province)
                            <option value="{{ $key }}">{{ $province }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-zinc-300 mb-2">District</label>
                        <input type="text" name="district" required class="w-full px-3 py-2 bg-[#09090B] border border-zinc-700 rounded-lg text-white focus:outline-none focus:border-red-500">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-zinc-300 mb-2">Address</label>
                    <input type="text" name="address" required class="w-full px-3 py-2 bg-[#09090B] border border-zinc-700 rounded-lg text-white focus:outline-none focus:border-red-500">
                </div>
                
                            </div>
            
            <!-- Emergency Contact -->
            <div class="mt-6 space-y-4">
                <h4 class="text-lg font-semibold text-white border-b border-zinc-700 pb-2">Emergency Contact</h4>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-zinc-300 mb-2">Emergency Contact Name</label>
                        <input type="text" name="emergency_contact_name" required class="w-full px-3 py-2 bg-[#09090B] border border-zinc-700 rounded-lg text-white focus:outline-none focus:border-red-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-300 mb-2">Emergency Contact Phone</label>
                        <input type="tel" name="emergency_contact_phone" required class="w-full px-3 py-2 bg-[#09090B] border border-zinc-700 rounded-lg text-white focus:outline-none focus:border-red-500">
                    </div>
                </div>
            </div>
            
                        
            <div class="flex justify-end gap-4 mt-8">
                <button type="button" onclick="closeAddDoctorModal()" class="px-6 py-2 bg-zinc-700 hover:bg-zinc-600 text-white rounded-lg transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-2 bg-red-600 hover:bg-red-500 text-white rounded-lg transition-colors">
                    <i class="fa-solid fa-save mr-2"></i> Add Doctor
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openAddDoctorModal() {
    document.getElementById('addDoctorModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeAddDoctorModal() {
    document.getElementById('addDoctorModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function toggleAvailability(doctorId, action) {
    if (confirm(`Are you sure you want to mark this doctor as ${action}?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/doctors/${doctorId}/toggle-availability`;
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        form.innerHTML = `
            <input type="hidden" name="_token" value="${csrfToken}">
            <input type="hidden" name="_method" value="POST">
        `;
        
        document.body.appendChild(form);
        form.submit();
    }
}

function editDoctor(doctorId) {
    // This would open an edit modal - for now just show an alert
    alert('Edit functionality coming soon! Doctor ID: ' + doctorId);
}

// Close modal when clicking outside
document.getElementById('addDoctorModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeAddDoctorModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !document.getElementById('addDoctorModal').classList.contains('hidden')) {
        closeAddDoctorModal();
    }
});
</script>
@endsection
