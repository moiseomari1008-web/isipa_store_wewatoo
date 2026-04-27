# 🏗️ Architecture Complète de l'Espace Administrateurs

## 📐 Vue d'Ensemble du Système

```
┌─────────────────────────────────────────────────────────────────┐
│                     UTILISATEUR CONNECTÉ                        │
│                      (auth()->user())                           │
└────────────────────────┬────────────────────────────────────────┘
                         │
                    Accès à /admin
                         │
         ┌───────────────┴───────────────┐
         │                               │
    ✅ Admin                         ❌ Non Admin
         │                               │
     Affiche Dashboard           Redirect to /
                                     ou 403
```

---

## 🔐 Architecture de Sécurité

```
REQUEST → /admin/produits
   │
   ├─ auth Middleware ────────────┐
   │   (Connecté?)               │
   │   ✅ Oui → Continuer        │
   │   ❌ Non → /login           │
   │                             │
   └─ admin.access Middleware ───┴──┐
       (Est Admin?)                 │
       ✅ Oui → Continuer          │
       ❌ Non → 403 Forbidden       │
                                   │
           └─ permission Middleware──┐
               (A la permission?)    │
               ✅ Oui → Afficher    │
               ❌ Non → 403 Forbidden
```

---

## 👥 Hiérarchie des Rôles et Permissions

```
┌────────────────────────────────────────────────────────────────┐
│                        SYSTÈME DE RÔLES                        │
└────────────────────────────────────────────────────────────────┘

                          PERMISSIONS (12)
        ┌──────────────────────┬──────────────────────┐
        │                      │                      │
    PRODUITS             UTILISATEURS           COMMANDES
    (4 perms)            (3 perms)             (3 perms)
    ├─ gerer              ├─ gerer              ├─ voir
    ├─ publication        ├─ attribuer          ├─ valider
    ├─ categories         └─ dashboard          └─ livraison
    └─ dashboard
                          GLOBAL (2 perms)
                          ├─ parametres
                          ├─ acces_boutique
                          └─ passer_commande

        │                      │                      │
        └──────────┬───────────┼──────────┬───────────┘
                   │           │          │
            ┌──────▼──┐   ┌────▼──┐  ┌───▼─────┐
            │  RÔLES  │   │ (3)   │  │RELATIONS│
            └────┬────┘   └───────┘  └─────────┘
                 │
     ┌───────────┼───────────┐
     │           │           │
  SUPER    ADMIN ARTICLES  ADMIN
  ADMIN    (9/12)           USERS
  (12/12)                   (6/12)


┌─ SUPER ADMIN ──────────────────────────────────────┐
│ • voir_tableau_de_bord                             │
│ • gerer_produits              ← Admin Articles      │
│ • gerer_categories            ← Admin Articles      │
│ • voir_commandes              ← Both                │
│ • valider_paiements           ← Admin Articles      │
│ • gerer_livraisons            ← Admin Articles      │
│ • gerer_utilisateurs          ← Admin Users        │
│ • attribuer_roles             ← Admin Users        │
│ • publication_produits        ← Admin Articles      │
│ • parametres_site                                 │
│ • acces_boutique                                   │
│ • passer_commande                                  │
└────────────────────────────────────────────────────┘

┌─ ADMIN ARTICLES ────────────────────────────────────┐
│ • voir_tableau_de_bord                             │
│ • gerer_produits                                   │
│ • gerer_categories                                 │
│ • voir_commandes                                   │
│ • valider_paiements                                │
│ • gerer_livraisons                                 │
│ • publication_produits                             │
│ • acces_boutique                                   │
│ • passer_commande                                  │
└────────────────────────────────────────────────────┘

┌─ ADMIN UTILISATEURS ───────────────────────────────┐
│ • voir_tableau_de_bord                             │
│ • gerer_utilisateurs                               │
│ • attribuer_roles                                  │
│ • voir_commandes                                   │
│ • acces_boutique                                   │
│ • passer_commande                                  │
└────────────────────────────────────────────────────┘
```

