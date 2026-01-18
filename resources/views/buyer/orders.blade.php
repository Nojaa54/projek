<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pesanan Saya') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6">
                            {{ session('success') }}
                        </div>
                    @endif

                    @foreach($orders as $order)
                        <div class="border rounded-lg mb-6 overflow-hidden">
                            <div class="bg-gray-50 px-6 py-4 border-b flex justify-between items-center">
                                <div>
                                    <span class="font-bold text-gray-700">Order #{{ str_pad($order->id, 8, '0', STR_PAD_LEFT) }}</span>
                                    <span class="text-sm text-gray-500 ml-2">{{ $order->created_at->format('d M Y') }}</span>
                                </div>
                                <div>
                                     @php
                                        $statusClasses = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'waiting_verification' => 'bg-blue-100 text-blue-800',
                                            'paid' => 'bg-green-100 text-green-800',
                                            'shipped' => 'bg-purple-100 text-purple-800',
                                            'canceled' => 'bg-red-100 text-red-800',
                                            'declined' => 'bg-red-100 text-red-800',
                                        ];
                                        $statusLabels = [
                                            'pending' => 'Menunggu Pembayaran',
                                            'waiting_verification' => 'Menunggu Verifikasi',
                                            'paid' => 'Dibayar',
                                            'shipped' => 'Dikirim',
                                            'canceled' => 'Dibatalkan',
                                            'declined' => 'Ditolak',
                                        ];
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase {{ $statusClasses[$order->status] ?? 'bg-gray-100' }}">
                                        {{ $statusLabels[$order->status] ?? $order->status }}
                                    </span>
                                </div>
                            </div>
                            <div class="p-6">
                                <ul class="mb-4">
                                    @foreach($order->items as $item)
                                        <li class="flex justify-between py-2 border-b last:border-b-0">
                                            <span class="text-gray-700">{{ $item->product->name }} (x{{ $item->quantity }})</span>
                                            <span class="font-medium">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="flex justify-between items-center border-t pt-4">
                                    <a href="{{ route('buyer.orders.show', $order) }}" class="text-ikea-blue font-bold hover:underline text-sm">Lihat Detail / Upload Bukti</a>
                                    <span class="text-lg font-bold">Total: Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    @if($orders->isEmpty())
                        <div class="text-center py-10 text-gray-500">
                            Anda belum memiliki pesanan. <a href="{{ route('buyer.index') }}" class="text-ikea-blue font-bold">Mulai Belanja</a>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
