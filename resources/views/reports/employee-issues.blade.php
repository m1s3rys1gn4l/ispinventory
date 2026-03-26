<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-slate-900">Employee Issue Report</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <!-- Metrics Cards -->
            <div class="grid gap-4 md:grid-cols-4">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-sm font-medium text-slate-600">Total Issued</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">{{ number_format($metrics['total_issued'], 0) }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-sm font-medium text-emerald-600">Total Returned</p>
                    <p class="mt-2 text-3xl font-bold text-emerald-600">{{ number_format($metrics['total_returned'], 0) }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-sm font-medium text-amber-600">Total Pending</p>
                    <p class="mt-2 text-3xl font-bold text-amber-600">{{ number_format($metrics['total_pending'], 0) }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-sm font-medium text-slate-600">Employees</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">{{ $metrics['employees'] }}</p>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
                    <h3 class="font-semibold text-slate-900">Issue Details</h3>
                    <a href="{{ route('reports.employee-issues') }}?export=csv" class="rounded-lg bg-cyan-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-cyan-700">Export CSV</a>
                </div>
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 text-left text-slate-600">
                        <tr>
                            <th class="px-4 py-3">Issue Date</th>
                            <th class="px-4 py-3">Employee</th>
                            <th class="px-4 py-3">Product</th>
                            <th class="px-4 py-3">Issued</th>
                            <th class="px-4 py-3">Returned</th>
                            <th class="px-4 py-3">Pending</th>
                            <th class="px-4 py-3">Purpose</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($rows as $row)
                            <tr>
                                <td class="px-4 py-3">{{ \Illuminate\Support\Carbon::parse($row->issue_date)->format('Y-m-d') }}</td>
                                <td class="px-4 py-3 font-medium text-slate-900">{{ $row->employee_name }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $row->product_name }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ fmt_qty($row->issued_qty, $row->product_unit) }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ fmt_qty($row->returned_qty, $row->product_unit) }}</td>
                                <td class="px-4 py-3 {{ $row->pending_qty > 0 ? 'text-amber-600 font-semibold' : 'text-emerald-600' }}">{{ fmt_qty($row->pending_qty, $row->product_unit) }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $row->purpose }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="px-4 py-6 text-center text-slate-500">No issue data yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