---

## 📂 Structure des Fichiers

```
isipa_store_wewatoo/
│
├─ app/
│  ├─ Http/Middleware/
│  │  ├─ EnsureAdminAccess.php      ← Vérifie si admin
│  │  └─ EnsurePermission.php        ← Vérifie les permissions
│  │
│  └─ Models/
│     ├─ User.php                    ← hasPermission(), isAdmin()...
│     ├─ Role.php
│     ├─ Permission.php
│     └─ Attribution.php
│
├─ database/
│  └─ seeders/
│     └─ RolesAndPermissionsSeeder.php ← Crée les rôles/perms
│
├─ routes/
│  └─ web.php                        ← Routes /admin
│
├─ resources/views/
│  ├─ components/
│  │  └─ admin-layout.blade.php      ← Layout principal
│  │
│  └─ livewire/admin/
│     ├─ dashboard.blade.php
│     ├─ produits/
│     │  ├─ index.blade.php          ← List + CRUD
│     │  ├─ create.blade.php
│     │  └─ edit.blade.php
│     ├─ categories/
│     │  ├─ index.blade.php
│     │  ├─ create.blade.php
│     │  └─ edit.blade.php
│     ├─ commandes/
│     │  ├─ index.blade.php
│     │  └─ show.blade.php
│     ├─ paiements/
│     │  └─ index.blade.php
│     ├─ livraisons/
│     │  └─ index.blade.php
│     └─ utilisateurs/
│        ├─ index.blade.php
│        ├─ create.blade.php
│        └─ edit.blade.php
│
├─ bootstrap/
│  └─ app.php                        ← Middlewares registrés
│
├─ ADMIN_DOCUMENTATION.md            ← Doc complète
├─ ADMIN_IMPLEMENTATION_SUMMARY.md   ← Résumé technique
└─ QUICK_START.md                    ← Guide démarrage
```

---

## 🚀 Flux de Requête Complet

```
USER VISITS: http://localhost:8000/admin/produits
    │
    ├─ Route: GET /admin/produits
    │
    ├─ Middleware Stack:
    │  ├─ auth              (Connecté?)
    │  ├─ admin.access      (Est Admin?)
    │  └─ permission        (A gerer_produits?)
    │
    ├─ View Loaded: livewire/admin/produits/index.blade.php
    │
    ├─ Livewire Component Init:
    │  ├─ #[Computed] produits()
    │  │  └─ Produit::where('nom', 'like', "%{$search}%")->paginate(10)
    │  │
    │  └─ #[Computed] categories()
    │     └─ CategorieProduit::all()
    │
    └─ Layout Rendered: admin-layout.blade.php
       ├─ Sidebar (menus selon permissions)
       ├─ Header
       └─ Content Area (produits/index.blade.php)
```

---

## 🔄 Cycle de Vie d'une Action CRUD

### CREATE (Créer un produit)

```
┌─ User clicks "+ Ajouter un produit"
│
├─ wire:click="$set('mode', 'create')"
│  └─ Component switch mode to 'create'
│
├─ Form Rendered (create form visible)
│
├─ User fills: nom, description, prix, stock, categorie_id
│
├─ User clicks "Créer le produit"
│  └─ wire:submit="store"
│
├─ Validation
│  ├─ nom: required|string|max:255|min:3
│  ├─ description: required|string|min:10
│  ├─ prix: required|numeric|min:0
│  ├─ stock: required|integer|min:0
│  └─ categorie_id: required|exists:categorie_produits,id
│
├─ If Valid:
│  ├─ Produit::create([...])
│  ├─ session()->flash('success', '...')
│  ├─ resetForm()
│  └─ mode = 'list'
│
└─ UI Updated (modal disappears, list refreshed)
```

### READ (Afficher la liste)

```
$this->produits → @forelse loop → <table> rows
```

### UPDATE (Modifier)

```
wire:click="edit({{ $produit->id }})"
├─ Load produit data
├─ mode = 'edit'
└─ Form re-rendered with values

wire:submit="update"
├─ Validate
├─ $produit->update([...])
├─ flash success
└─ Back to list
```

