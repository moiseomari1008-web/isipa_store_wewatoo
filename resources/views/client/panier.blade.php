<x-layouts.app>
    <div class="space-y-6">
        <div class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="text-xs uppercase tracking-[0.35em] text-zinc-500">Mon panier</p>
                    <h1 class="mt-3 text-3xl font-black text-zinc-900 dark:text-white">Verifier mes selections</h1>
                    <p class="mt-3 text-sm leading-7 text-zinc-600 dark:text-zinc-300">
                        Modifiez les quantites, retirez des articles puis passez a la commande avec votre mode de paiement prefere.
                    </p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('client.boutique') }}" class="rounded-full border border-zinc-300 px-5 py-3 text-sm font-semibold uppercase tracking-[0.2em] text-zinc-700 dark:border-zinc-700 dark:text-zinc-200">Continuer mes achats</a>
                    @if ($items->isNotEmpty())
                        <a href="{{ route('client.checkout') }}" class="rounded-full bg-[#2613D8] px-5 py-3 text-sm font-bold uppercase tracking-[0.2em] text-[#F9F909] dark:bg-[#F9F909] dark:text-[#2613D8]">Commander</a>
                    @endif
                </div>
            </div>
        </div>

        @if (session('client_success'))
            <div class="rounded-2xl border border-emerald-500/30 bg-emerald-500/10 px-5 py-4 text-sm text-emerald-600 dark:text-emerald-200">
                {{ session('client_success') }}
            </div>
        @endif

        @if (session('client_error'))
            <div class="rounded-2xl border border-rose-500/30 bg-rose-500/10 px-5 py-4 text-sm text-rose-600 dark:text-rose-200">
                {{ session('client_error') }}
            </div>
        @endif

        <div class="grid gap-6 xl:grid-cols-[1.25fr_0.75fr]">
            <div class="space-y-4">
                @forelse ($items as $item)
                    <div class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                        <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                            <div class="space-y-2">
                                <p class="text-xs uppercase tracking-[0.35em] text-zinc-500">{{ $item->produit->categorie?->nom ?? 'Produit' }}</p>
                                <h2 class="text-2xl font-black text-zinc-900 dark:text-white">{{ $item->produit->nom }}</h2>
                                <p class="text-sm text-zinc-600 dark:text-zinc-300">Prix unitaire: {{ number_format((float) $item->produit->prix_unitaire, 2, ',', ' ') }}</p>
                            </div>

                            <div class="flex flex-col items-start gap-3 lg:items-end">
                                <form method="POST" action="{{ route('panier.update', $item) }}" class="flex items-end gap-3">
                                    @csrf
                                    @method('PATCH')
                                    <div>
                                        <label for="item-{{ $item->id }}" class="text-xs font-semibold uppercase tracking-[0.35em] text-zinc-500">Quantite</label>
                                        <input id="item-{{ $item->id }}" name="quantite" type="number" min="1" max="{{ $item->produit->stock }}" value="{{ $item->quantite }}" class="mt-2 w-28 rounded-2xl border border-zinc-200 bg-zinc-50 px-4 py-3 text-sm text-zinc-900 outline-none dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">
                                    </div>
                                    <button type="submit" class="rounded-full border border-zinc-300 px-4 py-3 text-xs font-bold uppercase tracking-[0.2em] text-zinc-700 dark:border-zinc-700 dark:text-zinc-200">Mettre a jour</button>
                                </form>

                                <form method="POST" action="{{ route('panier.remove', $item) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm font-semibold text-rose-600 dark:text-rose-300">Retirer du panier</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="rounded-3xl border border-dashed border-zinc-300 bg-white p-10 text-center text-sm text-zinc-500 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-400">
                        Votre panier est vide. Ajoutez des produits depuis la boutique client.
                    </div>
                @endforelse
            </div>

            <div class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <p class="text-xs uppercase tracking-[0.35em] text-zinc-500">Resume</p>
                <div class="mt-6 space-y-4">
                    <div class="flex items-center justify-between text-sm text-zinc-600 dark:text-zinc-300">
                        <span>Articles</span>
                        <span>{{ $items->sum('quantite') }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm text-zinc-600 dark:text-zinc-300">
                        <span>Sous-total</span>
                        <span>{{ number_format($total, 2, ',', ' ') }}</span>
                    </div>
                    <div class="border-t border-zinc-200 pt-4 dark:border-zinc-800">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-semibold text-zinc-900 dark:text-white">Total</span>
                            <span class="text-3xl font-black text-[#2613D8] dark:text-[#F9F909]">{{ number_format($total, 2, ',', ' ') }}</span>
                        </div>
                    </div>
                </div>

                @if ($items->isNotEmpty())
                    <a href="{{ route('client.checkout') }}" class="mt-8 inline-flex w-full items-center justify-center rounded-full bg-[#2613D8] px-5 py-3 text-sm font-bold uppercase tracking-[0.2em] text-[#F9F909] dark:bg-[#F9F909] dark:text-[#2613D8]">
                        Passer la commande
                    </a>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app>
