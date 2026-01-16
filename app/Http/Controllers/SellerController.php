<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerController extends Controller
{
    public function dashboard()
    {
        $products = Auth::user()->products;
        // Orders containing seller's products (simplified: showing all orders for demo, or filter by items)
        // For a simple multi-vendor setup, we'd need to filter order items.
        // Let's just show products for now.
        return view('seller.dashboard', compact('products'));
    }

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
            'image' => 'nullable|image',
        ]);

        $path = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
        }

        Auth::user()->products()->create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'image' => $path,
        ]);

        return redirect()->route('seller.dashboard')->with('success', 'Product created!');
    }
}
