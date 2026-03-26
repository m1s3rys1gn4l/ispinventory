<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-slate-900">Products</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            @include('partials.flash')

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="mb-4 text-lg font-semibold text-slate-900">Add Product</h3>
                <form method="POST" action="{{ route('products.store') }}" class="grid gap-4 md:grid-cols-3">
                    @csrf
                    <select name="category_id" class="block w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm focus:border-cyan-500 focus:outline-none focus:ring-cyan-500">
                        <option value="">Select category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <input type="text" name="name" placeholder="Product name" class="block w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-slate-900 placeholder-slate-400 shadow-sm focus:border-cyan-500 focus:outline-none focus:ring-cyan-500" required>
                    <input type="text" name="sku" placeholder="SKU (optional)" class="block w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-slate-900 placeholder-slate-400 shadow-sm focus:border-cyan-500 focus:outline-none focus:ring-cyan-500">
                    <select name="unit" class="block w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm focus:border-cyan-500 focus:outline-none focus:ring-cyan-500" required>
                        <optgroup label="Count">
                            <option value="Pcs" selected>Pcs (Pieces)</option>
                            <option value="Box">Box</option>
                            <option value="Pair">Pair</option>
                            <option value="Set">Set</option>
                            <option value="Roll">Roll</option>
                            <option value="Each">Each</option>
                        </optgroup>
                        <optgroup label="Length">
                            <option value="m">Meter (m)</option>
                            <option value="cm">Centimeter (cm)</option>
                            <option value="ft">Feet (ft)</option>
                        </optgroup>
                        <optgroup label="Weight">
                            <option value="kg">Kilogram (kg)</option>
                            <option value="g">Gram (g)</option>
                        </optgroup>
                    </select>
                    <input type="number" step="0.01" min="0" name="min_stock" placeholder="Min stock" value="0" class="block w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-slate-900 placeholder-slate-400 shadow-sm focus:border-cyan-500 focus:outline-none focus:ring-cyan-500" required>
                    <textarea name="notes" rows="1" placeholder="Notes" class="block w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-slate-900 placeholder-slate-400 shadow-sm focus:border-cyan-500 focus:outline-none focus:ring-cyan-500"></textarea>
                    <label class="inline-flex items-center gap-2">
                        <input type="hidden" name="track_serial" value="0">
                        <input type="checkbox" name="track_serial" value="1" class="h-4 w-4 rounded border-slate-300 text-cyan-600 focus:ring-2 focus:ring-cyan-500">
                        <span class="text-sm text-slate-700">Track serial</span>
                    </label>
                    <label class="inline-flex items-center gap-2">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" checked class="h-4 w-4 rounded border-slate-300 text-cyan-600 focus:ring-2 focus:ring-cyan-500">
                        <span class="text-sm text-slate-700">Active</span>
                    </label>
                    <button class="rounded-xl bg-cyan-600 px-4 py-2 font-medium text-white hover:bg-cyan-700">Save Product</button>
                </form>
            </div>

            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 text-left text-slate-600">
                        <tr>
                            <th class="px-4 py-3">Product</th>
                            <th class="px-4 py-3">Category</th>
                            <th class="px-4 py-3">Unit</th>
                            <th class="px-4 py-3">Current Stock</th>
                            <th class="px-4 py-3">Min Stock</th>
                            <th class="px-4 py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($products as $product)
                            @php $balance = (float) ($stockByProduct[$product->id] ?? 0); @endphp
                            <tr>
                                <td class="px-4 py-3 font-medium text-slate-900">{{ $product->name }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $product->category?->name ?? '-' }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $product->unit }}</td>
                                <td class="px-4 py-3 {{ $balance < $product->min_stock ? 'text-rose-600 font-semibold' : 'text-slate-700' }}">{{ fmt_qty($balance, $product->unit) }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ fmt_qty($product->min_stock, $product->unit) }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('products.edit', $product) }}" class="text-cyan-600 hover:text-cyan-700">Edit</a>
                                        <form method="POST" action="{{ route('products.destroy', $product) }}" onsubmit="return confirm('Delete this product?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-rose-600 hover:text-rose-700">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-4 py-6 text-center text-slate-500">No products yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
