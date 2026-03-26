<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-slate-900">
            Inventory Dashboard
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            @include('partials.flash')

            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm text-slate-500">Products</p>
                    <p class="mt-2 text-2xl font-bold text-slate-900">{{ $productCount }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm text-slate-500">Low Stock Items</p>
                    <p class="mt-2 text-2xl font-bold text-rose-600">{{ $lowStockCount }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm text-slate-500">Items Still Issued</p>
                    <p class="mt-2 text-2xl font-bold text-amber-600">{{ $activeIssueCount }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm text-slate-500">Ledger Transactions</p>
                    <p class="mt-2 text-2xl font-bold text-cyan-700">{{ $transactionCount }}</p>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="mb-4 text-lg font-semibold text-slate-900">Quick Actions</h3>
                <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                    <a href="{{ route('purchases.index') }}" class="rounded-xl bg-cyan-600 px-4 py-3 text-center font-medium text-white hover:bg-cyan-700">Add Purchase</a>
                    <a href="{{ route('issues.index') }}" class="rounded-xl bg-slate-900 px-4 py-3 text-center font-medium text-white hover:bg-slate-800">Issue Items</a>
                    <a href="{{ route('returns.index') }}" class="rounded-xl bg-emerald-600 px-4 py-3 text-center font-medium text-white hover:bg-emerald-700">Record Return</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
