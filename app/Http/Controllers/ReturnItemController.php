<?php

namespace App\Http\Controllers;

use App\Models\IssueItem;
use App\Models\ReturnItem;
use App\Models\StockTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ReturnItemController extends Controller
{
    public function index()
    {
        $issueItems = IssueItem::query()
            ->with(['issueVoucher.employee', 'product', 'returns'])
            ->latest()
            ->get()
            ->filter(fn (IssueItem $item) => $item->remainingQty() > 0);

        return view('returns.index', [
            'returns' => ReturnItem::query()->with(['issueItem.issueVoucher.employee', 'issueItem.product'])->latest()->get(),
            'openIssueItems' => $issueItems,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'issue_item_id' => ['required', 'exists:issue_items,id'],
            'return_date' => ['required', 'date'],
            'quantity' => ['required', 'numeric', 'gt:0'],
            'condition' => ['required', 'in:good,damaged'],
            'notes' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($data) {
            $issueItem = IssueItem::query()->with('returns')->findOrFail($data['issue_item_id']);
            $remaining = $issueItem->remainingQty();
            $qty = (float) $data['quantity'];

            if ($qty > $remaining) {
                abort(422, 'Return quantity exceeds pending issue quantity. Pending: ' . $remaining);
            }

            $returnItem = ReturnItem::query()->create($data);

            StockTransaction::query()->create([
                'product_id' => $issueItem->product_id,
                'user_id' => Auth::id(),
                'transaction_type' => 'RETURN',
                'quantity' => $qty,
                'transaction_date' => $data['return_date'],
                'reference_type' => 'return_item',
                'reference_id' => $returnItem->id,
                'notes' => $data['notes'] ?? null,
            ]);
        });

        return redirect()->route('returns.index')->with('status', 'Return entry saved.');
    }
}
