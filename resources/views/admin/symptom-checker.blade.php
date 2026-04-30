@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-zinc-100">Symptom Checker Management</h1>
        <a href="{{ route('admin') }}" class="px-4 py-2 bg-zinc-600 hover:bg-zinc-700 text-zinc-100 rounded-lg transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Back to Admin
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-red-600/10 border border-red-600/20 text-red-400 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @foreach($questions as $category => $question)
            <div class="bg-zinc-900 rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4 text-zinc-100">{{ $category }}</h2>
                
                <div class="mb-4 p-4 bg-zinc-800 rounded-lg">
                    <p class="text-sm font-medium text-zinc-300">Current Question:</p>
                    <p class="text-zinc-100">{{ $question['question'] }}</p>
                </div>

                <!-- Add New Option Form -->
                <form action="{{ route('admin.store-symptom-option') }}" method="POST" class="mb-6">
                    @csrf
                    <input type="hidden" name="category" value="{{ $category }}">
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-zinc-300 mb-2">Option Text</label>
                        <input type="text" name="option_text" required class="w-full px-3 py-2 bg-zinc-800 border border-zinc-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-zinc-300 mb-2">Result Severity</label>
                            <select name="result_severity" required class="w-full px-3 py-2 bg-zinc-800 border border-zinc-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="critical">Critical</option>
                                <option value="urgent">Urgent</option>
                                <option value="moderate">Moderate</option>
                                <option value="minor">Minor</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-zinc-300 mb-2">Result Condition</label>
                            <input type="text" name="result_condition" required class="w-full px-3 py-2 bg-zinc-800 border border-zinc-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-zinc-300 mb-2">Result Action</label>
                        <textarea name="result_action" required rows="3" class="w-full px-3 py-2 bg-zinc-800 border border-zinc-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                    
                    <button type="submit" class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-zinc-100 rounded-lg transition-colors">
                        <i class="fas fa-plus mr-2"></i>Add Option
                    </button>
                </form>

                <!-- Existing Options -->
                @if(isset($question['options']) && count($question['options']) > 0)
                    <div class="border-t pt-4">
                        <h3 class="text-lg font-medium mb-3 text-gray-700">Existing Options</h3>
                        <div class="space-y-2">
                            @foreach($question['options'] as $option)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div>
                                        <p class="font-medium text-gray-800">{{ $option['option_text'] }}</p>
                                        <p class="text-sm text-gray-600">→ {{ $option['result_condition'] }}</p>
                                        <span class="ml-2 px-2 py-1 text-xs rounded-full 
                                            @if($option['result_severity'] === 'critical') bg-red-100 text-red-800
                                            @elseif($option['result_severity'] === 'urgent') bg-orange-100 text-orange-800
                                            @elseif($option['result_severity'] === 'moderate') bg-yellow-100 text-yellow-800
                                            @else bg-green-100 text-green-800 @endif">
                                            {{ ucfirst($option['result_severity']) }}
                                        </span>
                                    </div>
                                    <button class="text-red-600 hover:text-red-800">
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
