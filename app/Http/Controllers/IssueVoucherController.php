<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\IssueVoucher;
use App\Models\Product;
use App\Models\StockTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class IssueVoucherController extends Controller
{
    public function index()
    {
        return view('issues.index', [
            'issues' => IssueVoucher::query()->with(['employee', 'items.product'])->latest()->get(),
            'employees' => Employee::query()->where('status', 'active')->orderBy('name')->get(),
            'products' => Product::query()->where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'issue_date' => ['required', 'date'],
            'purpose' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'product_id' => ['required', 'array', 'min:1'],
            'product_id.*' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'array', 'min:1'],
            'quantity.*' => ['required', 'numeric', 'gt:0'],
        ]);

        DB::transaction(function () use ($data, $request) {
            foreach ($data['product_id'] as $index => $productId) {
                $requiredQty = (float) $data['quantity'][$index];
                $balance = Product::currentStockById((int) $productId);

                if ($balance < $requiredQty) {
                    $product = Product::query()->findOrFail($productId);
                    abort(422, 'Insufficient stock for ' . $product->name . '. Available: ' . $balance);
                }
            }

            $voucher = IssueVoucher::query()->create([
                'employee_id' => $data['employee_id'],
                'issued_by' => Auth::id(),
                'issue_date' => $data['issue_date'],
                'purpose' => $data['purpose'],
                'notes' => $data['notes'] ?? null,
            ]);

            foreach ($data['product_id'] as $index => $productId) {
                $qty = (float) $data['quantity'][$index];

                $item = $voucher->items()->create([
                    'product_id' => $productId,
                    'quantity' => $qty,
                ]);

                StockTransaction::query()->create([
                    'product_id' => $productId,
                    'user_id' => Auth::id(),
                    'transaction_type' => 'OUT',
                    'quantity' => $qty,
                    'transaction_date' => $voucher->issue_date,
                    'reference_type' => 'issue_item',
                    'reference_id' => $item->id,
                    'notes' => $request->input('purpose'),
                ]);
            }
        });

        return redirect()->route('issues.index')->with('status', 'Items issued successfully.');
    }

    public function destroy(IssueVoucher $issue)
    {
        return redirect()->route('issues.index')->with('status', 'Issue records are immutable. Use return entry for corrections.');
    }
}
