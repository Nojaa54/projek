<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Seller Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold">My Products</h3>
                        <a href="{{ route('seller.products.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Add New Product
                        </a>
                    </div>
                    
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($products as $product)
                            <div class="border rounded-lg p-4 shadow hover:shadow-lg transition">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover mb-4 rounded">
                                @else
                                    <div class="w-full h-48 bg-gray-200 mb-4 rounded flex items-center justify-center text-gray-500">No Image</div>
                                @endif
                                <h4 class="font-bold text-lg">{{ $product->name }}</h4>
                                <p class="text-gray-600 truncate">{{ $product->description }}</p>
                                <div class="mt-4 flex justify-between items-center">
                                    <span class="text-xl font-bold text-green-600">${{ number_format($product->price, 2) }}</span>
                                    <span class="text-sm text-gray-500">Stock: {{ $product->stock }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    @if($products->isEmpty())
                        <p class="text-gray-500 text-center py-10">You usually haven't added any products yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
