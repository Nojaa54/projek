<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Shopping Cart') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if(count(session('cart', [])) > 0)
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr>
                                    <th class="py-4 border-b">Product</th>
                                    <th class="py-4 border-b">Price</th>
                                    <th class="py-4 border-b">Quantity</th>
                                    <th class="py-4 border-b">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $total = 0; @endphp
                                @foreach(session('cart') as $id => $details)
                                    @php $subtotal = $details['price'] * $details['quantity']; $total += $subtotal; @endphp
                                    <tr class="hover:bg-gray-50">
                                        <td class="py-4 border-b">
                                            <div class="flex items-center">
                                                @if($details['image'])
                                                    <img src="{{ asset('storage/' . $details['image']) }}" class="w-12 h-12 object-cover rounded mr-4">
                                                @endif
                                                <span class="font-bold">{{ $details['name'] }}</span>
                                            </div>
                                        </td>
                                        <td class="py-4 border-b">${{ number_format($details['price'], 2) }}</td>
                                        <td class="py-4 border-b">{{ $details['quantity'] }}</td>
                                        <td class="py-4 border-b font-bold">${{ number_format($subtotal, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="py-6 text-right font-bold text-xl uppercase pr-4">Total:</td>
                                    <td class="py-6 font-bold text-xl text-indigo-600">${{ number_format($total, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>

                        <div class="mt-8 flex justify-end">
                            <form action="{{ route('buyer.checkout') }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-lg shadow-md transition transform hover:scale-105">
                                    Proceed to Checkout
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <p class="text-gray-500 mb-4">Your cart is empty.</p>
                            <a href="{{ route('buyer.index') }}" class="text-indigo-600 hover:underline">Continue Shopping</a>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
