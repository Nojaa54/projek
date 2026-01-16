<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Shopping Cart') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-4xl font-extrabold text-ikea-black mb-8">Keranjang Belanja</h1>
            
            <div class="bg-white">
                @if(count(session('cart', [])) > 0)
                    <div class="divide-y divide-gray-200 border-t border-b border-gray-200">
                        @php $total = 0; @endphp
                        @foreach(session('cart') as $id => $details)
                            @php $subtotal = $details['price'] * $details['quantity']; $total += $subtotal; @endphp
                            <div class="py-6 flex gap-6">
                                <div class="w-24 h-24 bg-gray-100 flex-shrink-0">
                                     @if($details['image'])
                                        <img src="{{ asset('storage/' . $details['image']) }}" class="w-full h-full object-cover mix-blend-multiply">
                                    @endif
                                </div>
                                <div class="flex-grow flex justify-between">
                                    <div>
                                        <h3 class="font-bold text-lg text-black">{{ $details['name'] }}</h3>
                                        <p class="text-sm text-gray-500 mb-2">Jumlah: {{ $details['quantity'] }}</p>
                                        <form action="{{ route('buyer.cart.remove', $id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs text-red-600 font-bold hover:underline">Hapus</button>
                                        </form>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold text-lg">Rp {{ number_format($subtotal, 0, ',', '.') }}</p>
                                        <p class="text-sm text-gray-500">Rp {{ number_format($details['price'], 0, ',', '.') }} / unit</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-8 flex flex-col items-end">
                        <div class="text-2xl font-extrabold mb-6">
                            Total <span class="ml-4">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <a href="{{ route('buyer.checkout.show') }}" class="bg-ikea-blue hover:bg-blue-800 text-white font-bold py-4 px-12 rounded-full shadow-sm hover:shadow-md transition w-full sm:w-auto text-center">
                            Lanjut ke Pembayaran
                        </a>
                    </div>
                @else
                    <div class="text-center py-20 bg-gray-50">
                        <h2 class="text-xl font-bold mb-4">Keranjang belanja Anda kosong</h2>
                        <a href="{{ route('buyer.index') }}" class="text-ikea-blue font-bold underline decoration-2 underline-offset-4 hover:text-black">Mulai belanja</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
