@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-zinc-100">Kit Inventory Management</h1>
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
        @foreach($categories as $categoryKey => $category)
            <div class="bg-zinc-900 rounded-lg shadow-md p-6">
                <div class="flex items-center mb-4">
                    <i class="fas {{ $category['icon'] }} text-2xl text-blue-600 mr-3"></i>
                    <h2 class="text-xl font-semibold text-zinc-100">{{ $category['label'] }}</h2>
                </div>

                <!-- Add New Item Form -->
                <form action="{{ route('admin.store-kit-item') }}" method="POST" class="mb-6">
                    @csrf
                    <input type="hidden" name="category" value="{{ $categoryKey }}">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-zinc-300 mb-2">Item Name</label>
                            <input type="text" name="item_name" required class="w-full px-3 py-2 bg-zinc-800 border border-zinc-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-zinc-300 mb-2">Quantity</label>
                            <input type="number" name="quantity" required min="0" class="w-full px-3 py-2 bg-zinc-800 border border-zinc-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                    
                    <button type="submit" class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-zinc-100 rounded-lg transition-colors">
                        <i class="fas fa-plus mr-2"></i>Add Item
                    </button>
                </form>

                <!-- Existing Items -->
                @if(isset($category['items']) && count($category['items']) > 0)
                    <div class="border-t pt-4">
                        <h3 class="text-lg font-medium mb-3 text-gray-700">Existing Items</h3>
                        <div class="space-y-2">
                            @foreach($category['items'] as $item)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div>
                                        <span class="font-medium text-gray-800">{{ $item['item_name'] }}</span>
                                        <span class="ml-3 px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                            Qty: {{ $item['quantity'] }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <button class="text-blue-600 hover:text-blue-900">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="text-red-600 hover:text-red-900">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
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
