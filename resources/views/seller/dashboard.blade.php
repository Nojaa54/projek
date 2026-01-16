<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Seller Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-white">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-3xl font-bold text-ikea-black">Dashboard Penjual</h2>
                 <a href="{{ route('seller.products.create') }}" class="bg-ikea-blue text-white px-6 py-3 rounded-full font-bold hover:bg-blue-800 transition">
                    + Tambah Produk Baru
                </a>
            </div>

            <!-- Incoming Orders Section -->
            <div class="mb-12">
                <h2 class="text-3xl font-bold text-ikea-black mb-6">Pesanan Masuk</h2>
                <div class="bg-white border border-gray-200 shadow-sm overflow-hidden sm:rounded-lg">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4 font-bold text-gray-600">Order #</th>
                                <th class="px-6 py-4 font-bold text-gray-600">Pembeli</th>
                                <th class="px-6 py-4 font-bold text-gray-600">Total</th>
                                <th class="px-6 py-4 font-bold text-gray-600">Status</th>
                                <th class="px-6 py-4 font-bold text-gray-600">Bukti Bayar</th>
                                <th class="px-6 py-4 font-bold text-gray-600 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($orders as $order)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 font-bold text-gray-900">#{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold">{{ $order->user->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $order->created_at->format('d M Y H:i') }}</div>
                                    </td>
                                    <td class="px-6 py-4 font-bold display-currency">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4">
                                        @php
                                            $statusClasses = [
                                                'pending' => 'bg-yellow-100 text-yellow-800',
                                                'waiting_verification' => 'bg-blue-100 text-blue-800',
                                                'paid' => 'bg-green-100 text-green-800',
                                                'canceled' => 'bg-red-100 text-red-800',
                                                'declined' => 'bg-red-100 text-red-800',
                                            ];
                                            $statusLabels = [
                                                'pending' => 'Menunggu Pembayaran',
                                                'waiting_verification' => 'Verifikasi Bukti',
                                                'paid' => 'Lunas',
                                                'canceled' => 'Dibatalkan',
                                                'declined' => 'Ditolak',
                                            ];
                                        @endphp
                                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase {{ $statusClasses[$order->status] ?? 'bg-gray-100' }}">
                                            {{ $statusLabels[$order->status] ?? $order->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($order->payment_proof)
                                            <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank" class="text-blue-600 underline text-sm font-bold">Lihat Bukti</a>
                                        @elseif($order->payment_method == 'cod')
                                            <span class="text-xs text-gray-500 font-bold">COD</span>
                                        @else
                                            <span class="text-xs text-gray-400">Belum ada</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        @if(in_array($order->status, ['pending', 'waiting_verification']))
                                            <div class="flex justify-end gap-2">
                                                <form action="{{ route('seller.orders.verify', $order) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="action" value="accept">
                                                    <button type="submit" class="bg-green-600 text-white px-3 py-1 rounded text-xs font-bold hover:bg-green-700 transition" onclick="return confirm('Terima pesanan ini?')">Terima</button>
                                                </form>
                                                <form action="{{ route('seller.orders.verify', $order) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="action" value="reject">
                                                    <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded text-xs font-bold hover:bg-red-700 transition" onclick="return confirm('Tolak pesanan ini?')">Tolak</button>
                                                </form>
                                            </div>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                     @if($orders->isEmpty())
                        <div class="p-8 text-center text-gray-500">
                            Belum ada pesanan masuk.
                        </div>
                    @endif
                </div>
            </div>

            <div class="flex justify-between items-center mb-6 pt-8 border-t border-gray-200">

                <table class="w-full text-left">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 font-bold text-gray-600">Produk</th>
                            <th class="px-6 py-4 font-bold text-gray-600">Harga</th>
                            <th class="px-6 py-4 font-bold text-gray-600">Stok</th>
                            <th class="px-6 py-4 font-bold text-gray-600 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($products as $product)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                         @if($product->images->count() > 0)
                                            <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" class="w-12 h-12 object-cover rounded bg-gray-100">
                                        @else
                                            <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center text-xs text-gray-500">No Img</div>
                                        @endif
                                        <span class="font-bold text-ikea-black">{{ $product->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                <td class="px-6 py-4">
                                    <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-xs font-bold">{{ $product->stock }}</span>
                                </td>
                                <td class="px-6 py-4 text-right flex justify-end gap-2">
                                    <a href="{{ route('seller.products.edit', $product) }}" class="text-gray-600 hover:text-ikea-blue font-bold text-sm">Edit</a>
                                    <form action="{{ route('seller.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 font-bold text-sm ml-4">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                 @if($products->isEmpty())
                    <div class="p-12 text-center text-gray-500">
                        Belum ada produk. Mulai berjualan dengan menambahkan produk baru!
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
