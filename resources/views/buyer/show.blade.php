<x-app-layout>
    <div class="py-12 bg-white">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                
                <!-- Left: Gallery (Takes 2 columns) -->
                <div class="md:col-span-2 space-y-4">
                    <div class="w-full bg-gray-50 h-[500px] overflow-hidden rounded-lg border border-gray-100 p-8 flex items-center justify-center">
                        @if($product->images->count() > 0)
                            <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" alt="{{ $product->name }}" class="object-contain max-h-full max-w-full main-image hover:scale-105 transition duration-500">
                        @else
                            <img src="https://placehold.co/800x600/f5f5f5/111?text=No+Image" alt="No Image" class="object-contain max-h-full max-w-full text-gray-400">
                        @endif
                    </div>
                    <div class="flex gap-4 overflow-x-auto pb-2">
                        @foreach($product->images as $image)
                            <button onclick="changeImage('{{ asset('storage/' . $image->image_path) }}')" class="flex-shrink-0 w-24 h-24 bg-gray-50 hover:bg-gray-100 transition">
                                <img src="{{ asset('storage/' . $image->image_path) }}" class="w-full h-full object-contain p-2">
                            </button>
                        @endforeach
                    </div>
                    
                    <div class="mt-12 border-t border-gray-200 pt-8">
                        <h3 class="font-bold text-xl mb-4">Description</h3>
                        <p class="text-gray-600 leading-relaxed">{{ $product->description }}</p>
                    </div>
                </div>

                <!-- Right: Buy Box (Takes 1 column) -->
                <div class="md:col-span-1">
                    <div class="sticky top-6">
                        <h1 class="text-3xl font-extrabold text-black mb-1 uppercase">{{ $product->name }}</h1>
                        <p class="text-sm text-gray-500 mb-6">Gitar Elektrik, Seri Profesional</p>
                        
                        <div class="flex items-start gap-1 mb-6">
                            <span class="text-sm font-bold align-top mt-1">Rp</span>
                            <span class="text-5xl font-bold tracking-tight">{{ number_format($product->price, 0, ',', '.') }}</span>
                        </div>

                        <div class="mb-8">
                            <p class="flex items-center gap-2 text-sm font-bold text-green-700">
                                <span class="w-2 h-2 bg-green-700 rounded-full"></span>
                                Tersedia untuk pengiriman
                            </p>
                            <p class="text-sm text-gray-500 ml-4">Stok tersedia di <a href="{{ route('buyer.index') }}" class="text-gray-500 hover:text-ikea-black transition">FRET & FLOW</a> Warehouse</p>
                        </div>

                        <form action="{{ route('buyer.cart.add', $product) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full bg-ikea-blue hover:bg-blue-800 text-white font-bold py-4 rounded-full transition duration-200 flex items-center justify-center gap-2 mb-4">
                                Tambah ke Keranjang
                            </button>
                        </form>
                        
                         <div class="border border-gray-300 p-4 rounded text-sm text-gray-600">
                            <div class="flex gap-2 items-center mb-2">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span>Kebijakan pengembalian 365 hari</span>
                            </div>
                             <div class="flex gap-2 items-center">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                <span>Pembayaran aman</span>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        function changeImage(src) {
            document.querySelector('.main-image').src = src;
        }
    </script>
</x-app-layout>
