<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Orders') }}
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
                                    <span class="font-bold text-gray-700">Order #{{ $order->id }}</span>
                                    <span class="text-sm text-gray-500 ml-2">{{ $order->created_at->format('d M Y') }}</span>
                                </div>
                                <div>
                                    <span class="px-3 py-1 rounded-full text-xs font-bold 
                                        {{ $order->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $order->status == 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $order->status == 'processed' ? 'bg-blue-100 text-blue-800' : '' }}
                                    ">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </div>
                            </div>
                            <div class="p-6">
                                <ul class="mb-4">
                                    @foreach($order->items as $item)
                                        <li class="flex justify-between py-2 border-b last:border-b-0">
                                            <span class="text-gray-700">{{ $item->product->name }} (x{{ $item->quantity }})</span>
                                            <span class="font-medium">${{ number_format($item->price, 2) }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="text-right">
                                    <span class="text-lg font-bold">Total: ${{ number_format($order->total_price, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    @if($orders->isEmpty())
                        <div class="text-center py-10 text-gray-500">
                            You have no orders yet.
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
