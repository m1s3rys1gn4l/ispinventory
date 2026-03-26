<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-slate-900">Stock Report</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <!-- Metrics Cards -->
            <div class="grid gap-4 md:grid-cols-4">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-sm font-medium text-slate-600">Total Products</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">{{ $metrics['total_products'] }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-sm font-medium text-slate-600">Categories</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">{{ $metrics['categories'] }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-sm font-medium text-rose-600">Low Stock Items</p>
                    <p class="mt-2 text-3xl font-bold text-rose-600">{{ $metrics['low_stock'] }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-sm font-medium text-slate-600">Total Units In Stock</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">{{ number_format($metrics['total_value'], 0) }}</p>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
                    <h3 class="font-semibold text-slate-900">Stock Details</h3>
                    <a href="{{ route('reports.stock') }}?export=csv" class="rounded-lg bg-cyan-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-cyan-700">Export CSV</a>
                </div>
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 text-left text-slate-600">
                        <tr>
                            <th class="px-4 py-3">Product</th>
                            <th class="px-4 py-3">Category</th>
                            <th class="px-4 py-3">Balance</th>
                            <th class="px-4 py-3">Unit</th>
                            <th class="px-4 py-3">Min Stock</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($rows as $row)
                            <tr>
                                <td class="px-4 py-3 font-medium text-slate-900">{{ $row->name }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $row->category_name ?? '-' }}</td>
                                <td class="px-4 py-3 {{ $row->balance < $row->min_stock ? 'text-rose-600 font-semibold' : 'text-slate-700' }}">{{ fmt_qty($row->balance, $row->unit) }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $row->unit }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ fmt_qty($row->min_stock, $row->unit) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-4 py-6 text-center text-slate-500">No stock data yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
