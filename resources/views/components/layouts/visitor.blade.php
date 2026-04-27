<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head', ['title' => $title ?? "ISIPA Store | Boutique informatique"])
    </head>
    <body class="visitor-shell text-[var(--store-text)] antialiased">
        <div class="visitor-bg"></div>

        <header class="visitor-header">
            <div class="mx-auto flex w-full max-w-7xl items-center justify-between gap-6 px-6 py-5 lg:px-10">
                <a href="{{ route('home') }}" class="relative z-30 flex items-center gap-4">
                    <div class="visitor-brand-mark">
                        <span>IS</span>
                    </div>

                    <div>
                        <p class="text-xs uppercase tracking-[0.45em] text-white/70">ISIPA Store</p>
                        <p class="visitor-brand-title">Boutique Informatique</p>
                    </div>
                </a>

                <nav class="relative z-30 hidden items-center gap-3 md:flex">
                    <a href="{{ route('home') }}" class="visitor-nav-link {{ request()->routeIs('home') ? 'is-active' : '' }}">Accueil</a>
                    <a href="{{ route('catalogue') }}" class="visitor-nav-link {{ request()->routeIs('catalogue') ? 'is-active' : '' }}">Catalogue</a>
                    <a href="{{ route('login') }}" class="visitor-nav-link">Connexion</a>
                    <a href="{{ route('register') }}" class="visitor-button visitor-button-sm">S'inscrire</a>
                    <a href="{{ route('admin.login') }}" class="ml-4 text-[0.65rem] uppercase tracking-widest text-white/40 transition hover:text-[var(--store-accent)]">Espace Admin</a>
                </nav>
            </div>

            <div class="mx-auto flex w-full max-w-7xl gap-3 px-6 pb-4 md:hidden lg:px-10 flex-wrap items-center">
                <a href="{{ route('login') }}" class="visitor-nav-link mobile-auth-link">Connexion</a>
                <a href="{{ route('register') }}" class="visitor-button visitor-button-sm mobile-auth-button">S'inscrire</a>
                <a href="{{ route('admin.login') }}" class="ml-auto text-[0.65rem] uppercase tracking-widest text-white/40">Admin</a>
            </div>
        </header>

        <main class="relative z-10">
            {{ $slot }}
        </main>

        <footer class="relative z-10 mt-20 border-t border-white/12">
            <div class="mx-auto grid max-w-7xl gap-8 px-6 py-10 lg:grid-cols-[1.4fr_1fr_1fr] lg:px-10">
                <div class="space-y-3">
                    <p class="text-xs uppercase tracking-[0.4em] text-white/65">Plateforme e-commerce</p>
                    <h2 class="visitor-footer-title">Vente des produits informatiques de l'ISIPA</h2>
                    <p class="max-w-xl text-sm leading-7 text-white/72">
                        Un espace visiteur pour consulter les catalogues, decouvrir les categories et rejoindre la boutique en quelques clics.
                    </p>
                </div>

                <div>
                    <p class="visitor-footer-label">Navigation</p>
                    <div class="mt-4 space-y-3 text-sm text-white/72">
                        <a href="{{ route('home') }}" class="block transition hover:text-[var(--store-accent)]">Accueil</a>
                        <a href="{{ route('catalogue') }}" class="block transition hover:text-[var(--store-accent)]">Catalogue</a>
                        <a href="{{ route('login') }}" class="block transition hover:text-[var(--store-accent)]">Connexion</a>
                        <a href="{{ route('register') }}" class="block transition hover:text-[var(--store-accent)]">Inscription</a>
                    </div>
                </div>

                <div>
                    <p class="visitor-footer-label">MVP visiteur</p>
                    <div class="mt-4 space-y-3 text-sm text-white/72">
                        <p>Consulter les produits disponibles</p>
                        <p>Explorer les categories</p>
                        <p>Creer un compte client</p>
                    </div>
                </div>
            </div>
        </footer>
    </body>
</html>
