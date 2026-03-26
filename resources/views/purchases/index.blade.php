<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-slate-900">Purchases (Stock In)</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            @include('partials.flash')

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="mb-4 text-lg font-semibold text-slate-900">New Purchase</h3>
                <form method="POST" action="{{ route('purchases.store') }}" class="space-y-4">
                    @csrf
                    <div class="grid gap-4 md:grid-cols-4">
                        <input type="date" name="purchase_date" class="rounded-xl border-slate-300 focus:border-cyan-500 focus:ring-cyan-500" required>
                        <select name="vendor_id" class="rounded-xl border-slate-300 focus:border-cyan-500 focus:ring-cyan-500">
                            <option value="">Select vendor</option>
                            @foreach ($vendors as $vendor)
                                <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                            @endforeach
                        </select>
                        <input type="text" name="reference_no" placeholder="Invoice / Reference" class="rounded-xl border-slate-300 focus:border-cyan-500 focus:ring-cyan-500">
                        <input type="text" name="notes" placeholder="Notes" class="rounded-xl border-slate-300 focus:border-cyan-500 focus:ring-cyan-500">
                    </div>

                    <div id="purchase-lines" class="space-y-2">
                        <div class="grid gap-3 md:grid-cols-12">
                            <select name="product_id[]" class="md:col-span-6 block w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm focus:border-cyan-500 focus:outline-none focus:ring-cyan-500" required>
                                <option value="">Select product</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->unit }})</option>
                                @endforeach
                            </select>
                            <input type="number" step="0.01" min="0.01" name="quantity[]" placeholder="Qty" class="md:col-span-3 block w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-slate-900 placeholder-slate-400 shadow-sm focus:border-cyan-500 focus:outline-none focus:ring-cyan-500" required>
                            <input type="number" step="0.01" min="0" name="unit_cost[]" placeholder="Unit Cost" class="md:col-span-3 block w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-slate-900 placeholder-slate-400 shadow-sm focus:border-cyan-500 focus:outline-none focus:ring-cyan-500" required>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <button type="button" id="add-purchase-line" class="rounded-xl border border-slate-300 px-4 py-2 text-slate-700 hover:bg-slate-50">Add Line</button>
                        <button class="rounded-xl bg-cyan-600 px-4 py-2 font-medium text-white hover:bg-cyan-700">Save Purchase</button>
                    </div>
                </form>
            </div>

            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 text-left text-slate-600">
                        <tr>
                            <th class="px-4 py-3">Date</th>
                            <th class="px-4 py-3">Vendor</th>
                            <th class="px-4 py-3">Reference</th>
                            <th class="px-4 py-3">Items</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($purchases as $purchase)
                            <tr>
                                <td class="px-4 py-3">{{ $purchase->purchase_date->format('Y-m-d') }}</td>
                                <td class="px-4 py-3">{{ $purchase->vendor?->name ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $purchase->reference_no }}</td>
                                <td class="px-4 py-3 text-slate-600">
                                    {{ $purchase->items->map(fn($i) => $i->product->name.' x '.$i->quantity)->join(', ') }}
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-4 py-6 text-center text-slate-500">No purchase entries yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('add-purchase-line')?.addEventListener('click', function () {
            const container = document.getElementById('purchase-lines');
            const first = container.firstElementChild.cloneNode(true);
            first.querySelectorAll('input, select').forEach(el => el.value = '');
            container.appendChild(first);
        });
    </script>
</x-app-layout>