### DELETE (Supprimer)

```
wire:click="delete({{ $produit->id }})"
├─ onclick="return confirm('Êtes-vous sûr?')"
├─ Produit::findOrFail($id)->delete()
├─ flash success
└─ Table refreshed automatically
```

---

## 🛡️ Couches de Sécurité

```
              REQUÊTE
                │
        ┌───────┴───────┐
        │               │
    COUCHE 1        COUCHE 2
   (Routes)       (Middleware)
        │               │
   Routes protégées   ├─ auth
   /admin/*           ├─ admin.access
                      └─ permission:*
        │               │
        └───────┬───────┘
                │
        ┌───────┴────────┐
        │                │
    COUCHE 3         COUCHE 4
  (Contrôle)      (Affichage)
        │                │
   Permissions    @if(auth()->user()
   dans models      ->hasPermission())
                       │
                   Blade conditionals
                   pour UI
```

---

## 📊 Diagramme de Base de Données

```
UTILISATEURS
├─ id
├─ nom_complet
├─ email
├─ password
├─ id_role (FK)
├─ telephone
├─ adresse
└─ timestamps

ROLES
├─ id
├─ nom (UNIQUE)
│  ├─ "Super Admin"
│  ├─ "Admin Articles"
│  └─ "Admin Utilisateurs"
├─ description
└─ timestamps

PERMISSIONS
├─ id
├─ nom (UNIQUE)
│  ├─ "voir_tableau_de_bord"
│  ├─ "gerer_produits"
│  ├─ "gerer_categories"
│  ├─ ... (12 total)
├─ description
└─ timestamps

ATTRIBUTIONS (Junction Table)
├─ id
├─ id_role (FK)
├─ id_permission (FK)
└─ timestamps
```

---

## 🎯 Carte des Permissions par Section

```
┌─ PRODUITS ────────────────────────────┐
│ • voir_tableau_de_bord (Dashboard)    │
│ • gerer_produits (CRUD)               │
│ • gerer_categories (CRUD)             │
│ • publication_produits (Publish)      │
│                                       │
│ Rôles: Super Admin, Admin Articles   │
└───────────────────────────────────────┘

┌─ UTILISATEURS ─────────────────────────┐
│ • voir_tableau_de_bord (Dashboard)    │
│ • gerer_utilisateurs (CRUD)           │
│ • attribuer_roles (Assign/Modify)     │
│                                       │
│ Rôles: Super Admin, Admin Utilisateurs│
└────────────────────────────────────────┘

┌─ COMMANDES & PAIEMENTS ────────────────┐
│ • voir_commandes (View)               │
│ • valider_paiements (Validate)        │
│ • gerer_livraisons (Manage)           │
│                                       │
│ Rôles: Super Admin, Admin Articles    │
│        (Tous les admins voient)      │
└────────────────────────────────────────┘

┌─ GLOBAL ───────────────────────────────┐
│ • parametres_site (Settings)          │
│ • acces_boutique (Shop Access)        │
│ • passer_commande (Place Orders)      │
│                                       │
│ Rôles: Tous les admins               │
└────────────────────────────────────────┘
```

---

## ✨ Décision de Design

### Pourquoi 3 Rôles?
```
┌─ Super Admin
│  └─ Gestion complète, idéal pour propriétaire
│
├─ Admin Articles
│  └─ Autonome pour gestion produits/commandes
│
└─ Admin Utilisateurs
   └─ Gestion RH séparée
```

### Pourquoi Permissions Séparées?
```
Éviter "AdminAll/AdminNone"
→ Chaque rôle peut être personnalisé
→ Sécurité granulaire
→ Évolutif si nouveau rôle ajouté
```

### Pourquoi Livewire Volt?
```
✅ Composants réactifs
✅ Validation côté serveur
✅ CRUD sans javascript complexe
✅ État préservé automatique
✅ Sécurité CSRF automatique
```

---

**Architecture prête pour production!** 🚀
