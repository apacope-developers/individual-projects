@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-zinc-100">Emergency Contacts Management</h1>
        <a href="{{ route('admin') }}" class="px-4 py-2 bg-zinc-600 hover:bg-zinc-700 text-zinc-100 rounded-lg transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Back to Admin
        </a>
        
            </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-600/10 border border-green-600/20 text-green-400 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-600/10 border border-red-600/20 text-red-400 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <!-- Edit Contact Modal -->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-zinc-900 rounded-lg p-6 w-full max-w-md mx-4">
            <h3 class="text-xl font-semibold mb-4 text-zinc-100">Edit Contact</h3>
            <form id="editForm" method="POST" action="">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" id="editContactId" name="id">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-zinc-300 mb-2">Contact Name</label>
                    <input type="text" id="editName" name="name" required class="w-full px-3 py-2 bg-zinc-800 border border-zinc-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-zinc-300 mb-2">Phone Number</label>
                    <input type="text" id="editPhone" name="phone" required class="w-full px-3 py-2 bg-zinc-800 border border-zinc-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-zinc-300 mb-2">Contact Type</label>
                    <select id="editType" name="type" required class="w-full px-3 py-2 bg-zinc-800 border border-zinc-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="emergency">Emergency Service</option>
                        <option value="medical">Medical Professional</option>
                        <option value="personal">Personal Contact</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-zinc-300 mb-2">Icon Class</label>
                    <input type="text" id="editIcon" name="icon" required class="w-full px-3 py-2 bg-zinc-800 border border-zinc-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-zinc-300 mb-2">Default Emergency</label>
                    <select id="editIsDefault" name="isDefault" required class="w-full px-3 py-2 bg-zinc-800 border border-zinc-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                    </select>
                </div>
                
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-zinc-100 rounded-lg transition-colors">
                        <i class="fas fa-save mr-2"></i>Save Changes
                    </button>
                    <button type="button" onclick="closeEditModal()" class="flex-1 px-4 py-2 bg-zinc-600 hover:bg-zinc-700 text-zinc-100 rounded-lg transition-colors">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add New Contact Form -->
    <div class="bg-zinc-900 rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-semibold mb-6 text-zinc-100">Add New Contact</h2>
        
        <form action="{{ route('admin.store-contact') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-zinc-300 mb-2">Contact Name</label>
                    <input type="text" name="name" required class="w-full px-3 py-2 bg-zinc-800 border border-zinc-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-zinc-300 mb-2">Phone Number</label>
                    <input type="text" name="phone" required placeholder="555-123-4567" class="w-full px-3 py-2 bg-zinc-800 border border-zinc-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-zinc-300 mb-2">Contact Type</label>
                    <select name="type" required class="w-full px-3 py-2 bg-zinc-800 border border-zinc-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="emergency">Emergency Service</option>
                        <option value="medical">Medical Professional</option>
                        <option value="personal">Personal Contact</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-zinc-300 mb-2">Icon Class</label>
                    <input type="text" name="icon" required placeholder="fa-phone-volume" class="w-full px-3 py-2 bg-zinc-800 border border-zinc-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-zinc-500 mt-1">FontAwesome icon class</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-zinc-300 mb-2">Default Emergency</label>
                    <select name="isDefault" required class="w-full px-3 py-2 bg-zinc-800 border border-zinc-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                    </select>
                    <p class="text-xs text-zinc-500 mt-1">Cannot delete default contacts</p>
                </div>
            </div>

            <button type="submit" class="w-full px-4 py-3 bg-blue-600 hover:bg-blue-700 text-zinc-100 rounded-lg transition-colors">
                <i class="fas fa-plus mr-2"></i>Add Contact
            </button>
        </form>
    </div>

    <!-- Existing Contacts -->
    @if(count($contacts) > 0)
        <div class="bg-zinc-900 rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-6 text-zinc-300">Existing Contacts</h2>
            <div class="overflow-x-auto">
                <table class="w-full table-auto">
                    <thead class="bg-zinc-800">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">Contact</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">Phone</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">Type</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">Default</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-zinc-800 divide-y divide-zinc-700">
                        @foreach($contacts as $contact)
                            <tr>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <i class="fas {{ $contact['icon'] }} mr-3 text-blue-600"></i>
                                        <span class="font-medium text-zinc-100">{{ $contact['name'] }}</span>
                                        @if($contact['isDefault'])
                                            <span class="ml-2 px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Default</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <a href="tel:{{ $contact['phone'] }}" class="text-blue-400 hover:text-blue-300 font-medium">
                                        {{ $contact['phone'] }}
                                    </a>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs rounded-full 
                                            @if($contact['type'] === 'emergency') bg-red-600/20 text-red-400
                                            @elseif($contact['type'] === 'medical') bg-blue-600/20 text-blue-400
                                            @else bg-green-600/20 text-green-400 @endif">
                                            {{ ucfirst($contact['type']) }}
                                        </span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    @if($contact['isDefault'])
                                        <span class="text-red-400 font-medium">Yes</span>
                                    @else
                                        <span class="text-zinc-600">No</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                                    <button onclick="editContact('{{ $contact['id'] }}')" class="text-blue-400 hover:text-blue-300 mr-3">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    @if(!$contact['isDefault'])
                                        <form method="POST" action="{{ route('admin.delete-contact', $contact['id']) }}" onsubmit="console.log('Submitting delete form for contact: {{ $contact['id'] }}'); return confirm('Are you sure you want to delete this contact?')" class="inline">
                                            @csrf
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="submit" class="text-red-400 hover:text-red-300">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-zinc-500 text-xs">Cannot delete default contacts</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>

<script>
// Store contacts data for editing
const contacts = @json($contacts);

function editContact(id) {
    const contact = contacts.find(c => c.id === id);
    if (contact) {
        document.getElementById('editContactId').value = contact.id;
        document.getElementById('editName').value = contact.name;
        document.getElementById('editPhone').value = contact.phone;
        document.getElementById('editType').value = contact.type;
        document.getElementById('editIcon').value = contact.icon;
        document.getElementById('editIsDefault').value = contact.isDefault ? '1' : '0';
        document.getElementById('editForm').action = '/admin/contacts/' + id;
        document.getElementById('editModal').style.display = 'flex';
    }
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}

function testDelete(id) {
    console.log('Test delete called for ID:', id);
    
    // Create a form to submit DELETE request
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/admin/contacts/' + id;
    
    const methodInput = document.createElement('input');
    methodInput.type = 'hidden';
    methodInput.name = '_method';
    methodInput.value = 'DELETE';
    
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    form.appendChild(methodInput);
    form.appendChild(csrfInput);
    document.body.appendChild(form);
    
    // Submit form with a small delay to ensure it's processed
    setTimeout(() => {
        form.submit();
    }, 100);
}
</script>
@endsection
