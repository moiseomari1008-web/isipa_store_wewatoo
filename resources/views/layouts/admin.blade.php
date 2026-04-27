<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - ISIPA Store</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-100">
    <div class="flex h-screen bg-gray-100">
        <!-- Sidebar -->
        <div class="w-64 bg-gray-900 text-white p-6 flex flex-col">
            <div class="mb-8">
                <h1 class="text-2xl font-bold">ISIPA Admin</h1>
                <p class="text-gray-400 text-sm">{{ auth()->user()->nom_complet }}</p>
                <p class="text-gray-500 text-xs mt-1">{{ auth()->user()->role->nom ?? 'N/A' }}</p>
            </div>

            <nav class="flex-1 space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700' : '' }}">
                    📊 Tableau de bord
                </a>

                @if(auth()->user()->hasPermission('gerer_produits') || auth()->user()->isSuperAdmin())
                <a href="{{ route('admin.produits.index') }}" class="block px-4 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.produits.*') ? 'bg-gray-700' : '' }}">
                    📦 Produits
                </a>
                @endif

                @if(auth()->user()->hasPermission('gerer_categories') || auth()->user()->isSuperAdmin())
                <a href="{{ route('admin.categories.index') }}" class="block px-4 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.categories.*') ? 'bg-gray-700' : '' }}">
                    🏷️ Catégories
                </a>
                @endif

                @if(auth()->user()->hasPermission('voir_commandes') || auth()->user()->isSuperAdmin())
                <a href="{{ route('admin.commandes.index') }}" class="block px-4 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.commandes.*') ? 'bg-gray-700' : '' }}">
                    📋 Commandes
                </a>
                @endif

                @if(auth()->user()->hasPermission('valider_paiements') || auth()->user()->isSuperAdmin())
                <a href="{{ route('admin.paiements.index') }}" class="block px-4 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.paiements.*') ? 'bg-gray-700' : '' }}">
                    💳 Paiements
                </a>
                @endif

                @if(auth()->user()->hasPermission('gerer_livraisons') || auth()->user()->isSuperAdmin())
                <a href="{{ route('admin.livraisons.index') }}" class="block px-4 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.livraisons.*') ? 'bg-gray-700' : '' }}">
                    🚚 Livraisons
                </a>
                @endif

                @if(auth()->user()->hasPermission('gerer_utilisateurs') || auth()->user()->isSuperAdmin())
                <a href="{{ route('admin.utilisateurs.index') }}" class="block px-4 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.utilisateurs.*') ? 'bg-gray-700' : '' }}">
                    👥 Utilisateurs
                </a>
                @endif
            </nav>

            <div class="border-t border-gray-700 pt-4 mt-4">
                <form method="POST" action="{{ route('logout') }}" class="block">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 rounded hover:bg-gray-700 text-red-400">
                        🚪 Déconnexion
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <div class="bg-white shadow p-6 border-b">
                <h2 class="text-2xl font-bold text-gray-800">{{ $title ?? 'Tableau de bord' }}</h2>
                <p class="text-gray-600 text-sm mt-1">{{ $subtitle ?? '' }}</p>
            </div>

            <!-- Content -->
            <div class="flex-1 overflow-auto p-6">
                {{ $slot }}
            </div>
        </div>
    </div>

    @livewireScripts
</body>
</html>
