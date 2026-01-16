<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BuyerController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('images')->latest();

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        $products = $query->get();
        return view('buyer.index', compact('products'));
    }

    public function show(Product $product)
    {
        $product->load('images');
        return view('buyer.show', compact('product'));
    }

    public function addToCart(Request $request, Product $product)
    {
        // Simple Session-based Cart
        $cart = session()->get('cart', []);
        
        if(isset($cart[$product->id])) {
            $cart[$product->id]['quantity']++;
        } else {
            $cart[$product->id] = [
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->price,
                "image" => $product->image
            ];
        }
        
        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function removeFromCart($id)
    {
        $cart = session()->get('cart');
        if(isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        return redirect()->back()->with('success', 'Produk dihapus dari keranjang!');
    }

    public function cart()
    {
        return view('buyer.cart');
    }

    public function showCheckout()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('buyer.index')->with('error', 'Keranjang belanja kosong.');
        }
        return view('buyer.checkout', compact('cart'));
    }

    public function processCheckout(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('buyer.index');
        }

        $request->validate([
            'address' => 'required|string',
            'city' => 'required|string',
            'postal_code' => 'required|string',
            'payment_method' => 'required|string',
        ]);

        $total = 0;
        foreach ($cart as $id => $details) {
            $total += $details['price'] * $details['quantity'];
        }

        $order = Order::create([
            'user_id' => Auth::id(),
            'total_price' => $total,
            'status' => 'pending',
            'shipping_address' => $request->address,
            'shipping_city' => $request->city,
            'postal_code' => $request->postal_code,
            'payment_method' => $request->payment_method,
        ]);

        foreach ($cart as $id => $details) {
            \App\Models\OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $id,
                'quantity' => $details['quantity'],
                'price' => $details['price'],
            ]);
            
            // Decrease Stock
            $product = Product::find($id);
            if($product) {
                $product->decrement('stock', $details['quantity']);
            }
        }

        session()->forget('cart');

        return redirect()->route('buyer.orders.show', $order)->with('success', 'Order placed successfully!');
    }

    public function showOrder(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
        $order->load('items.product');
        return view('buyer.receipt', compact('order'));
    }

    public function orders()
    {
        $orders = Auth::user()->orders()->latest()->get();
        return view('buyer.orders', compact('orders'));
    }

    public function uploadProof(Request $request, Order $order)
    {
        if ($order->user_id !== Auth::id()) abort(403);
        
        $request->validate([
            'payment_proof' => 'required|image|max:2048',
        ]);

        $path = $request->file('payment_proof')->store('payment_proofs', 'public');
        
        $order->update([
            'payment_proof' => $path,
            'status' => 'waiting_verification',
        ]);

        return redirect()->back()->with('success', 'Bukti pembayaran berhasil diunggah! Menunggu verifikasi penjual.');
    }

    public function cancelOrder(Request $request, Order $order)
    {
        if ($order->user_id !== Auth::id()) abort(403);

        $request->validate([
            'cancellation_reason' => 'required|string|min:5',
        ]);

        // Only allow cancellation if pending or waiting verification
        if (!in_array($order->status, ['pending', 'waiting_verification'])) {
            return redirect()->back()->with('error', 'Pesanan tidak dapat dibatalkan pada tahap ini.');
        }

        $order->update([
            'status' => 'canceled',
            'cancellation_reason' => $request->cancellation_reason,
        ]);

        return redirect()->back()->with('success', 'Pesanan berhasil dibatalkan.');
    }
}
