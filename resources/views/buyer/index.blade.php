<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Marketplace') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                @foreach($products as $product)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-xl transition duration-300">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center text-gray-500">No Image</div>
                        @endif
                        
                        <div class="p-4">
                            <h3 class="font-bold text-lg mb-2 text-gray-800">{{ $product->name }}</h3>
                            <p class="text-sm text-gray-600 mb-4 h-12 overflow-hidden">{{ Str::limit($product->description, 50) }}</p>
                            
                            <div class="flex justify-between items-center mb-4">
                                <span class="text-xl font-bold text-indigo-600">${{ number_format($product->price, 2) }}</span>
                                <span class="text-xs text-gray-500">Stock: {{ $product->stock }}</span>
                            </div>

                            <form action="{{ route('buyer.cart.add', $product->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
                                    Add to Cart
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($products->isEmpty())
                <div class="text-center py-12 text-gray-500">
                    No products available at the moment.
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
