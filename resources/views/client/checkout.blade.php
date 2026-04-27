<x-layouts.app>
    <div class="space-y-6">
        <div class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <p class="text-xs uppercase tracking-[0.35em] text-zinc-500">Validation</p>
            <h1 class="mt-3 text-3xl font-black text-zinc-900 dark:text-white">Passer ma commande</h1>
            <p class="mt-3 max-w-3xl text-sm leading-7 text-zinc-600 dark:text-zinc-300">
                Verifiez votre adresse, choisissez le mode de paiement et confirmez la commande. Une ligne de paiement sera creee automatiquement.
            </p>
        </div>

        <div class="grid gap-6 xl:grid-cols-[1fr_0.85fr]">
            <div class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <form method="POST" action="{{ route('client.commandes.store') }}" class="space-y-5">
                    @csrf
                    <div>
                        <label for="adresse_livraison" class="text-xs font-semibold uppercase tracking-[0.35em] text-zinc-500">Adresse de livraison</label>
                        <textarea id="adresse_livraison" name="adresse_livraison" rows="4" class="mt-2 w-full rounded-2xl border border-zinc-200 bg-zinc-50 px-4 py-3 text-sm text-zinc-900 outline-none dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">{{ old('adresse_livraison', $user->adresse) }}</textarea>
                        @error('adresse_livraison')
                            <p class="mt-2 text-sm text-rose-600 dark:text-rose-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="type_paiement" class="text-xs font-semibold uppercase tracking-[0.35em] text-zinc-500">Mode de paiement</label>
                        <select id="type_paiement" name="type_paiement" class="mt-2 w-full rounded-2xl border border-zinc-200 bg-zinc-50 px-4 py-3 text-sm text-zinc-900 outline-none dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">
                            @foreach ($modesPaiement as $mode)
                                <option value="{{ $mode }}" @selected(old('type_paiement') === $mode)>{{ $mode }}</option>
                            @endforeach
                        </select>
                        @error('type_paiement')
                            <p class="mt-2 text-sm text-rose-600 dark:text-rose-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label for="num_compte" class="text-xs font-semibold uppercase tracking-[0.35em] text-zinc-500">Numero de compte</label>
                            <input id="num_compte" name="num_compte" type="text" value="{{ old('num_compte') }}" class="mt-2 w-full rounded-2xl border border-zinc-200 bg-zinc-50 px-4 py-3 text-sm text-zinc-900 outline-none dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">
                        </div>
                        <div>
                            <label for="reference_transaction" class="text-xs font-semibold uppercase tracking-[0.35em] text-zinc-500">Reference transaction</label>
                            <input id="reference_transaction" name="reference_transaction" type="text" value="{{ old('reference_transaction') }}" class="mt-2 w-full rounded-2xl border border-zinc-200 bg-zinc-50 px-4 py-3 text-sm text-zinc-900 outline-none dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">
                        </div>
                    </div>

                    <button type="submit" class="inline-flex rounded-full bg-[#2613D8] px-6 py-3 text-sm font-bold uppercase tracking-[0.2em] text-[#F9F909] dark:bg-[#F9F909] dark:text-[#2613D8]">
                        Confirmer la commande
                    </button>
                </form>
            </div>

            <div class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <p class="text-xs font-semibold uppercase tracking-[0.35em] text-zinc-500">Resume commande</p>
                <div class="mt-6 space-y-4">
                    @foreach ($items as $item)
                        <div class="flex items-center justify-between gap-4 border-b border-zinc-200 pb-4 dark:border-zinc-800">
                            <div>
                                <p class="font-semibold text-zinc-900 dark:text-white">{{ $item->produit->nom }}</p>
                                <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ $item->quantite }} x {{ number_format((float) $item->produit->prix_unitaire, 2, ',', ' ') }}</p>
                            </div>
                            <span class="text-sm font-bold text-zinc-900 dark:text-white">
                                {{ number_format($item->quantite * (float) $item->produit->prix_unitaire, 2, ',', ' ') }}
                            </span>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6 flex items-center justify-between">
                    <span class="text-sm font-semibold text-zinc-900 dark:text-white">Total a payer</span>
                    <span class="text-3xl font-black text-[#2613D8] dark:text-[#F9F909]">{{ number_format($total, 2, ',', ' ') }}</span>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
