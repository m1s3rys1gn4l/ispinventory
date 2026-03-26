<?php

namespace App\Http\Controllers;

use App\Models\IssueItem;
use App\Models\Product;
use App\Models\StockTransaction;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $products = Product::query()->get(['id', 'name', 'min_stock']);

        $stockByProduct = StockTransaction::query()
            ->select('product_id')
            ->selectRaw("COALESCE(SUM(CASE WHEN transaction_type IN ('IN', 'RETURN', 'ADJUSTMENT') THEN quantity ELSE -quantity END), 0) as balance")
            ->groupBy('product_id')
            ->pluck('balance', 'product_id');

        $lowStockCount = $products->filter(function (Product $product) use ($stockByProduct) {
            $balance = (float) ($stockByProduct[$product->id] ?? 0);
            return $balance < (float) $product->min_stock;
        })->count();

        $activeIssueCount = IssueItem::query()
            ->leftJoin('return_items', 'issue_items.id', '=', 'return_items.issue_item_id')
            ->select(
                'issue_items.id',
                'issue_items.quantity',
                DB::raw('COALESCE(SUM(return_items.quantity), 0) as returned_qty')
            )
            ->groupBy('issue_items.id', 'issue_items.quantity')
            ->havingRaw('issue_items.quantity > COALESCE(SUM(return_items.quantity), 0)')
            ->count();

        return view('dashboard', [
            'productCount' => $products->count(),
            'lowStockCount' => $lowStockCount,
            'activeIssueCount' => $activeIssueCount,
            'transactionCount' => StockTransaction::query()->count(),
        ]);
    }
}
