<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-slate-900">Returns</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            @include('partials.flash')

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="mb-4 text-lg font-semibold text-slate-900">Record Return</h3>
                <form method="POST" action="{{ route('returns.store') }}" class="grid gap-4 md:grid-cols-4">
                    @csrf
                    <select name="issue_item_id" class="md:col-span-2 block w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm focus:border-cyan-500 focus:outline-none focus:ring-cyan-500" required>
                        <option value="">Select issued item</option>
                        @foreach ($openIssueItems as $item)
                            <option value="{{ $item->id }}">
                                {{ $item->issueVoucher->employee->name }} | {{ $item->product->name }} | Pending: {{ fmt_qty($item->remainingQty(), $item->product->unit) }}
                            </option>
                        @endforeach
                    </select>
                    <input type="date" name="return_date" class="block w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm focus:border-cyan-500 focus:outline-none focus:ring-cyan-500" required>
                    <input type="number" step="0.01" min="0.01" name="quantity" placeholder="Qty" class="block w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-slate-900 placeholder-slate-400 shadow-sm focus:border-cyan-500 focus:outline-none focus:ring-cyan-500" required>
                    <select name="condition" class="block w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm focus:border-cyan-500 focus:outline-none focus:ring-cyan-500">
                        <option value="good">Good</option>
                        <option value="damaged">Damaged</option>
                    </select>
                    <input type="text" name="notes" placeholder="Notes" class="md:col-span-2 block w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-slate-900 placeholder-slate-400 shadow-sm focus:border-cyan-500 focus:outline-none focus:ring-cyan-500">
                    <button class="rounded-xl bg-cyan-600 px-4 py-2 font-medium text-white hover:bg-cyan-700">Save Return</button>
                </form>
            </div>

            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 text-left text-slate-600">
                        <tr>
                            <th class="px-4 py-3">Date</th>
                            <th class="px-4 py-3">Employee</th>
                            <th class="px-4 py-3">Product</th>
                            <th class="px-4 py-3">Qty</th>
                            <th class="px-4 py-3">Condition</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($returns as $return)
                            <tr>
                                <td class="px-4 py-3">{{ $return->return_date->format('Y-m-d') }}</td>
                                <td class="px-4 py-3">{{ $return->issueItem->issueVoucher->employee->name }}</td>
                                <td class="px-4 py-3">{{ $return->issueItem->product->name }}</td>
                                <td class="px-4 py-3">{{ fmt_qty($return->quantity, $return->issueItem->product->unit) }}</td>
                                <td class="px-4 py-3">{{ ucfirst($return->condition) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-4 py-6 text-center text-slate-500">No return records yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
