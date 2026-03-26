<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\StockTransaction;
use App\Models\Vendor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function index()
    {
        return view('purchases.index', [
            'purchases' => Purchase::query()->with(['vendor', 'items.product'])->latest()->get(),
            'vendors' => Vendor::query()->orderBy('name')->get(),
            'products' => Product::query()->where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'vendor_id' => ['nullable', 'exists:vendors,id'],
            'purchase_date' => ['required', 'date'],
            'reference_no' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'product_id' => ['required', 'array', 'min:1'],
            'product_id.*' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'array', 'min:1'],
            'quantity.*' => ['required', 'numeric', 'gt:0'],
            'unit_cost' => ['required', 'array', 'min:1'],
            'unit_cost.*' => ['required', 'numeric', 'min:0'],
        ]);

        DB::transaction(function () use ($data, $request) {
            $purchase = Purchase::query()->create([
                'vendor_id' => $data['vendor_id'] ?? null,
                'purchase_date' => $data['purchase_date'],
                'reference_no' => $data['reference_no'] ?? null,
                'notes' => $data['notes'] ?? null,
            ]);

            foreach ($data['product_id'] as $index => $productId) {
                $qty = (float) $data['quantity'][$index];
                $unitCost = (float) $data['unit_cost'][$index];

                $item = $purchase->items()->create([
                    'product_id' => $productId,
                    'quantity' => $qty,
                    'unit_cost' => $unitCost,
                    'line_total' => $qty * $unitCost,
                ]);

                StockTransaction::query()->create([
                    'product_id' => $productId,
                    'user_id' => Auth::id(),
                    'transaction_type' => 'IN',
                    'quantity' => $qty,
                    'transaction_date' => $purchase->purchase_date,
                    'reference_type' => 'purchase_item',
                    'reference_id' => $item->id,
                    'notes' => $request->input('reference_no') ?: 'Purchase entry',
                ]);
            }
        });

        return redirect()->route('purchases.index')->with('status', 'Purchase created and stock added.');
    }

    public function destroy(Purchase $purchase)
    {
        if ($purchase->items()->exists()) {
            return redirect()->route('purchases.index')->with('status', 'Purchase cannot be deleted after stock posting.');
        }

        $purchase->delete();
        return redirect()->route('purchases.index')->with('status', 'Purchase deleted.');
    }
}
