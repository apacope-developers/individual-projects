@extends('admin')

@section('content')
<!-- Header -->
<div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6 lg:mb-8">
  <div>
    <h2 class="text-xl lg:text-2xl font-bold">Admin Dashboard</h2>
    <p class="text-zinc-500 text-sm">Manage users and monitor system activity</p>
  </div>
  @if(session('success'))
    <div class="bg-green-600/10 border border-green-600/20 rounded-lg px-3 lg:px-4 py-2 text-sm text-green-400">
      <i class="fa-solid fa-check-circle mr-2"></i>{{ session('success') }}
    </div>
  @endif
</div>

<!-- Stats -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6 lg:mb-8 stats-grid">
  <div class="stat-card">
    <div class="flex items-center justify-between mb-2">
      <p class="text-zinc-500 text-xs lg:text-sm">Total Users</p>
      <div class="w-8 h-8 rounded-lg bg-teal-600/20 flex items-center justify-center"><i class="fa-solid fa-users text-teal-400 text-xs"></i></div>
    </div>
    <p class="text-2xl lg:text-3xl font-bold" style="color:#2DD4BF">{{ $userCount }}</p>
  </div>
  <div class="stat-card">
    <div class="flex items-center justify-between mb-2">
      <p class="text-zinc-500 text-xs lg:text-sm">Total Sessions</p>
      <div class="w-8 h-8 rounded-lg bg-blue-600/20 flex items-center justify-center"><i class="fa-solid fa-clock text-blue-400 text-xs"></i></div>
    </div>
    <p class="text-2xl lg:text-3xl font-bold" style="color:#3B82F6">24</p>
  </div>
  <div class="stat-card">
    <div class="flex items-center justify-between mb-2">
      <p class="text-zinc-500 text-xs lg:text-sm">System Status</p>
      <div class="w-8 h-8 rounded-lg bg-green-600/20 flex items-center justify-center"><i class="fa-solid fa-check text-green-400 text-xs"></i></div>
    </div>
    <p class="text-2xl lg:text-3xl font-bold" style="color:#10B981">Online</p>
  </div>
</div>

<!-- Users Table -->
<div class="bg-[#18181B] border border-zinc-800 rounded-xl overflow-hidden">
  <div class="px-4 lg:px-6 py-3 lg:py-4 border-b border-zinc-800 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <h3 class="text-base lg:text-lg font-semibold">Registered Users</h3>
    <button onclick="openAddUserModal()" class="btn btn-danger">
      <i class="fa-solid fa-plus"></i> <span class="hidden sm:inline">Add User</span><span class="sm:hidden">Add</span>
    </button>
  </div>
  <div class="overflow-x-auto">
    <table class="w-full users-table">
      <thead>
        <tr class="border-b border-zinc-800">
          <th class="px-3 lg:px-4 py-2 lg:py-3 text-left text-xs font-medium text-zinc-400 uppercase tracking-wider">User</th>
          <th class="px-3 lg:px-4 py-2 lg:py-3 text-left text-xs font-medium text-zinc-400 uppercase tracking-wider">Email</th>
          <th class="px-3 lg:px-4 py-2 lg:py-3 text-left text-xs font-medium text-zinc-400 uppercase tracking-wider hidden sm:table-cell">Joined</th>
          <th class="px-3 lg:px-4 py-2 lg:py-3 text-right text-xs font-medium text-zinc-400 uppercase tracking-wider">Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($users as $user)
        <tr class="table-row">
          <td class="px-3 lg:px-4 py-2 lg:py-3">
            <div class="flex items-center gap-2 lg:gap-3">
              <div class="w-6 h-6 lg:w-8 lg:h-8 rounded-full bg-zinc-800 flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-user text-zinc-500 text-xs"></i></div>
              <div>
                <p class="text-sm font-medium truncate max-w-[120px] lg:max-w-none">{{ $user->name }}</p>
                <p class="text-[10px] text-zinc-600">ID: {{ $user->id }}</p>
              </div>
            </div>
          </td>
          <td class="px-3 lg:px-4 py-2 lg:py-3 text-sm text-zinc-400 truncate max-w-[150px] lg:max-w-none">{{ $user->email }}</td>
          <td class="px-3 lg:px-4 py-2 lg:py-3 text-xs text-zinc-500 hidden sm:table-cell">{{ $user->created_at->format('M j, Y') }}</td>
          <td class="px-3 lg:px-4 py-2 lg:py-3 text-right">
            <div class="flex gap-1 lg:gap-2 justify-end">
              <button onclick="openEditUserModal({{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}', {{ $user->is_admin ? 'true' : 'false' }})" class="btn btn-ghost text-xs px-2 py-1 lg:px-3 lg:py-1.5">
                <i class="fa-solid fa-edit"></i> <span class="hidden lg:inline">Edit</span>
              </button>
              <form method="POST" action="/admin/users/{{ $user->id }}" onsubmit="return confirm('Are you sure you want to delete this user?')">
                @csrf
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit" class="btn btn-danger text-xs px-2 py-1 lg:px-3 lg:py-1.5">
                  <i class="fa-solid fa-trash-can"></i> <span class="hidden lg:inline">Delete</span>
                </button>
              </form>
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

