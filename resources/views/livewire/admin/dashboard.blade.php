@volt
<?php

use Livewire\Volt\Component;
use App\Models\Produit;
use App\Models\Commande;
use App\Models\User;
use App\Models\Paiement;

new class extends Component {
    public function mount() {}
}; ?>

<div>
<x-admin-layout title="Tableau de bord" subtitle="Vue générale de la plateforme">

    {{-- Welcome banner --}}
    <div style="
        background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 50%, #7c3aed 100%);
        border-radius: 16px;
        padding: 1.75rem 2rem;
        margin-bottom: 1.75rem;
        color: white;
        position: relative;
        overflow: hidden;
    ">
        <div style="position:absolute;top:-30px;right:-30px;width:200px;height:200px;background:rgba(255,255,255,0.05);border-radius:50%;"></div>
        <div style="position:absolute;bottom:-50px;right:80px;width:150px;height:150px;background:rgba(255,255,255,0.04);border-radius:50%;"></div>
        <h2 style="font-size:1.3rem;font-weight:700;margin-bottom:0.4rem;">
            👋 Bienvenue, {{ auth()->user()->nom_complet }}
        </h2>
        <p style="font-size:0.875rem;opacity:0.85;">
            Vous êtes connecté en tant que
            <strong style="background:rgba(255,255,255,0.2);padding:2px 8px;border-radius:999px;font-size:0.8rem;">
                {{ auth()->user()->role?->nom ?? 'Admin' }}
            </strong>
        </p>
        <p style="font-size:0.75rem;opacity:0.6;margin-top:0.4rem;">
            {{ now()->isoFormat('dddd D MMMM YYYY — HH:mm') }}
        </p>
    </div>

    {{-- ===== Stats Cards ===== --}}
    <div style="display:grid; grid-template-columns: repeat(auto-fit,minmax(200px,1fr)); gap:1rem; margin-bottom:1.75rem;">

        @if(auth()->user()->hasPermission('gerer_produits') || auth()->user()->isSuperAdmin())
        <div style="background:white;border-radius:12px;padding:1.25rem;box-shadow:0 1px 3px rgba(0,0,0,0.06);border:1px solid #f1f5f9;transition:box-shadow 0.2s;" onmouseover="this.style.boxShadow='0 4px 16px rgba(0,0,0,0.1)'" onmouseout="this.style.boxShadow='0 1px 3px rgba(0,0,0,0.06)'">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;">
                <div>
                    <p style="font-size:0.8rem;color:#64748b;font-weight:500;margin-bottom:0.3rem;">Total Produits</p>
                    <p style="font-size:2rem;font-weight:800;color:#0f172a;">{{ \App\Models\Produit::count() }}</p>
                </div>
                <div style="background:#dbeafe;border-radius:10px;padding:0.6rem;font-size:1.3rem;">📦</div>
            </div>
            <a href="{{ route('admin.produits.index') }}" style="font-size:0.75rem;color:#3b82f6;font-weight:500;text-decoration:none;display:inline-block;margin-top:0.75rem;">
                Gérer les produits →
            </a>
        </div>
        @endif

        @if(auth()->user()->hasPermission('voir_commandes') || auth()->user()->isSuperAdmin())
        <div style="background:white;border-radius:12px;padding:1.25rem;box-shadow:0 1px 3px rgba(0,0,0,0.06);border:1px solid #f1f5f9;" onmouseover="this.style.boxShadow='0 4px 16px rgba(0,0,0,0.1)'" onmouseout="this.style.boxShadow='0 1px 3px rgba(0,0,0,0.06)'">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;">
                <div>
                    <p style="font-size:0.8rem;color:#64748b;font-weight:500;margin-bottom:0.3rem;">Commandes</p>
                    <p style="font-size:2rem;font-weight:800;color:#0f172a;">{{ \App\Models\Commande::count() }}</p>
                </div>
                <div style="background:#dcfce7;border-radius:10px;padding:0.6rem;font-size:1.3rem;">📋</div>
            </div>
            <a href="{{ route('admin.commandes.index') }}" style="font-size:0.75rem;color:#22c55e;font-weight:500;text-decoration:none;display:inline-block;margin-top:0.75rem;">
                Voir les commandes →
            </a>
        </div>
        @endif

        @if(auth()->user()->hasPermission('gerer_utilisateurs') || auth()->user()->isSuperAdmin())
        <div style="background:white;border-radius:12px;padding:1.25rem;box-shadow:0 1px 3px rgba(0,0,0,0.06);border:1px solid #f1f5f9;" onmouseover="this.style.boxShadow='0 4px 16px rgba(0,0,0,0.1)'" onmouseout="this.style.boxShadow='0 1px 3px rgba(0,0,0,0.06)'">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;">
                <div>
                    <p style="font-size:0.8rem;color:#64748b;font-weight:500;margin-bottom:0.3rem;">Utilisateurs</p>
                    <p style="font-size:2rem;font-weight:800;color:#0f172a;">{{ \App\Models\User::count() }}</p>
                </div>
                <div style="background:#fce7f3;border-radius:10px;padding:0.6rem;font-size:1.3rem;">👥</div>
            </div>
            <a href="{{ route('admin.utilisateurs.index') }}" style="font-size:0.75rem;color:#ec4899;font-weight:500;text-decoration:none;display:inline-block;margin-top:0.75rem;">
                Gérer les utilisateurs →
            </a>
        </div>
        @endif

        @if(auth()->user()->hasPermission('valider_paiements') || auth()->user()->isSuperAdmin())
        <div style="background:white;border-radius:12px;padding:1.25rem;box-shadow:0 1px 3px rgba(0,0,0,0.06);border:1px solid #f1f5f9;" onmouseover="this.style.boxShadow='0 4px 16px rgba(0,0,0,0.1)'" onmouseout="this.style.boxShadow='0 1px 3px rgba(0,0,0,0.06)'">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;">
                <div>
                    <p style="font-size:0.8rem;color:#64748b;font-weight:500;margin-bottom:0.3rem;">Paiements en attente</p>
                    <p style="font-size:2rem;font-weight:800;color:#0f172a;">{{ \App\Models\Paiement::where('statut', 'en_attente')->count() }}</p>
                </div>
                <div style="background:#fef3c7;border-radius:10px;padding:0.6rem;font-size:1.3rem;">💳</div>
            </div>
            <a href="{{ route('admin.paiements.index') }}" style="font-size:0.75rem;color:#f59e0b;font-weight:500;text-decoration:none;display:inline-block;margin-top:0.75rem;">
                Valider les paiements →
            </a>
        </div>
        @endif

        @if(auth()->user()->hasPermission('gerer_livraisons') || auth()->user()->isSuperAdmin())
        <div style="background:white;border-radius:12px;padding:1.25rem;box-shadow:0 1px 3px rgba(0,0,0,0.06);border:1px solid #f1f5f9;" onmouseover="this.style.boxShadow='0 4px 16px rgba(0,0,0,0.1)'" onmouseout="this.style.boxShadow='0 1px 3px rgba(0,0,0,0.06)'">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;">
                <div>
                    <p style="font-size:0.8rem;color:#64748b;font-weight:500;margin-bottom:0.3rem;">Livraisons</p>
                    <p style="font-size:2rem;font-weight:800;color:#0f172a;">{{ \App\Models\Commande::where('statut_livraison', 'en_cours')->count() }}</p>
                </div>
                <div style="background:#ede9fe;border-radius:10px;padding:0.6rem;font-size:1.3rem;">🚚</div>
            </div>
            <a href="{{ route('admin.livraisons.index') }}" style="font-size:0.75rem;color:#8b5cf6;font-weight:500;text-decoration:none;display:inline-block;margin-top:0.75rem;">
                Voir les livraisons →
            </a>
        </div>
        @endif

    </div>

    {{-- ===== Actions rapides selon rôle ===== --}}
    <div style="background:white;border-radius:12px;padding:1.5rem;box-shadow:0 1px 3px rgba(0,0,0,0.06);margin-bottom:1.75rem;">
        <h3 style="font-size:0.95rem;font-weight:700;color:#0f172a;margin-bottom:1rem;border-bottom:1px solid #f1f5f9;padding-bottom:0.75rem;">
            ⚡ Actions rapides
        </h3>
        <div style="display:flex;flex-wrap:wrap;gap:0.75rem;">
            @if(auth()->user()->hasPermission('gerer_produits') || auth()->user()->isSuperAdmin())
            <a href="{{ route('admin.produits.index') }}" id="quick-add-product" style="display:inline-flex;align-items:center;gap:0.5rem;background:#eff6ff;color:#2563eb;border:1px solid #bfdbfe;padding:0.5rem 1rem;border-radius:8px;font-size:0.8rem;font-weight:600;text-decoration:none;transition:all 0.2s;" onmouseover="this.style.background='#dbeafe'" onmouseout="this.style.background='#eff6ff'">
                📦 Ajouter un produit
            </a>
            @endif

            @if(auth()->user()->hasPermission('gerer_categories') || auth()->user()->isSuperAdmin())
            <a href="{{ route('admin.categories.index') }}" id="quick-add-category" style="display:inline-flex;align-items:center;gap:0.5rem;background:#f0fdf4;color:#16a34a;border:1px solid #bbf7d0;padding:0.5rem 1rem;border-radius:8px;font-size:0.8rem;font-weight:600;text-decoration:none;" onmouseover="this.style.background='#dcfce7'" onmouseout="this.style.background='#f0fdf4'">
                🏷️ Nouvelle catégorie
            </a>
            @endif

            @if(auth()->user()->hasPermission('voir_commandes') || auth()->user()->isSuperAdmin())
            <a href="{{ route('admin.commandes.index') }}" id="quick-view-orders" style="display:inline-flex;align-items:center;gap:0.5rem;background:#fefce8;color:#ca8a04;border:1px solid #fde68a;padding:0.5rem 1rem;border-radius:8px;font-size:0.8rem;font-weight:600;text-decoration:none;" onmouseover="this.style.background='#fef9c3'" onmouseout="this.style.background='#fefce8'">
                📋 Voir les commandes
            </a>
            @endif

            @if(auth()->user()->hasPermission('gerer_utilisateurs') || auth()->user()->isSuperAdmin())
            <a href="{{ route('admin.utilisateurs.index') }}" id="quick-manage-users" style="display:inline-flex;align-items:center;gap:0.5rem;background:#fdf4ff;color:#9333ea;border:1px solid #e9d5ff;padding:0.5rem 1rem;border-radius:8px;font-size:0.8rem;font-weight:600;text-decoration:none;" onmouseover="this.style.background='#f3e8ff'" onmouseout="this.style.background='#fdf4ff'">
                👥 Gérer utilisateurs
            </a>
            @endif

            @if(auth()->user()->hasPermission('valider_paiements') || auth()->user()->isSuperAdmin())
            <a href="{{ route('admin.paiements.index') }}" id="quick-validate-payment" style="display:inline-flex;align-items:center;gap:0.5rem;background:#fff7ed;color:#ea580c;border:1px solid #fed7aa;padding:0.5rem 1rem;border-radius:8px;font-size:0.8rem;font-weight:600;text-decoration:none;" onmouseover="this.style.background='#ffedd5'" onmouseout="this.style.background='#fff7ed'">
                💳 Valider paiements
            </a>
            @endif

            @if(auth()->user()->hasPermission('attribuer_roles') || auth()->user()->isSuperAdmin())
            <a href="{{ route('admin.roles.index') }}" id="quick-assign-roles" style="display:inline-flex;align-items:center;gap:0.5rem;background:#f0f9ff;color:#0284c7;border:1px solid #bae6fd;padding:0.5rem 1rem;border-radius:8px;font-size:0.8rem;font-weight:600;text-decoration:none;" onmouseover="this.style.background='#e0f2fe'" onmouseout="this.style.background='#f0f9ff'">
                🎭 Gérer les rôles
            </a>
            @endif

            @if(auth()->user()->hasPermission('acces_boutique') || auth()->user()->isSuperAdmin())
            <a href="{{ route('client.boutique') }}" id="quick-shop" target="_blank" style="display:inline-flex;align-items:center;gap:0.5rem;background:#f8fafc;color:#475569;border:1px solid #e2e8f0;padding:0.5rem 1rem;border-radius:8px;font-size:0.8rem;font-weight:600;text-decoration:none;">
                🏪 Accéder à la boutique ↗
            </a>
            @endif
        </div>
    </div>

    {{-- ===== Permissions de ce compte ===== --}}
    <div style="background:white;border-radius:12px;padding:1.5rem;box-shadow:0 1px 3px rgba(0,0,0,0.06);">
        <h3 style="font-size:0.95rem;font-weight:700;color:#0f172a;margin-bottom:1rem;border-bottom:1px solid #f1f5f9;padding-bottom:0.75rem;">
            🔐 Vos permissions ({{ auth()->user()->role?->nom }})
        </h3>
        @php
            $permissions = auth()->user()->role?->permissions ?? collect();
        @endphp
        <div style="display:flex;flex-wrap:wrap;gap:0.5rem;">
            @forelse($permissions as $perm)
                <span style="background:#f1f5f9;color:#475569;font-size:0.72rem;font-weight:500;padding:3px 10px;border-radius:999px;border:1px solid #e2e8f0;">
                    {{ str_replace('_', ' ', $perm->nom) }}
                </span>
            @empty
                <span style="color:#94a3b8;font-size:0.85rem;">Aucune permission attribuée.</span>
            @endforelse
        </div>
    </div>

</x-admin-layout>
</div>
@endvolt
