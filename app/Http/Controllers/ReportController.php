<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\IssueItem;
use App\Models\Product;
use App\Models\StockTransaction;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function stock()
    {
        $rows = Product::query()
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->leftJoin('stock_transactions', 'products.id', '=', 'stock_transactions.product_id')
            ->groupBy('products.id', 'products.name', 'products.unit', 'products.min_stock', 'categories.name')
            ->select(
                'products.id',
                'products.name',
                'products.unit',
                'products.min_stock',
                'categories.name as category_name'
            )
            ->selectRaw("COALESCE(SUM(CASE WHEN stock_transactions.transaction_type IN ('IN', 'RETURN', 'ADJUSTMENT') THEN stock_transactions.quantity ELSE -stock_transactions.quantity END), 0) as balance")
            ->orderBy('products.name')
            ->get();

        // Check if CSV export requested
        if (request('export') === 'csv') {
            return $this->exportStockToCsv($rows);
        }

        // Calculate metrics
        $totalProducts = Product::count();
        $lowStockCount = $rows->filter(fn($r) => $r->balance < $r->min_stock)->count();
        $totalValue = $rows->sum('balance');
        $categoriesCount = Category::count();

        return view('reports.stock', [
            'rows' => $rows,
            'metrics' => [
                'total_products' => $totalProducts,
                'low_stock' => $lowStockCount,
                'total_value' => $totalValue,
                'categories' => $categoriesCount,
            ]
        ]);
    }

    public function employeeIssues()
    {
        $rows = IssueItem::query()
            ->join('issue_vouchers', 'issue_items.issue_voucher_id', '=', 'issue_vouchers.id')
            ->join('employees', 'issue_vouchers.employee_id', '=', 'employees.id')
            ->join('products', 'issue_items.product_id', '=', 'products.id')
            ->leftJoin('return_items', 'issue_items.id', '=', 'return_items.issue_item_id')
            ->groupBy(
                'issue_items.id',
                'employees.name',
                'products.name',
                'products.unit',
                'issue_items.quantity',
                'issue_vouchers.issue_date',
                'issue_vouchers.purpose'
            )
            ->select(
                'issue_items.id',
                'employees.name as employee_name',
                'products.name as product_name',
                'products.unit as product_unit',
                'issue_items.quantity as issued_qty',
                'issue_vouchers.issue_date',
                'issue_vouchers.purpose'
            )
            ->selectRaw('COALESCE(SUM(return_items.quantity), 0) as returned_qty')
            ->selectRaw('issue_items.quantity - COALESCE(SUM(return_items.quantity), 0) as pending_qty')
            ->orderByDesc('issue_vouchers.issue_date')
            ->get();

        // Check if CSV export requested
        if (request('export') === 'csv') {
            return $this->exportEmployeeIssuesToCsv($rows);
        }

        // Calculate metrics
        $totalIssued = $rows->sum('issued_qty');
        $totalReturned = $rows->sum('returned_qty');
        $totalPending = $rows->sum('pending_qty');
        $uniqueEmployees = $rows->unique('employee_name')->count();

        return view('reports.employee-issues', [
            'rows' => $rows,
            'metrics' => [
                'total_issued' => $totalIssued,
                'total_returned' => $totalReturned,
                'total_pending' => $totalPending,
                'employees' => $uniqueEmployees,
            ]
        ]);
    }

    private function exportStockToCsv($rows)
    {
        $filename = 'stock-report-' . now()->format('Y-m-d-H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($rows) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Product', 'Category', 'Balance', 'Unit', 'Min Stock']);

            foreach ($rows as $row) {
                fputcsv($file, [
                    $row->name,
                    $row->category_name ?? '-',
                    $row->balance,
                    $row->unit,
                    $row->min_stock,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportEmployeeIssuesToCsv($rows)
    {
        $filename = 'employee-issues-report-' . now()->format('Y-m-d-H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($rows) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Issue Date', 'Employee', 'Product', 'Issued', 'Returned', 'Pending', 'Purpose']);

            foreach ($rows as $row) {
                fputcsv($file, [
                    \Illuminate\Support\Carbon::parse($row->issue_date)->format('Y-m-d'),
                    $row->employee_name,
                    $row->product_name,
                    $row->issued_qty,
                    $row->returned_qty,
                    $row->pending_qty,
                    $row->purpose,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
