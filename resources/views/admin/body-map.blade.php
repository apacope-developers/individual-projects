@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-zinc-100">Body Map Management</h1>
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

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @foreach($zones as $zoneKey => $zone)
            <div class="bg-zinc-900 rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4 text-zinc-100">{{ $zone['label'] }}</h2>
                
                <!-- Add New Condition Form -->
                <form action="{{ route('admin.store-body-map-condition') }}" method="POST" class="mb-6">
                    @csrf
                    <input type="hidden" name="zone" value="{{ $zoneKey }}">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-zinc-300 mb-2">Condition Name</label>
                            <input type="text" name="name" required class="w-full px-3 py-2 bg-zinc-800 border border-zinc-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-zinc-300 mb-2">Severity</label>
                            <select name="severity" required class="w-full px-3 py-2 bg-zinc-800 border border-zinc-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="critical">Critical</option>
                                <option value="urgent">Urgent</option>
                                <option value="moderate">Moderate</option>
                                <option value="minor">Minor</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-zinc-300 mb-2">Description</label>
                        <textarea name="description" required rows="3" class="w-full px-3 py-2 bg-zinc-800 border border-zinc-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-zinc-300 mb-2">First Aid Action</label>
                        <textarea name="action" required rows="2" class="w-full px-3 py-2 bg-zinc-800 border border-zinc-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                    
                    <button type="submit" class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-zinc-100 rounded-lg transition-colors">
                        <i class="fas fa-plus mr-2"></i>Add Condition
                    </button>
                </form>

                <!-- Existing Conditions -->
                @if(isset($zone['conditions']) && count($zone['conditions']) > 0)
                    <div class="border-t pt-4">
                        <h3 class="text-lg font-medium mb-3 text-zinc-300">Existing Conditions</h3>
                        <div class="space-y-2">
                            @foreach($zone['conditions'] as $condition)
                                <div class="flex items-center justify-between p-3 bg-zinc-800 rounded-lg">
                                    <div>
                                        <span class="font-medium text-zinc-100">{{ $condition['name'] }}</span>
                                        <span class="ml-2 px-2 py-1 text-xs rounded-full 
                                            @if($condition['severity'] === 'critical') bg-red-600/20 text-red-400
                                            @elseif($condition['severity'] === 'urgent') bg-orange-600/20 text-orange-400
                                            @elseif($condition['severity'] === 'moderate') bg-yellow-600/20 text-yellow-400
                                            @else bg-green-600/20 text-green-400 @endif">
                                            {{ ucfirst($condition['severity']) }}
                                        </span>
                                    </div>
                                    <button class="text-red-400 hover:text-red-300">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>
@endsection
