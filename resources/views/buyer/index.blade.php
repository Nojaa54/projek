<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Marketplace') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-white min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Search Bar -->
             <div class="mb-8">
                <form action="{{ route('buyer.index') }}" method="GET" class="flex gap-2">
                    <input type="text" name="search" placeholder="Cari gitar impianmu..." value="{{ request('search') }}" class="w-full border-2 border-gray-300 rounded-full px-6 py-3 focus:border-ikea-blue focus:ring-0">
                    <button type="submit" class="bg-ikea-blue text-white rounded-full px-8 py-3 font-bold hover:bg-blue-800 transition">Cari</button>
                </form>
            </div>

            <!-- Product Grid -->
            <h2 class="text-3xl font-bold mb-6 text-ikea-black">Koleksi Gitar</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-x-6 gap-y-10">
                @foreach ($products as $product)
                    <a href="{{ route('buyer.show', $product) }}" class="group block">
                        <div class="relative bg-ikea-gray aspect-w-1 aspect-h-1 overflow-hidden mb-4">
                            @if($product->images->count() > 0)
                                <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" alt="{{ $product->name }}" class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-300">
                            @else
                                <img src="https://placehold.co/600x600/f5f5f5/111?text={{ urlencode($product->name) }}" alt="{{ $product->name }}" class="object-cover w-full h-full">
                            @endif
                        </div>
                        
                        <div class="space-y-1">
                            <h3 class="font-bold text-ikea-black text-lg group-hover:underline decoration-2 underline-offset-4">{{ $product->name }}</h3>
                            <p class="text-gray-500 text-sm line-clamp-2 h-10">{{ $product->description }}</p>
                            <div class="pt-2 flex items-start gap-1">
                                <span class="text-xs font-bold align-top">Rp</span>
                                <span class="text-2xl font-bold leading-none">{{ number_format($product->price, 0, ',', '.') }}</span>
                            </div>
                             <div class="mt-2 text-sm text-green-700 font-semibold flex items-center gap-1">
                                <span class="w-2 h-2 rounded-full bg-green-600 inline-block"></span>
                                Tersedia
                            </div>
                        </div>
                        
                        <!-- Mobile Add Button -->
                        <div class="mt-4 md:hidden">
                            <button class="w-full bg-ikea-blue text-white py-2 rounded-full font-bold">Tambah ke Keranjang</button>
                        </div>
                    </a>
                @endforeach
            </div>

            @if($products->isEmpty())
                <div class="text-center py-12 text-gray-500">
                    Produk tidak ditemukan.
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
