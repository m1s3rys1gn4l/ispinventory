<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-slate-900">Edit Product</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <form method="POST" action="{{ route('products.update', $product) }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="category_id" class="block text-sm font-medium text-slate-700">Category</label>
                        <select name="category_id" id="category_id" class="mt-1 block w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm focus:border-cyan-500 focus:outline-none focus:ring-cyan-500">
                            <option value="">Select category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700">Product Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" class="mt-1 block w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-slate-900 placeholder-slate-400 shadow-sm focus:border-cyan-500 focus:outline-none focus:ring-cyan-500" required>
                        @error('name')
                            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="sku" class="block text-sm font-medium text-slate-700">SKU</label>
                        <input type="text" name="sku" id="sku" value="{{ old('sku', $product->sku) }}" class="mt-1 block w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-slate-900 placeholder-slate-400 shadow-sm focus:border-cyan-500 focus:outline-none focus:ring-cyan-500">
                        @error('sku')
                            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="unit" class="block text-sm font-medium text-slate-700">Unit</label>
                        <select name="unit" id="unit" class="mt-1 block w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-slate-900 shadow-sm focus:border-cyan-500 focus:outline-none focus:ring-cyan-500" required>
                            <optgroup label="Count">
                                <option value="Pcs" {{ old('unit', $product->unit) === 'Pcs' ? 'selected' : '' }}>Pcs (Pieces)</option>
                                <option value="Box" {{ old('unit', $product->unit) === 'Box' ? 'selected' : '' }}>Box</option>
                                <option value="Pair" {{ old('unit', $product->unit) === 'Pair' ? 'selected' : '' }}>Pair</option>
                                <option value="Set" {{ old('unit', $product->unit) === 'Set' ? 'selected' : '' }}>Set</option>
                                <option value="Roll" {{ old('unit', $product->unit) === 'Roll' ? 'selected' : '' }}>Roll</option>
                                <option value="Each" {{ old('unit', $product->unit) === 'Each' ? 'selected' : '' }}>Each</option>
                            </optgroup>
                            <optgroup label="Length">
                                <option value="m" {{ old('unit', $product->unit) === 'm' ? 'selected' : '' }}>Meter (m)</option>
                                <option value="cm" {{ old('unit', $product->unit) === 'cm' ? 'selected' : '' }}>Centimeter (cm)</option>
                                <option value="ft" {{ old('unit', $product->unit) === 'ft' ? 'selected' : '' }}>Feet (ft)</option>
                            </optgroup>
                            <optgroup label="Weight">
                                <option value="kg" {{ old('unit', $product->unit) === 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                                <option value="g" {{ old('unit', $product->unit) === 'g' ? 'selected' : '' }}>Gram (g)</option>
                            </optgroup>
                        </select>
                        @error('unit')
                            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="min_stock" class="block text-sm font-medium text-slate-700">Min Stock</label>
                        <input type="number" step="0.01" min="0" name="min_stock" id="min_stock" value="{{ old('min_stock', $product->min_stock) }}" class="mt-1 block w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-slate-900 placeholder-slate-400 shadow-sm focus:border-cyan-500 focus:outline-none focus:ring-cyan-500" required>
                        @error('min_stock')
                            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="notes" class="block text-sm font-medium text-slate-700">Notes</label>
                        <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-slate-900 placeholder-slate-400 shadow-sm focus:border-cyan-500 focus:outline-none focus:ring-cyan-500">{{ old('notes', $product->notes) }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-3">
                        <label class="inline-flex items-center gap-2">
                            <input type="hidden" name="track_serial" value="0">
                            <input type="checkbox" name="track_serial" value="1" {{ $product->track_serial ? 'checked' : '' }} class="h-4 w-4 rounded border-slate-300 text-cyan-600 focus:ring-2 focus:ring-cyan-500">
                            <span class="text-sm text-slate-700">Track serial</span>
                        </label>
                        <label class="inline-flex items-center gap-2">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" {{ $product->is_active ? 'checked' : '' }} class="h-4 w-4 rounded border-slate-300 text-cyan-600 focus:ring-2 focus:ring-cyan-500">
                            <span class="text-sm text-slate-700">Active</span>
                        </label>
                    </div>

                    <div class="flex gap-3 pt-4">
                        <button type="submit" class="rounded-xl bg-cyan-600 px-4 py-2 font-medium text-white hover:bg-cyan-700">Update Product</button>
                        <a href="{{ route('products.index') }}" class="rounded-xl border border-slate-300 bg-slate-50 px-4 py-2 font-medium text-slate-900 hover:bg-slate-100">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