<!-- Add User Modal -->
<div id="addUserModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
  <div class="bg-[#18181B] border border-zinc-800 rounded-xl p-4 lg:p-6 w-full max-w-md modal-box">
    <h3 class="text-lg font-semibold mb-4">Add New User</h3>
    <form method="POST" action="/admin/users" onsubmit="console.log('Form submitted'); return true;">
      @csrf
      <div class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-zinc-400 mb-1">Name</label>
          <input type="text" name="name" required class="w-full px-3 py-2 bg-[#27272A] border border-zinc-700 rounded-lg text-white focus:outline-none focus:border-red-500">
        </div>
        <div>
          <label class="block text-sm font-medium text-zinc-400 mb-1">Email</label>
          <input type="email" name="email" required class="w-full px-3 py-2 bg-[#27272A] border border-zinc-700 rounded-lg text-white focus:outline-none focus:border-red-500">
        </div>
        <div>
          <label class="block text-sm font-medium text-zinc-400 mb-1">Password</label>
          <input type="password" name="password" required class="w-full px-3 py-2 bg-[#27272A] border border-zinc-700 rounded-lg text-white focus:outline-none focus:border-red-500">
        </div>
        <div>
          <label class="block text-sm font-medium text-zinc-400 mb-1">Confirm Password</label>
          <input type="password" name="password_confirmation" required class="w-full px-3 py-2 bg-[#27272A] border border-zinc-700 rounded-lg text-white focus:outline-none focus:border-red-500">
        </div>
        <div>
          <label class="flex items-center gap-2 text-sm text-zinc-400">
            <input type="checkbox" name="is_admin" class="rounded border-zinc-700 bg-[#27272A] text-red-500 focus:ring-red-500">
            <span>Admin Account</span>
          </label>
        </div>
      </div>
      <div class="flex gap-3 mt-6">
        <button type="button" onclick="closeAddUserModal()" class="flex-1 px-4 py-2 bg-zinc-700 hover:bg-zinc-600 text-white rounded-lg transition-colors">
          Cancel
        </button>
        <button type="submit" class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-500 text-white rounded-lg transition-colors">
          Add User
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Edit User Modal -->
<div id="editUserModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
  <div class="bg-[#18181B] border border-zinc-800 rounded-xl p-4 lg:p-6 w-full max-w-md modal-box">
    <h3 class="text-lg font-semibold mb-4">Edit User</h3>
    <form method="POST" id="editUserForm">
      @csrf
      <input type="hidden" name="_method" value="PUT">
      <div class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-zinc-400 mb-1">Name</label>
          <input type="text" name="name" id="editName" required class="w-full px-3 py-2 bg-[#27272A] border border-zinc-700 rounded-lg text-white focus:outline-none focus:border-red-500">
        </div>
        <div>
          <label class="block text-sm font-medium text-zinc-400 mb-1">Email</label>
          <input type="email" name="email" id="editEmail" required class="w-full px-3 py-2 bg-[#27272A] border border-zinc-700 rounded-lg text-white focus:outline-none focus:border-red-500">
        </div>
                <div>
          <label class="flex items-center gap-2 text-sm text-zinc-400">
            <input type="checkbox" name="is_admin" id="editIsAdmin" class="rounded border-zinc-700 bg-[#27272A] text-red-500 focus:ring-red-500">
            <span>Admin Account</span>
          </label>
        </div>
      </div>
      <div class="flex gap-3 mt-6">
        <button type="button" onclick="closeEditUserModal()" class="flex-1 px-4 py-2 bg-zinc-700 hover:bg-zinc-600 text-white rounded-lg transition-colors">
          Cancel
        </button>
        <button type="submit" class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-500 text-white rounded-lg transition-colors">
          Update User
        </button>
      </div>
    </form>
  </div>
</div>

<script>
// Test function to verify JavaScript is working
console.log('Admin panel JavaScript loaded');

function openAddUserModal() {
  console.log('Opening Add User Modal...');
  
  try {
    const modal = document.getElementById('addUserModal');
    console.log('Modal element:', modal);
    
    if (modal) {
      // Remove hidden class and set display
      modal.classList.remove('hidden');
      modal.style.display = 'flex';
      console.log('Modal opened successfully');
      console.log('Modal display style:', modal.style.display);
      console.log('Modal classes:', modal.className);
    } else {
      console.error('Add User Modal not found in DOM');
      alert('Error: Modal not found. Please check browser console for details.');
    }
  } catch (error) {
    console.error('Error opening modal:', error);
    alert('Error opening modal: ' + error.message);
  }
}

function closeAddUserModal() {
  console.log('Closing Add User Modal...');
  
  try {
    const modal = document.getElementById('addUserModal');
    if (modal) {
      modal.style.display = 'none';
      modal.classList.add('hidden');
      console.log('Modal closed successfully');
      
      // Reset form
      const form = modal.querySelector('form');
      if (form) {
        form.reset();
        console.log('Form reset successfully');
      }
    } else {
      console.error('Add User Modal not found');
    }
  } catch (error) {
    console.error('Error closing modal:', error);
  }
}

// Check for success messages and close modal if user was created
document.addEventListener('DOMContentLoaded', function() {
  // Check if there's a success message
  const successElements = document.querySelectorAll('.bg-green-600\\/10');
  if (successElements.length > 0) {
    console.log('Success message detected, user likely created successfully');
    // Close any open modals
    closeAddUserModal();
  }
});

function openEditUserModal(userId, userName, userEmail, isAdmin) {
  document.getElementById('editUserForm').action = '/admin/users/' + userId;
  document.getElementById('editName').value = userName;
  document.getElementById('editEmail').value = userEmail;
  document.getElementById('editIsAdmin').checked = isAdmin;
  document.getElementById('editUserModal').style.display = 'flex';
}

function closeEditUserModal() {
  document.getElementById('editUserModal').style.display = 'none';
}

// Close modals when clicking outside
document.getElementById('addUserModal').addEventListener('click', function(e) {
  if (e.target === this) {
    closeAddUserModal();
  }
});

document.getElementById('editUserModal').addEventListener('click', function(e) {
  if (e.target === this) {
    closeEditUserModal();
  }
});
</script>

@endsection
