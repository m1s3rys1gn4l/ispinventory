<section class="space-y-4">
    <header>
        <h3 class="text-lg font-semibold text-slate-900">{{ __('Delete Account') }}</h3>
        <p class="mt-2 text-sm text-slate-600">{{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}</p>
    </header>

    <div
        x-data="{ open: false }"
    >
        <button
            @click="open = true"
            class="rounded-xl bg-rose-600 px-4 py-2 font-medium text-white hover:bg-rose-700"
        >{{ __('Delete Account') }}</button>

        <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" @click="open = false">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-lg" @click.stop>
                <h2 class="text-lg font-semibold text-slate-900">{{ __('Are you sure you want to delete your account?') }}</h2>
                <p class="mt-2 text-sm text-slate-600">{{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}</p>

                <form method="post" action="{{ route('profile.destroy') }}" class="mt-6 space-y-4">
                    @csrf
                    @method('delete')

                    <div>
                        <label for="password" class="sr-only">{{ __('Password') }}</label>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            class="block w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-slate-900 placeholder-slate-400 shadow-sm focus:border-cyan-500 focus:outline-none focus:ring-cyan-500"
                            placeholder="{{ __('Password') }}"
                            required
                        />
                        @error('password', 'userDeletion')
                            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end gap-3">
                        <button
                            type="button"
                            @click="open = false"
                            class="rounded-xl border border-slate-300 bg-slate-50 px-4 py-2 font-medium text-slate-900 hover:bg-slate-100"
                        >{{ __('Cancel') }}</button>

                        <button
                            type="submit"
                            class="rounded-xl bg-rose-600 px-4 py-2 font-medium text-white hover:bg-rose-700"
                        >{{ __('Delete Account') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
