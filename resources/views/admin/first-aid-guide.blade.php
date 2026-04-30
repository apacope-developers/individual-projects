@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-zinc-100">First Aid Guide Management</h1>
        <a href="{{ route('admin') }}" class="px-4 py-2 bg-zinc-600 hover:bg-zinc-700 text-zinc-100 rounded-lg transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Back to Admin
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-red-600/10 border border-red-600/20 text-red-400 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <!-- Add New Condition Form -->
    <div class="bg-zinc-900 rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-semibold mb-6 text-zinc-100">Add New Condition</h2>
        
        <form action="{{ route('admin.store-first-aid-condition') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-zinc-300 mb-2">Condition ID</label>
                    <input type="text" name="id" required class="w-full px-3 py-2 bg-zinc-800 border border-zinc-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-zinc-500 mt-1">Unique identifier (e.g., cardiac-arrest)</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-zinc-300 mb-2">Condition Name</label>
                    <input type="text" name="name" required class="w-full px-3 py-2 bg-zinc-800 border border-zinc-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-zinc-300 mb-2">Icon Class</label>
                    <input type="text" name="icon" required placeholder="fa-heart" class="w-full px-3 py-2 bg-zinc-800 border border-zinc-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-zinc-500 mt-1">FontAwesome icon class</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-zinc-300 mb-2">Severity</label>
                    <select name="severity" required class="w-full px-3 py-2 bg-zinc-800 border border-zinc-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <select name="severity" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="critical">Critical</option>
                        <option value="urgent">Urgent</option>
                        <option value="moderate">Moderate</option>
                        <option value="minor">Minor</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                    <select name="category" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="cardiac">Cardiac</option>
                        <option value="breathing">Breathing</option>
                        <option value="wounds">Wounds</option>
                        <option value="neurological">Neurological</option>
                        <option value="musculoskeletal">Musculoskeletal</option>
                        <option value="allergic">Allergic</option>
                        <option value="environmental">Environmental</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Call 912</label>
                    <select name="call_912" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Summary</label>
                <textarea name="summary" required rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Steps (one per line)</label>
                    <textarea name="steps[]" required rows="6" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="1. Call 912&#10;2. Begin CPR..."></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Do's (one per line)</label>
                    <textarea name="dos[]" required rows="6" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Keep calm&#10;Apply pressure..."></textarea>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Don'ts (one per line)</label>
                <textarea name="donts[]" required rows="6" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Don't give food&#10;Don't move patient..."></textarea>
            </div>

            <button type="submit" class="w-full px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                <i class="fas fa-plus mr-2"></i>Add Condition
            </button>
        </form>
    </div>

    <!-- Existing Conditions -->
    @if(count($conditions) > 0)
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-6 text-gray-800">Existing Conditions</h2>
            <div class="overflow-x-auto">
                <table class="w-full table-auto">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Severity</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Call 912</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($conditions as $condition)
                            <tr>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <i class="fas {{ $condition['icon'] }} mr-2 text-blue-600"></i>
                                        {{ $condition['name'] }}
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                        {{ ucfirst($condition['category']) }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded-full 
                                        @if($condition['severity'] === 'critical') bg-red-100 text-red-800
                                        @elseif($condition['severity'] === 'urgent') bg-orange-100 text-orange-800
                                        @elseif($condition['severity'] === 'moderate') bg-yellow-100 text-yellow-800
                                        @else bg-green-100 text-green-800 @endif">
                                        {{ ucfirst($condition['severity']) }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    @if($condition['call_912'])
                                        <span class="text-red-600 font-medium">Yes</span>
                                    @else
                                        <span class="text-gray-600">No</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                                    <button class="text-blue-600 hover:text-blue-900 mr-3">Edit</button>
                                    <button class="text-red-600 hover:text-red-900">Delete</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection
