<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-slate-900">Issue Items (Stock Out)</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            @include('partials.flash')

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="mb-4 text-lg font-semibold text-slate-900">New Issue Voucher</h3>
                <form method="POST" action="{{ route('issues.store') }}" class="space-y-4">
                    @csrf
                    <div class="grid gap-4 md:grid-cols-3">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Issue Date</label>
                            <input type="date" name="issue_date" class="block w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm focus:border-cyan-500 focus:outline-none focus:ring-cyan-500" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Employee</label>
                            <select name="employee_id" class="block w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm focus:border-cyan-500 focus:outline-none focus:ring-cyan-500" required>
                                <option value="">Select employee</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Purpose</label>
                            <input type="text" name="purpose" placeholder="Item purpose" class="block w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-slate-900 placeholder-slate-400 shadow-sm focus:border-cyan-500 focus:outline-none focus:ring-cyan-500" required>
                        </div>
                    </div>
                    <textarea name="notes" rows="2" placeholder="Notes (optional)" class="block w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-slate-900 placeholder-slate-400 shadow-sm focus:border-cyan-500 focus:outline-none focus:ring-cyan-500"></textarea>

                    <div id="issue-lines" class="space-y-2">
                        <div class="grid gap-3 md:grid-cols-12">
                            <select name="product_id[]" class="md:col-span-8 block w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm focus:border-cyan-500 focus:outline-none focus:ring-cyan-500" required>
                                <option value="">Select product</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->unit }})</option>
                                @endforeach
                            </select>
                            <input type="number" step="0.01" min="0.01" name="quantity[]" placeholder="Qty" class="md:col-span-4 block w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-slate-900 placeholder-slate-400 shadow-sm focus:border-cyan-500 focus:outline-none focus:ring-cyan-500" required>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <button type="button" id="add-issue-line" class="rounded-xl border border-slate-300 px-4 py-2 text-slate-700 hover:bg-slate-50">Add Line</button>
                        <button class="rounded-xl bg-cyan-600 px-4 py-2 font-medium text-white hover:bg-cyan-700">Save Issue</button>
                    </div>
                </form>
            </div>

            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 text-left text-slate-600">
                        <tr>
                            <th class="px-4 py-3">Date</th>
                            <th class="px-4 py-3">Employee</th>
                            <th class="px-4 py-3">Purpose</th>
                            <th class="px-4 py-3">Items</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($issues as $issue)
                            <tr>
                                <td class="px-4 py-3">{{ $issue->issue_date->format('Y-m-d') }}</td>
                                <td class="px-4 py-3">{{ $issue->employee->name }}</td>
                                <td class="px-4 py-3">{{ $issue->purpose }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $issue->items->map(fn($i) => $i->product->name.' x '.$i->quantity)->join(', ') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-4 py-6 text-center text-slate-500">No issue records yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('add-issue-line')?.addEventListener('click', function () {
            const container = document.getElementById('issue-lines');
            const first = container.firstElementChild.cloneNode(true);
            first.querySelectorAll('input, select').forEach(el => el.value = '');
            container.appendChild(first);
        });
    </script>
</x-app-layout>
