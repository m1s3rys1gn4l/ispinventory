<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-slate-900">Vendors</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            @include('partials.flash')

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="mb-4 text-lg font-semibold text-slate-900">Add Vendor</h3>
                <form method="POST" action="{{ route('vendors.store') }}" class="grid gap-4 md:grid-cols-2">
                    @csrf
                    <input type="text" name="name" placeholder="Vendor name" class="block w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-slate-900 placeholder-slate-400 shadow-sm focus:border-cyan-500 focus:outline-none focus:ring-cyan-500" required>
                    <input type="text" name="contact_person" placeholder="Contact person" class="block w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-slate-900 placeholder-slate-400 shadow-sm focus:border-cyan-500 focus:outline-none focus:ring-cyan-500">
                    <input type="text" name="phone" placeholder="Phone" class="block w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-slate-900 placeholder-slate-400 shadow-sm focus:border-cyan-500 focus:outline-none focus:ring-cyan-500">
                    <input type="text" name="address" placeholder="Address" class="block w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-slate-900 placeholder-slate-400 shadow-sm focus:border-cyan-500 focus:outline-none focus:ring-cyan-500">
                    <textarea name="notes" rows="2" placeholder="Notes" class="block w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-slate-900 placeholder-slate-400 shadow-sm focus:border-cyan-500 focus:outline-none focus:ring-cyan-500 md:col-span-2"></textarea>
                    <button class="md:col-span-2 rounded-xl bg-cyan-600 px-4 py-2 font-medium text-white hover:bg-cyan-700">Save Vendor</button>
                </form>
            </div>

            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 text-left text-slate-600">
                        <tr>
                            <th class="px-4 py-3">Name</th>
                            <th class="px-4 py-3">Contact</th>
                            <th class="px-4 py-3">Phone</th>
                            <th class="px-4 py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($vendors as $vendor)
                            <tr>
                                <td class="px-4 py-3 font-medium text-slate-900">{{ $vendor->name }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $vendor->contact_person }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $vendor->phone }}</td>
                                <td class="px-4 py-3">
                                    <form method="POST" action="{{ route('vendors.destroy', $vendor) }}" onsubmit="return confirm('Delete this vendor?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-rose-600 hover:text-rose-700">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-4 py-6 text-center text-slate-500">No vendors yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
