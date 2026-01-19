<x-app-layout>
    <div class="py-12 bg-white">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <!-- Success Message -->
            <div class="mb-8 text-center">
                 <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-100 mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <h1 class="text-3xl font-bold text-ikea-black">Terima kasih atas pesanan Anda!</h1>
                <p class="text-gray-500 mt-2">Email konfirmasi telah dikirimkan ke kotak masuk Anda.</p>
            </div>

            <div class="bg-white border-2 border-dashed border-gray-300 p-8 print:border-none">
                <!-- Receipt Header -->
                <div class="flex justify-between items-start mb-8 border-b border-gray-200 pb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-ikea-blue tracking-tighter uppercase">FRET & FLOW</h2>
                        <p class="text-sm text-gray-500">Premium Guitar Marketplace</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-gray-900">STRUK PEMBELIAN</p>
                        <p class="text-sm text-gray-500">#{{ str_pad($order->id, 8, '0', STR_PAD_LEFT) }}</p>
                        <p class="text-sm text-gray-500">{{ $order->created_at->format('d M Y') }}</p>
                        <div class="mt-2">
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
                </div>

                <!-- Receipt Body -->
                <div class="mb-8">
                    <h3 class="text-xs font-bold uppercase text-gray-400 mb-4">Dikirim Ke</h3>
                     <p class="font-bold text-gray-900">{{ $order->user->name }}</p>
                    <p class="text-gray-600">{{ $order->shipping_address }}</p>
                    <p class="text-gray-600">{{ $order->shipping_city }}, {{ $order->postal_code }}</p>
                </div>

                <!-- Payment Proof Section -->
                @if($order->payment_method == 'transfer' && $order->status == 'pending')
                    <div class="mb-8 p-6 bg-blue-50 border border-blue-200 rounded-lg print:hidden">
                        <h3 class="font-bold text-ikea-blue mb-2">Instruksi Pembayaran</h3>
                        <p class="text-sm text-gray-600 mb-4">Silakan transfer sesuai total tagihan ke rekening berikut:</p>
                        
                        <div class="bg-white p-4 rounded border border-blue-100 mb-6">
                            <div class="flex items-center gap-4 mb-2">
                                <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold text-xs">BCA</div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-bold">Bank Central Asia</p>
                                    <p class="text-lg font-mono font-bold text-gray-800 tracking-wider">1234 5678 90</p>
                                    <p class="text-xs text-gray-600">A/N: FRET & FLOW OFFICIAL</p>
                                </div>
                            </div>
                        </div>

                        <h3 class="font-bold text-ikea-blue mb-2">Upload Bukti Pembayaran</h3>
                        <p class="text-sm text-gray-600 mb-4">Setelah transfer, mohon upload foto/screenshot bukti pembayaran di sini untuk verifikasi.</p>
                        <form action="{{ route('buyer.orders.upload_proof', $order) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="payment_proof" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-ikea-blue file:text-white hover:file:bg-blue-700" required>
                            <button type="submit" class="mt-4 bg-ikea-black text-white px-6 py-2 rounded-full font-bold text-sm hover:bg-gray-800 transition">Kirim Bukti</button>
                        </form>
                    </div>
                @elseif($order->payment_proof)
                    <div class="mb-8 print:hidden">
                        <h3 class="font-bold text-gray-900 mb-2">Bukti Pembayaran</h3>
                        <img src="{{ asset('storage/' . $order->payment_proof) }}" alt="Bukti Pembayaran" class="h-48 object-contain border border-gray-200 rounded">
                        @if($order->status == 'waiting_verification')
                            <p class="text-sm text-blue-600 mt-2 font-bold">Sedang diverifikasi oleh penjual.</p>
                        @endif
                    </div>
                @endif
                
                <!-- Cancellation Section -->
                @if(in_array($order->status, ['pending', 'waiting_verification']))
                    <div class="mb-8 border-t border-gray-200 pt-6 print:hidden">
                         <details>
                            <summary class="cursor-pointer text-red-600 font-bold hover:underline">Ingin membatalkan pesanan?</summary>
                            <div class="mt-4 p-4 bg-red-50 border border-red-100 rounded">
                                <form action="{{ route('buyer.orders.cancel', $order) }}" method="POST">
                                    @csrf
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Alasan Pembatalan (Bukti Kuat/Alasan Jelas)</label>
                                    <textarea name="cancellation_reason" rows="3" class="w-full border-gray-300 rounded focus:border-red-500 focus:ring-red-500" placeholder="Contoh: Salah alamat, ingin mengubah pesanan..." required></textarea>
                                    <button type="submit" class="mt-3 bg-red-600 text-white px-4 py-2 rounded font-bold text-sm hover:bg-red-700" onclick="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini? Aksi ini tidak dapat dibatalkan.')">Batalkan Pesanan</button>
                                </form>
                            </div>
                        </details>
                    </div>
                @endif
                
                @if($order->status == 'canceled')
                     <div class="mb-8 p-4 bg-red-50 border border-red-200 rounded text-red-800">
                        <strong>Pesanan Dibatalkan:</strong> {{ $order->cancellation_reason }}
                    </div>
                @endif

                <!-- Items -->
                <div class="mb-8">
                    @foreach($order->items as $item)
                        <div class="flex justify-between py-3 border-b border-gray-100 last:border-0">
                            <div>
                                <span class="font-bold block text-gray-900">{{ $item->product->name }}</span>
                                <span class="text-sm text-gray-500">Jml: {{ $item->quantity }}</span>
                            </div>
                            <div class="font-bold text-gray-900">
                                Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Totals -->
                <div class="flex justify-between items-center pt-6 border-t border-gray-900">
                    <span class="font-bold text-xl text-ikea-black">Total Bayar</span>
                    <span class="font-extrabold text-2xl text-ikea-blue">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Print Actions -->
            <div class="mt-8 text-center print:hidden space-x-4">
                <a href="{{ route('buyer.index') }}" class="text-gray-600 hover:underline font-bold">
                    Lanjut Belanja
                </a>
                <button onclick="window.print()" class="bg-ikea-black hover:bg-gray-800 text-white font-bold py-3 px-8 rounded-full shadow-lg transition transform hover:scale-105 inline-flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Cetak Struk
                </button>
            </div>
        </div>
    </div>
</x-app-layout>
