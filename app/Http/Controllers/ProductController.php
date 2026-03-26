<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockTransaction;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::query()->with('category')->latest()->get();

        $stockByProduct = StockTransaction::query()
            ->select('product_id')
            ->selectRaw("COALESCE(SUM(CASE WHEN transaction_type IN ('IN', 'RETURN', 'ADJUSTMENT') THEN quantity ELSE -quantity END), 0) as balance")
            ->groupBy('product_id')
            ->pluck('balance', 'product_id');

        return view('products.index', [
            'products' => $products,
            'categories' => Category::query()->where('is_active', true)->orderBy('name')->get(),
            'stockByProduct' => $stockByProduct,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => ['nullable', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['nullable', 'string', 'max:255', 'unique:products,sku'],
            'unit' => ['required', 'string', 'max:40'],
            'min_stock' => ['required', 'numeric', 'min:0'],
            'track_serial' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'notes' => ['nullable', 'string'],
        ]);

        $data['track_serial'] = (bool) ($data['track_serial'] ?? false);
        $data['is_active'] = (bool) ($data['is_active'] ?? false);

        Product::query()->create($data);

        return redirect()->route('products.index')->with('status', 'Product created.');
    }

    public function edit(Product $product)
    {
        return view('products.edit', [
            'product' => $product,
            'categories' => Category::query()->where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'category_id' => ['nullable', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['nullable', 'string', 'max:255', 'unique:products,sku,' . $product->id],
            'unit' => ['required', 'string', 'max:40'],
            'min_stock' => ['required', 'numeric', 'min:0'],
            'track_serial' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'notes' => ['nullable', 'string'],
        ]);

        $data['track_serial'] = (bool) ($data['track_serial'] ?? false);
        $data['is_active'] = (bool) ($data['is_active'] ?? false);

        $product->update($data);

        return redirect()->route('products.index')->with('status', 'Product updated.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('status', 'Product deleted.');
    }
}
