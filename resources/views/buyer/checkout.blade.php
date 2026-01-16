<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-ikea-black leading-tight">
            {{ __('Checkout') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-white">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
             <form action="{{ route('buyer.checkout.process') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                    
                    <!-- Left: Form -->
                    <div class="md:col-span-2 space-y-8">
                        <div>
                            <h3 class="text-2xl font-bold text-ikea-black mb-6">1. Informasi Pengiriman</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <x-input-label for="address" :value="__('Alamat Lengkap')" />
                                    <textarea id="address" name="address" rows="3" class="block mt-1 w-full border-gray-300 focus:border-ikea-blue focus:ring-ikea-blue rounded-none shadow-sm" required></textarea>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <x-input-label for="city" :value="__('Kota')" />
                                        <x-text-input id="city" class="block mt-1 w-full border-gray-300 focus:border-ikea-blue focus:ring-ikea-blue rounded-none" type="text" name="city" required />
                                    </div>
                                    <div>
                                        <x-input-label for="postal_code" :value="__('Kode Pos')" />
                                        <x-text-input id="postal_code" class="block mt-1 w-full border-gray-300 focus:border-ikea-blue focus:ring-ikea-blue rounded-none" type="text" name="postal_code" required />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-2xl font-bold text-ikea-black mb-6">2. Pembayaran</h3>
                            <div class="space-y-3">
                                <label class="flex items-center space-x-3 p-4 border border-gray-300 cursor-pointer hover:border-ikea-blue">
                                    <input type="radio" name="payment_method" value="transfer" class="text-ikea-blue focus:ring-ikea-blue" checked>
                                    <span class="font-bold text-gray-800">Transfer Bank</span>
                                </label>
                                <label class="flex items-center space-x-3 p-4 border border-gray-300 cursor-pointer hover:border-ikea-blue">
                                    <input type="radio" name="payment_method" value="cod" class="text-ikea-blue focus:ring-ikea-blue">
                                    <span class="font-bold text-gray-800">Bayar di Tempat (COD)</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Summary -->
                    <div class="md:col-span-1">
                        <div class="bg-gray-50 p-6 sticky top-6">
                            <h3 class="font-bold text-lg mb-4">Ringkasan Pesanan</h3>
                            <div class="space-y-2 mb-6 text-sm">
                                @php $total = 0; @endphp
                                @foreach($cart as $id => $details)
                                    @php $total += $details['price'] * $details['quantity']; @endphp
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">{{ $details['name'] }} x {{ $details['quantity'] }}</span>
                                        <span class="font-bold">Rp {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}</span>
                                    </div>
                                @endforeach
                            </div>
                            
                            <div class="border-t border-gray-200 pt-4 flex justify-between items-center mb-8">
                                <span class="font-bold text-lg">Total</span>
                                <span class="font-extrabold text-2xl text-ikea-black">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>

                            <button type="submit" class="w-full bg-ikea-blue hover:bg-blue-800 text-white font-bold py-4 rounded-full transition shadow-sm hover:shadow-lg">
                                Buat Pesanan
                            </button>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
</x-app-layout>
