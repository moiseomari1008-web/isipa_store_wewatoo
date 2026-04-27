<x-layouts.visitor :title="'Accueil | ISIPA Store'">
    <section class="relative overflow-hidden px-6 pb-16 pt-8 lg:px-10 lg:pb-24 lg:pt-14">
        <div class="mx-auto grid max-w-7xl gap-10 lg:grid-cols-[1.2fr_0.8fr] lg:items-center">
            <div class="space-y-8">
                <div class="visitor-chip">E-commerce informatique ISIPA</div>

                <div class="space-y-5">
                    <p class="visitor-kicker">Espace visiteur</p>
                    <h1 class="visitor-hero-title">
                        Achetez le meilleur du materiel informatique dans une boutique
                        <span>claire, rapide et moderne.</span>
                    </h1>
                    <p class="max-w-2xl text-base leading-8 text-white/76 lg:text-lg">
                        Consultez les catalogues, explorez les categories et preparez votre inscription pour commander les produits informatiques de l'ISIPA.
                    </p>
                </div>

                <div class="flex flex-col gap-4 sm:flex-row">
                    <a href="{{ route('catalogue') }}" class="visitor-button">Voir le catalogue</a>
                    <a href="{{ route('register') }}" class="visitor-button visitor-button-ghost">Creer un compte</a>
                </div>

                <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    <div class="visitor-stat-card">
                        <span class="visitor-stat-value">{{ $stats['produits'] }}</span>
                        <span class="visitor-stat-label">Produits disponibles</span>
                    </div>
                    <div class="visitor-stat-card">
                        <span class="visitor-stat-value">{{ $stats['categories'] }}</span>
                        <span class="visitor-stat-label">Categories actives</span>
                    </div>
                    <div class="visitor-stat-card">
                        <span class="visitor-stat-value">24h</span>
                        <span class="visitor-stat-label">Boutique accessible</span>
                    </div>
                    <div class="visitor-stat-card">
                        <span class="visitor-stat-value">MVP</span>
                        <span class="visitor-stat-label">Parcours visiteur en ligne</span>
                    </div>
                </div>
            </div>

            <div class="visitor-display-panel">
                <div class="visitor-display-grid">
                    <div class="visitor-display-card">
                        <p class="visitor-display-label">Catalogue interactif</p>
                        <p class="visitor-display-title">Produits tries par categorie, statut et nouveaute.</p>
                    </div>
                    <div class="visitor-display-card visitor-display-card-alt">
                        <p class="visitor-display-label">Inscription rapide</p>
                        <p class="visitor-display-title">Le visiteur peut rejoindre la boutique et devenir client.</p>
                    </div>
                    <div class="visitor-display-banner">
                        <span>Couleurs officielles</span>
                        <div class="mt-4 flex gap-3">
                            <div class="visitor-color-swatch bg-[var(--store-primary)]"></div>
                            <div class="visitor-color-swatch bg-[var(--store-text)]"></div>
                            <div class="visitor-color-swatch bg-[var(--store-secondary)]"></div>
                            <div class="visitor-color-swatch border border-white/30 bg-white"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="px-6 py-8 lg:px-10">
        <div class="mx-auto max-w-7xl">
            <div class="mb-8 flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="visitor-kicker">Categories</p>
                    <h2 class="visitor-section-title">Explorer les univers du catalogue</h2>
                </div>
                <a href="{{ route('catalogue') }}" class="text-sm font-semibold tracking-[0.15em] text-[var(--store-accent)] uppercase">Tout voir</a>
            </div>

            <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                @forelse ($categories as $categorie)
                    <a href="{{ route('catalogue', ['categorie' => $categorie->id]) }}" class="visitor-category-card">
                        <div>
                            <p class="visitor-card-kicker">Categorie</p>
                            <h3 class="visitor-card-title">{{ $categorie->nom }}</h3>
                        </div>
                        <p class="text-sm leading-7 text-white/74">
                            {{ $categorie->description ?: "Decouvrez les produits disponibles dans cette categorie." }}
                        </p>
                        <div class="visitor-card-footer">
                            <span>{{ $categorie->produits_disponibles_count }} produit(s)</span>
                            <span>Explorer</span>
                        </div>
                    </a>
                @empty
                    <div class="visitor-empty-state md:col-span-2 xl:col-span-3">
                        <h3 class="visitor-empty-title">Aucune categorie disponible pour le moment</h3>
                        <p class="visitor-empty-text">
                            Ajoute d'abord des categories et des produits dans la base pour alimenter automatiquement l'espace visiteur.
                        </p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="px-6 py-10 lg:px-10">
        <div class="mx-auto max-w-7xl">
            <div class="mb-8 flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="visitor-kicker">Produits vedettes</p>
                    <h2 class="visitor-section-title">Le visiteur peut deja consulter ton catalogue</h2>
                </div>
                <a href="{{ route('catalogue') }}" class="visitor-button visitor-button-sm">Acceder au catalogue</a>
            </div>

            <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                @forelse ($produitsVedettes as $produit)
                    <article class="visitor-product-card">
                        <div class="visitor-product-visual">
                            <span>{{ $produit->categorie?->nom ?? 'Produit' }}</span>
                        </div>

                        <div class="space-y-4">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="visitor-card-kicker">Disponible</p>
                                    <h3 class="visitor-card-title">{{ $produit->nom }}</h3>
                                </div>
                                <div class="visitor-price-tag">
                                    {{ number_format((float) $produit->prix_unitaire, 2, ',', ' ') }}
                                </div>
                            </div>

                            <p class="text-sm leading-7 text-white/74">
                                {{ \Illuminate\Support\Str::limit($produit->description ?: "Produit informatique disponible dans la boutique ISIPA.", 110) }}
                            </p>

                            <div class="visitor-card-footer">
                                <span>Stock: {{ $produit->stock }}</span>
                                <a href="{{ route('boutique.produits.show', $produit) }}" class="font-semibold text-[var(--store-accent)]">Voir le detail</a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="visitor-empty-state md:col-span-2 xl:col-span-3">
                        <h3 class="visitor-empty-title">Le catalogue est pret, mais encore vide</h3>
                        <p class="visitor-empty-text">
                            Les produits ajoutes dans la table `produits` apparaitront automatiquement ici pour les visiteurs.
                        </p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
</x-layouts.visitor>
