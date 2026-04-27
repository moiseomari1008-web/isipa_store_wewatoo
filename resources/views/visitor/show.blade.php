<x-layouts.visitor :title="$produit->nom . ' | ISIPA Store'">
    <section class="px-6 pb-8 pt-10 lg:px-10">
        <div class="mx-auto max-w-7xl space-y-8">
            <div class="text-sm text-white/60">
                <a href="{{ route('home') }}" class="text-[var(--store-accent)]">Accueil</a>
                /
                <a href="{{ route('catalogue') }}" class="text-[var(--store-accent)]">Catalogue</a>
                /
                <span>{{ $produit->nom }}</span>
            </div>

            <div class="visitor-panel grid gap-8 lg:grid-cols-[0.95fr_1.05fr]">
                <div class="visitor-detail-visual">
                    <span>{{ $produit->categorie?->nom ?? 'Produit informatique' }}</span>
                </div>

                <div class="space-y-6">
                    <div class="space-y-4">
                        <p class="visitor-kicker">Fiche produit</p>
                        <h1 class="visitor-hero-title text-4xl md:text-5xl">{{ $produit->nom }}</h1>
                        <p class="max-w-2xl text-base leading-8 text-white/76">
                            {{ $produit->description ?: "Ce produit informatique est visible dans l'espace visiteur. L'ajout au panier sera branche dans l'espace client lors de la prochaine etape." }}
                        </p>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <div class="visitor-detail-card">
                            <p class="visitor-card-kicker">Prix unitaire</p>
                            <p class="visitor-detail-value">{{ number_format((float) $produit->prix_unitaire, 2, ',', ' ') }}</p>
                        </div>
                        <div class="visitor-detail-card">
                            <p class="visitor-card-kicker">Stock</p>
                            <p class="visitor-detail-value">{{ $produit->stock }}</p>
                        </div>
                        <div class="visitor-detail-card">
                            <p class="visitor-card-kicker">Categorie</p>
                            <p class="visitor-detail-text">{{ $produit->categorie?->nom ?? 'Sans categorie' }}</p>
                        </div>
                        <div class="visitor-detail-card">
                            <p class="visitor-card-kicker">Statut</p>
                            <p class="visitor-detail-text">{{ $produit->statut }}</p>
                        </div>
                    </div>

                    <div class="flex flex-col gap-4 sm:flex-row">
                        <a href="{{ route('register') }}" class="visitor-button">S'inscrire pour commander</a>
                        <a href="{{ route('catalogue') }}" class="visitor-button visitor-button-ghost">Retour au catalogue</a>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
                    <div>
                        <p class="visitor-kicker">Suggestions</p>
                        <h2 class="visitor-section-title">Produits similaires</h2>
                    </div>
                </div>

                <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
                    @forelse ($similaires as $article)
                        <article class="visitor-product-card">
                            <div class="visitor-product-visual">
                                <span>{{ $article->categorie?->nom ?? 'Produit' }}</span>
                            </div>
                            <div class="space-y-4">
                                <div class="flex items-start justify-between gap-4">
                                    <h3 class="visitor-card-title">{{ $article->nom }}</h3>
                                    <div class="visitor-price-tag">
                                        {{ number_format((float) $article->prix_unitaire, 2, ',', ' ') }}
                                    </div>
                                </div>
                                <div class="visitor-card-footer">
                                    <span>Stock: {{ $article->stock }}</span>
                                    <a href="{{ route('boutique.produits.show', $article) }}" class="font-semibold text-[var(--store-accent)]">Voir</a>
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="visitor-empty-state md:col-span-2 xl:col-span-4">
                            <h3 class="visitor-empty-title">Pas encore de produits similaires</h3>
                            <p class="visitor-empty-text">
                                Cette fiche est deja operationnelle. Les suggestions s'afficheront automatiquement quand d'autres produits de la meme categorie seront ajoutes.
                            </p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>
</x-layouts.visitor>
