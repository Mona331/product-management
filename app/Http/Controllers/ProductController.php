<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // عرض جميع المنتجات
    public function index()
    {
        $products = Product::all();

        return view('products.index', compact('products'));
    }

    // عرض صفحة إضافة منتج
    public function create()
    {
        return view('products.create');
    }

    // حفظ المنتج
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
        ]);

        Product::create($data);

        return redirect()->route('products.index')
            ->with('success', 'Product added successfully!');
    }

    // عرض صفحة تعديل المنتج
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    // تحديث بيانات المنتج
    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
        ]);

        $product->update($data);

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully!');
    }

    // حذف المنتج
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully!');
    }
}
