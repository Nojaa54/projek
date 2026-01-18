<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerController extends Controller
{


    public function createProduct()
    {
        return view('seller.products.create');
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $product = Auth::user()->products()->create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $product->images()->create(['image_path' => $path]);
            }
        }

        return redirect()->route('seller.dashboard')->with('success', 'Product created!');
    }

    public function dashboard()
    {
        $products = Product::where('user_id', Auth::id())->with('images')->latest()->get();
        // Assume all orders are visible to seller for now (single seller platform)
        // or filter by items belonging to seller?
        // Since the prompt implies a single seller/admin role for "WarungKita/FRET&FLOW", I will fetch all orders.
        $orders = \App\Models\Order::with('user', 'items.product')->latest()->get();
        
        return view('seller.dashboard', compact('products', 'orders'));
    }

    public function verifyOrder(Request $request, \App\Models\Order $order)
    {
        $request->validate([
            'action' => 'required|in:accept,reject',
        ]);

        if ($request->action == 'accept') {
            $order->update(['status' => 'paid']); // Or processing/shipped
            return redirect()->back()->with('success', 'Pesanan diterima. Silakan proses pengiriman.');
        } else {
            $order->update(['status' => 'declined']);
            return redirect()->back()->with('success', 'Pesanan ditolak.');
        }
    }

    public function editProduct(Product $product)
    {
        if ($product->user_id !== Auth::id()) {
            abort(403);
        }
        return view('seller.products.edit', compact('product'));
    }

    public function updateProduct(Request $request, Product $product)
    {
        if ($product->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $product->images()->create(['image_path' => $path]);
            }
        }

        return redirect()->route('seller.dashboard')->with('success', 'Product updated!');
    }

    public function destroyProduct(Product $product)
    {
        if ($product->user_id !== Auth::id()) {
            abort(403);
        }
        $product->delete();
        return redirect()->route('seller.dashboard')->with('success', 'Product deleted!');
    }
}
