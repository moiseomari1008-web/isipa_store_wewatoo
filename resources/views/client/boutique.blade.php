<x-layouts.app>
    <div class="space-y-6">
        <div class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="text-xs uppercase tracking-[0.35em] text-zinc-500">Boutique client</p>
                    <h1 class="mt-3 text-3xl font-black text-zinc-900 dark:text-white">Catalogue avec ajout au panier</h1>
                    <p class="mt-3 max-w-3xl text-sm leading-7 text-zinc-600 dark:text-zinc-300">
                        Cette version client reprend le catalogue visiteur et y ajoute les actions d'achat: choix des quantites, ajout au panier et preparation de commande.
                    </p>
                </div>
                <a href="{{ route('panier.index') }}" class="rounded-full bg-[#2613D8] px-5 py-3 text-sm font-bold uppercase tracking-[0.2em] text-[#F9F909] dark:bg-[#F9F909] dark:text-[#2613D8]">
                    Voir mon panier
                </a>
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

        <div class="grid gap-6 xl:grid-cols-[300px_1fr]">
            <aside class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <form method="GET" action="{{ route('client.boutique') }}" class="space-y-4">
                    <div>
                        <label for="q" class="text-xs font-semibold uppercase tracking-[0.35em] text-zinc-500">Recherche</label>
                        <input id="q" name="q" type="text" value="{{ $search }}" class="mt-2 w-full rounded-2xl border border-zinc-200 bg-zinc-50 px-4 py-3 text-sm text-zinc-900 outline-none dark:border-zinc-700 dark:bg-zinc-800 dark:text-white" placeholder="Laptop, clavier, imprimante...">
                    </div>
                    <div>
                        <label for="categorie" class="text-xs font-semibold uppercase tracking-[0.35em] text-zinc-500">Categorie</label>
                        <select id="categorie" name="categorie" class="mt-2 w-full rounded-2xl border border-zinc-200 bg-zinc-50 px-4 py-3 text-sm text-zinc-900 outline-none dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">
                            <option value="">Toutes les categories</option>
                            @foreach ($categories as $categorie)
                                <option value="{{ $categorie->id }}" @selected($categorieActive === $categorie->id)>
                                    {{ $categorie->nom }} ({{ $categorie->produits_disponibles_count }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="w-full rounded-full bg-[#2613D8] px-5 py-3 text-sm font-bold uppercase tracking-[0.2em] text-[#F9F909] dark:bg-[#F9F909] dark:text-[#2613D8]">Filtrer</button>
                </form>
            </aside>

            <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                @forelse ($produits as $produit)
                    <article class="flex h-full flex-col rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                        <div class="rounded-2xl bg-[linear-gradient(135deg,#2613D8_0%,#12083d_100%)] p-5 text-[#F9F909]">
                            <p class="text-xs uppercase tracking-[0.3em]">{{ $produit->categorie?->nom ?? 'Produit' }}</p>
                            <h2 class="mt-4 text-2xl font-black text-white">{{ $produit->nom }}</h2>
                        </div>

                        <div class="mt-5 flex flex-1 flex-col">
                            <p class="text-sm leading-7 text-zinc-600 dark:text-zinc-300">
                                {{ \Illuminate\Support\Str::limit($produit->description ?: "Produit informatique disponible dans la boutique client.", 120) }}
                            </p>

                            <div class="mt-5 flex items-center justify-between gap-4">
                                <span class="text-2xl font-black text-[#2613D8] dark:text-[#F9F909]">
                                    {{ number_format((float) $produit->prix_unitaire, 2, ',', ' ') }}
                                </span>
                                <span class="rounded-full bg-zinc-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-zinc-700 dark:bg-zinc-800 dark:text-zinc-200">
                                    Stock {{ $produit->stock }}
                                </span>
                            </div>

                            <form method="POST" action="{{ route('panier.add', $produit) }}" class="mt-6 flex items-end gap-3">
                                @csrf
                                <div class="flex-1">
                                    <label for="quantite-{{ $produit->id }}" class="text-xs font-semibold uppercase tracking-[0.35em] text-zinc-500">Quantite</label>
                                    <input id="quantite-{{ $produit->id }}" name="quantite" type="number" min="1" max="{{ $produit->stock }}" value="1" class="mt-2 w-full rounded-2xl border border-zinc-200 bg-zinc-50 px-4 py-3 text-sm text-zinc-900 outline-none dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">
                                </div>
                                <button type="submit" class="rounded-full bg-[#F9F909] px-5 py-3 text-xs font-bold uppercase tracking-[0.2em] text-[#2613D8]">
                                    Ajouter
                                </button>
                            </form>
                        </div>
                    </article>
                @empty
                    <div class="md:col-span-2 xl:col-span-3 rounded-3xl border border-dashed border-zinc-300 bg-white p-10 text-center text-sm text-zinc-500 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-400">
                        Aucun produit ne correspond au filtre actuel.
                    </div>
                @endforelse
            </div>
        </div>

        @if ($produits->hasPages())
            <div class="rounded-3xl border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                {{ $produits->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>
