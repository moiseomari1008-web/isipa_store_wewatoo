<x-layouts.visitor :title="'Catalogue | ISIPA Store'">
    <section class="px-6 pb-8 pt-10 lg:px-10">
        <div class="mx-auto max-w-7xl">
            <div class="visitor-panel grid gap-8 lg:grid-cols-[300px_1fr]">
                <aside class="space-y-6">
                    <div>
                        <p class="visitor-kicker">Recherche</p>
                        <h1 class="visitor-section-title">Catalogue visiteur</h1>
                    </div>

                    <form method="GET" action="{{ route('catalogue') }}" class="space-y-4">
                        <div class="space-y-2">
                            <label for="q" class="visitor-form-label">Mot-clé</label>
                            <input
                                id="q"
                                name="q"
                                type="text"
                                value="{{ $search }}"
                                placeholder="Ordinateur, imprimante, accessoire..."
                                class="visitor-input"
                            >
                        </div>

                        <div class="space-y-2">
                            <label for="categorie" class="visitor-form-label">Categorie</label>
                            <select id="categorie" name="categorie" class="visitor-input">
                                <option value="">Toutes les categories</option>
                                @foreach ($categories as $categorie)
                                    <option value="{{ $categorie->id }}" @selected($categorieActive === $categorie->id)>
                                        {{ $categorie->nom }} ({{ $categorie->produits_disponibles_count }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex flex-col gap-3 sm:flex-row lg:flex-col">
                            <button type="submit" class="visitor-button w-full justify-center">Filtrer</button>
                            <a href="{{ route('catalogue') }}" class="visitor-button visitor-button-ghost w-full justify-center">Reinitialiser</a>
                        </div>
                    </form>

                    <div class="rounded-[1.75rem] border border-white/12 bg-white/4 p-5">
                        <p class="visitor-card-kicker">MVP visiteur</p>
                        <div class="mt-4 space-y-3 text-sm leading-7 text-white/74">
                            <p>Consulter les catalogues des produits</p>
                            <p>S'inscrire a la plateforme</p>
                            <p>Acceder aux fiches detaillees des articles</p>
                        </div>
                    </div>
                </aside>

                <div class="space-y-6">
                    <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <p class="visitor-kicker">Resultats</p>
                            <p class="text-sm text-white/70">
                                {{ $produits->total() }} produit(s) disponible(s) pour les visiteurs
                            </p>
                        </div>
                        <div class="text-sm text-white/60">
                            Navigation: <a href="{{ route('home') }}" class="text-[var(--store-accent)]">Accueil</a> / Catalogue
                        </div>
                    </div>

                    <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                        @forelse ($produits as $produit)
                            <article class="visitor-product-card">
                                <div class="visitor-product-visual">
                                    <span>{{ $produit->categorie?->nom ?? 'Produit' }}</span>
                                </div>

                                <div class="space-y-4">
                                    <div class="flex items-start justify-between gap-4">
                                        <div>
                                            <p class="visitor-card-kicker">Stock {{ $produit->stock }}</p>
                                            <h2 class="visitor-card-title">{{ $produit->nom }}</h2>
                                        </div>
                                        <div class="visitor-price-tag">
                                            {{ number_format((float) $produit->prix_unitaire, 2, ',', ' ') }}
                                        </div>
                                    </div>

                                    <p class="text-sm leading-7 text-white/74">
                                        {{ \Illuminate\Support\Str::limit($produit->description ?: "Produit informatique disponible dans la boutique ISIPA.", 120) }}
                                    </p>

                                    <div class="visitor-card-footer">
                                        <span>{{ $produit->categorie?->nom ?? 'Sans categorie' }}</span>
                                        <a href="{{ route('boutique.produits.show', $produit) }}" class="font-semibold text-[var(--store-accent)]">Voir le detail</a>
                                    </div>
                                </div>
                            </article>
                        @empty
                            <div class="visitor-empty-state md:col-span-2 xl:col-span-3">
                                <h2 class="visitor-empty-title">Aucun produit ne correspond a ce filtre</h2>
                                <p class="visitor-empty-text">
                                    Essaie une autre categorie ou retire le mot-cle pour afficher davantage de produits disponibles.
                                </p>
                            </div>
                        @endforelse
                    </div>

                    @if ($produits->hasPages())
                        <div class="visitor-pagination">
                            {{ $produits->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
</x-layouts.visitor>
