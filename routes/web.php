<?php

use App\Http\Controllers\BoutiqueController;
use App\Http\Controllers\ClientController;
use Illuminate\Support\Facades\Route;

// ============================================================
//  ROUTES VISITEURS (accès public)
// ============================================================
Route::get('/', [BoutiqueController::class, 'accueil'])->name('home');
Route::get('/catalogue', [BoutiqueController::class, 'catalogue'])->name('catalogue');
Route::get('/produits/{produit}', [BoutiqueController::class, 'show'])->name('boutique.produits.show');

// ============================================================
//  INSCRIPTION ADMIN (route dédiée, hors middleware admin)
// ============================================================
Route::middleware(['guest'])->group(function () {
    \Livewire\Volt\Volt::route('/admin/register', 'auth.admin-register')->name('admin.register');
});

// ============================================================
//  ROUTES CLIENTS (auth requis)
// ============================================================
Route::middleware(['auth'])->group(function () {
    Route::get('boutique', [ClientController::class, 'boutique'])->name('client.boutique');
    Route::get('panier', [ClientController::class, 'panier'])->name('panier.index');
    Route::post('panier/ajouter/{produit}', [ClientController::class, 'ajouterAuPanier'])->name('panier.add');
    Route::patch('panier/{produitsPanier}', [ClientController::class, 'mettreAJourPanier'])->name('panier.update');
    Route::delete('panier/{produitsPanier}', [ClientController::class, 'supprimerDuPanier'])->name('panier.remove');
    Route::get('checkout', [ClientController::class, 'checkout'])->name('client.checkout');
    Route::post('commandes-client', [ClientController::class, 'passerCommande'])->name('client.commandes.store');
    Route::get('mes-commandes', [ClientController::class, 'commandes'])->name('client.commandes.index');
    Route::get('mes-commandes/{commande}', [ClientController::class, 'showCommande'])->name('client.commandes.show');
    Route::post('mes-commandes/{commande}/annuler', [ClientController::class, 'annulerCommande'])->name('client.commandes.cancel');
});

// ============================================================
//  ROUTES ADMINISTRATEURS — préfixe /admin
// ============================================================
Route::middleware(['auth', 'admin.access'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // ── Tableau de bord ──────────────────────────────────
        \Livewire\Volt\Volt::route('/', 'admin.dashboard')->name('dashboard');

        // ── Produits ─────────────────────────────────────────
        \Livewire\Volt\Volt::route('/produits', 'admin.produits.index')->name('produits.index');
        \Livewire\Volt\Volt::route('/produits/creer', 'admin.produits.create')->middleware('permission:gerer_produits')->name('produits.create');
        \Livewire\Volt\Volt::route('/produits/{produit}/editer', 'admin.produits.edit')->middleware('permission:gerer_produits')->name('produits.edit');

        // ── Catégories ───────────────────────────────────────
        \Livewire\Volt\Volt::route('/categories', 'admin.categories.index')->middleware('permission:gerer_categories')->name('categories.index');
        \Livewire\Volt\Volt::route('/categories/creer', 'admin.categories.create')->middleware('permission:gerer_categories')->name('categories.create');
        \Livewire\Volt\Volt::route('/categories/{categorie}/editer', 'admin.categories.edit')->middleware('permission:gerer_categories')->name('categories.edit');

        // ── Commandes ────────────────────────────────────────
        \Livewire\Volt\Volt::route('/commandes', 'admin.commandes.index')->middleware('permission:voir_commandes')->name('commandes.index');
        \Livewire\Volt\Volt::route('/commandes/{commande}', 'admin.commandes.show')->middleware('permission:voir_commandes')->name('commandes.show');

        // ── Paiements ────────────────────────────────────────
        \Livewire\Volt\Volt::route('/paiements', 'admin.paiements.index')->middleware('permission:valider_paiements')->name('paiements.index');

        // ── Livraisons ───────────────────────────────────────
        \Livewire\Volt\Volt::route('/livraisons', 'admin.livraisons.index')->middleware('permission:gerer_livraisons')->name('livraisons.index');

        // ── Utilisateurs ─────────────────────────────────────
        \Livewire\Volt\Volt::route('/utilisateurs', 'admin.utilisateurs.index')->middleware('permission:gerer_utilisateurs')->name('utilisateurs.index');
        \Livewire\Volt\Volt::route('/utilisateurs/creer', 'admin.utilisateurs.create')->middleware('permission:gerer_utilisateurs')->name('utilisateurs.create');
        \Livewire\Volt\Volt::route('/utilisateurs/{user}/editer', 'admin.utilisateurs.edit')->middleware('permission:gerer_utilisateurs')->name('utilisateurs.edit');

        // ── Rôles & Permissions ──────────────────────────────
        \Livewire\Volt\Volt::route('/roles', 'admin.roles.index')->middleware('permission:attribuer_roles')->name('roles.index');

        // ── Paramètres du site ───────────────────────────────
        \Livewire\Volt\Volt::route('/parametres', 'admin.parametres.index')->middleware('permission:parametres_site')->name('parametres.index');

    });

require __DIR__.'/auth.php';
