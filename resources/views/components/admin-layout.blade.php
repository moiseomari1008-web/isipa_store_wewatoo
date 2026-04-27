@props(['title' => 'Admin', 'subtitle' => ''])

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} — ISIPA Store Admin</title>
    <meta name="description" content="Espace administrateur ISIPA Store">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        body { font-family: 'Inter', sans-serif; }

        /* Sidebar */
        .admin-sidebar {
            width: 260px;
            min-width: 260px;
            background: linear-gradient(180deg, #0f172a 0%, #1e293b 60%, #0f172a 100%);
            border-right: 1px solid rgba(255,255,255,0.06);
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
            overflow-y: auto;
        }

        .sidebar-brand {
            padding: 1.5rem 1.25rem 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.07);
        }

        .sidebar-brand h1 {
            font-size: 1.15rem;
            font-weight: 800;
            background: linear-gradient(135deg, #60a5fa, #a78bfa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: 0.5px;
        }

        .role-badge {
            display: inline-block;
            font-size: 0.65rem;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 999px;
            margin-top: 4px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .role-super    { background: rgba(168,85,247,0.2); color: #c084fc; border: 1px solid rgba(168,85,247,0.3); }
        .role-articles { background: rgba(59,130,246,0.2); color: #60a5fa; border: 1px solid rgba(59,130,246,0.3); }
        .role-users    { background: rgba(34,197,94,0.2);  color: #4ade80; border: 1px solid rgba(34,197,94,0.3); }

        /* Nav sections */
        .nav-section-label {
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: rgba(148,163,184,0.5);
            padding: 0.75rem 1.25rem 0.25rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.6rem 1.25rem;
            margin: 0 0.5rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
            color: rgba(148,163,184,0.85);
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .nav-link:hover {
            background: rgba(255,255,255,0.07);
            color: #f1f5f9;
            transform: translateX(2px);
        }

        .nav-link.active {
            background: linear-gradient(135deg, rgba(96,165,250,0.15), rgba(167,139,250,0.15));
            color: #f1f5f9;
            border: 1px solid rgba(96,165,250,0.2);
        }

        .nav-icon {
            width: 1.2rem;
            text-align: center;
            font-size: 1rem;
            flex-shrink: 0;
        }

        /* Main */
        .admin-main { flex: 1; display: flex; flex-direction: column; overflow: hidden; }

        .admin-topbar {
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            padding: 0 1.5rem;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-shrink: 0;
        }

        .admin-topbar h2 {
            font-size: 1.125rem;
            font-weight: 700;
            color: #0f172a;
        }

        .admin-content {
            flex: 1;
            overflow-y: auto;
            padding: 1.75rem;
            background: #f8fafc;
        }

        /* Topbar actions */
        .topbar-actions { display: flex; align-items: center; gap: 1rem; }
        .topbar-user {
            display: flex; align-items: center; gap: 0.6rem;
            background: #f1f5f9; padding: 0.4rem 0.75rem; border-radius: 8px;
            font-size: 0.8rem; font-weight: 600; color: #475569;
        }
        .topbar-avatar {
            width: 30px; height: 30px; border-radius: 50%;
            background: linear-gradient(135deg, #60a5fa, #a78bfa);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.7rem; font-weight: 700; color: white;
        }

        .btn-logout {
            background: none; border: 1px solid #e2e8f0; color: #64748b;
            font-size: 0.8rem; font-weight: 500; padding: 0.4rem 0.8rem;
            border-radius: 8px; cursor: pointer; transition: all 0.2s;
            display: flex; align-items: center; gap: 0.4rem;
        }
        .btn-logout:hover { background: #fee2e2; border-color: #fca5a5; color: #dc2626; }

        /* Breadcrumb */
        .admin-breadcrumb { font-size: 0.8rem; color: #94a3b8; }
        .admin-breadcrumb span { color: #64748b; }

        /* Flash messages */
        .flash-success {
            background: #f0fdf4; border-left: 4px solid #22c55e;
            color: #166534; padding: 0.75rem 1rem; border-radius: 8px;
            margin-bottom: 1.25rem; font-size: 0.875rem;
        }
        .flash-error {
            background: #fef2f2; border-left: 4px solid #ef4444;
            color: #991b1b; padding: 0.75rem 1rem; border-radius: 8px;
            margin-bottom: 1.25rem; font-size: 0.875rem;
        }

        /* Divider */
        .nav-divider { height: 1px; background: rgba(255,255,255,0.06); margin: 0.5rem 1rem; }
    </style>
</head>
<body class="bg-slate-50">
<div style="display:flex; height:100vh; overflow:hidden;">

    {{-- ============ SIDEBAR ============ --}}
    <aside class="admin-sidebar">

        {{-- Branding --}}
        <div class="sidebar-brand">
            <div style="display:flex; align-items:center; gap:0.6rem;">
                <div style="width:34px;height:34px;border-radius:8px;background:linear-gradient(135deg,#60a5fa,#a78bfa);display:flex;align-items:center;justify-content:center;font-size:1rem;">🏪</div>
                <div>
                    <h1>ISIPA Admin</h1>
                </div>
            </div>
            <div style="margin-top:0.75rem; padding:0.6rem; background:rgba(255,255,255,0.04); border-radius:8px;">
                <p style="color:#f1f5f9; font-size:0.8rem; font-weight:600;">{{ auth()->user()->nom_complet }}</p>
                <p style="color:#94a3b8; font-size:0.7rem; margin-top:2px;">{{ auth()->user()->email }}</p>
                @php $roleName = auth()->user()->role?->nom ?? 'N/A'; @endphp
                <span class="role-badge {{ $roleName === 'Super Admin' ? 'role-super' : ($roleName === 'Admin Articles' ? 'role-articles' : 'role-users') }}">
                    {{ $roleName }}
                </span>
            </div>
        </div>

        {{-- Navigation --}}
        <nav style="flex:1; padding:0.75rem 0;">

            {{-- === SECTION PRINCIPAL === --}}
            <p class="nav-section-label">Principal</p>

            <a href="{{ route('admin.dashboard') }}"
               class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
               id="nav-dashboard">
                <span class="nav-icon">📊</span> Tableau de bord
            </a>

            {{-- === SECTION CATALOGUE === --}}
            @if(auth()->user()->hasPermission('gerer_produits') || auth()->user()->isSuperAdmin())
            <div class="nav-divider"></div>
            <p class="nav-section-label">Catalogue</p>

            <a href="{{ route('admin.produits.index') }}"
               class="nav-link {{ request()->routeIs('admin.produits.*') ? 'active' : '' }}"
               id="nav-produits">
                <span class="nav-icon">📦</span> Produits
            </a>
            @endif

            @if(auth()->user()->hasPermission('gerer_categories') || auth()->user()->isSuperAdmin())
            <a href="{{ route('admin.categories.index') }}"
               class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}"
               id="nav-categories">
                <span class="nav-icon">🏷️</span> Catégories
            </a>
            @endif

            @if(auth()->user()->hasPermission('publication_produits') || auth()->user()->isSuperAdmin())
            <a href="{{ route('admin.produits.index') }}"
               class="nav-link {{ request()->routeIs('admin.publication.*') ? 'active' : '' }}"
               id="nav-publication">
                <span class="nav-icon">🌐</span> Publication
            </a>
            @endif

            {{-- === SECTION VENTES === --}}
            @if(auth()->user()->hasPermission('voir_commandes') || auth()->user()->isSuperAdmin())
            <div class="nav-divider"></div>
            <p class="nav-section-label">Ventes</p>

            <a href="{{ route('admin.commandes.index') }}"
               class="nav-link {{ request()->routeIs('admin.commandes.*') ? 'active' : '' }}"
               id="nav-commandes">
                <span class="nav-icon">📋</span> Commandes
            </a>
            @endif

            @if(auth()->user()->hasPermission('valider_paiements') || auth()->user()->isSuperAdmin())
            <a href="{{ route('admin.paiements.index') }}"
               class="nav-link {{ request()->routeIs('admin.paiements.*') ? 'active' : '' }}"
               id="nav-paiements">
                <span class="nav-icon">💳</span> Paiements
            </a>
            @endif

            @if(auth()->user()->hasPermission('gerer_livraisons') || auth()->user()->isSuperAdmin())
            <a href="{{ route('admin.livraisons.index') }}"
               class="nav-link {{ request()->routeIs('admin.livraisons.*') ? 'active' : '' }}"
               id="nav-livraisons">
                <span class="nav-icon">🚚</span> Livraisons
            </a>
            @endif

            {{-- === SECTION UTILISATEURS === --}}
            @if(auth()->user()->hasPermission('gerer_utilisateurs') || auth()->user()->isSuperAdmin())
            <div class="nav-divider"></div>
            <p class="nav-section-label">Utilisateurs</p>

            <a href="{{ route('admin.utilisateurs.index') }}"
               class="nav-link {{ request()->routeIs('admin.utilisateurs.*') ? 'active' : '' }}"
               id="nav-utilisateurs">
                <span class="nav-icon">👥</span> Utilisateurs
            </a>
            @endif

            @if(auth()->user()->hasPermission('attribuer_roles') || auth()->user()->isSuperAdmin())
            <a href="{{ route('admin.roles.index') }}"
               class="nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}"
               id="nav-roles">
                <span class="nav-icon">🎭</span> Rôles & Permissions
            </a>
            @endif

            {{-- === SECTION GLOBAL === --}}
            <div class="nav-divider"></div>
            <p class="nav-section-label">Accès</p>

            @if(auth()->user()->hasPermission('acces_boutique') || auth()->user()->isSuperAdmin())
            <a href="{{ route('client.boutique') }}"
               class="nav-link"
               id="nav-boutique"
               target="_blank">
                <span class="nav-icon">🏪</span> Accès Boutique
                <span style="margin-left:auto;font-size:0.65rem;color:#64748b;">↗</span>
            </a>
            @endif

            @if(auth()->user()->hasPermission('passer_commande') || auth()->user()->isSuperAdmin())
            <a href="{{ route('client.checkout') }}"
               class="nav-link"
               id="nav-passer-commande">
                <span class="nav-icon">🛒</span> Passer Commande
            </a>
            @endif

            @if(auth()->user()->isSuperAdmin())
            <div class="nav-divider"></div>
            <p class="nav-section-label">Super Admin</p>
            <a href="{{ route('admin.parametres.index') }}"
               class="nav-link {{ request()->routeIs('admin.parametres.*') ? 'active' : '' }}"
               id="nav-parametres">
                <span class="nav-icon">⚙️</span> Paramètres du site
            </a>
            @endif

        </nav>

        {{-- Logout --}}
        <div style="padding:1rem; border-top:1px solid rgba(255,255,255,0.06);">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout" style="width:100%; justify-content:center;" id="btn-logout">
                    🚪 Déconnexion
                </button>
            </form>
        </div>
    </aside>

    {{-- ============ MAIN CONTENT ============ --}}
    <div class="admin-main">

        {{-- Topbar --}}
        <header class="admin-topbar">
            <div>
                <h2>{{ $title }}</h2>
                @if($subtitle)
                <p class="admin-breadcrumb">Admin / <span>{{ $title }}</span></p>
                @endif
            </div>
            <div class="topbar-actions">
                <div class="topbar-user">
                    <div class="topbar-avatar">{{ auth()->user()->initials() }}</div>
                    {{ auth()->user()->nom_complet }}
                </div>
                <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn-logout">🚪 Déco</button>
                </form>
            </div>
        </header>

        {{-- Flash Messages --}}
        <div style="padding:1rem 1.75rem 0;">
            @if(session('success'))
                <div class="flash-success">✅ {{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="flash-error">❌ {{ session('error') }}</div>
            @endif
        </div>

        {{-- Content --}}
        <main class="admin-content">
            {{ $slot }}
        </main>
    </div>
</div>

@livewireScripts
</body>
</html>
