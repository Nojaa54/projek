<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BuyerController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('buyer.index', compact('products'));
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
        return redirect()->back()->with('success', 'Product added to cart!');
    }

    public function cart()
    {
        return view('buyer.cart');
    }

    public function checkout()
    {
        $cart = session()->get('cart');
        if(!$cart) return redirect()->route('buyer.index');

        $total = 0;
        foreach($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        // Create Order
        $order = Order::create([
            'user_id' => Auth::id(),
            'total_price' => $total,
            'status' => 'pending'
        ]);

        foreach($cart as $id => $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $id,
                'quantity' => $item['quantity'],
                'price' => $item['price']
            ]);
        }

        session()->forget('cart');
        return redirect()->route('buyer.orders')->with('success', 'Order placed!');
    }

    public function orders()
    {
        $orders = Auth::user()->orders()->with('items.product')->latest()->get();
        return view('buyer.orders', compact('orders'));
    }
}
