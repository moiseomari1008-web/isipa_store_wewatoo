# 📊 Documentation - Espace Administrateurs ISIPA Store

## Vue d'ensemble

L'espace administrateurs est complètement implémenté selon le MVP avec 3 rôles distincts et un système de permissions granulaire.

## 🔐 Rôles et Permissions

### 1. **Super Admin**
Accès complet à toutes les fonctionnalités:
- Tableau de bord
- Gestion des produits (créer, modifier, supprimer)
- Gestion des catégories
- Voir les commandes
- Valider les paiements
- Gestion des livraisons
- Gestion des utilisateurs
- Attribuer les rôles
- Publication des produits
- Paramètres du site
- Accès à la boutique
- Passer des commandes

### 2. **Admin Articles**
Accès restreint à la gestion du contenu:
- ✅ Tableau de bord
- ✅ Gérer les produits
- ✅ Gérer les catégories
- ✅ Voir les commandes
- ✅ Valider les paiements
- ✅ Gérer les livraisons
- ✅ Publication des produits
- ✅ Accès à la boutique
- ✅ Passer des commandes
- ❌ Pas d'accès à la gestion des utilisateurs

### 3. **Admin Utilisateurs**
Accès restreint à la gestion des utilisateurs:
- ✅ Tableau de bord
- ✅ Gérer les utilisateurs
- ✅ Attribuer les rôles
- ✅ Voir les commandes
- ✅ Accès à la boutique
- ✅ Passer des commandes
- ❌ Pas d'accès à la gestion des produits

## 🚀 Installation et Configuration

### 1. Lancer le Seeder

```bash
php artisan db:seed --class=RolesAndPermissionsSeeder
```

Cela créera:
- ✅ Tous les 12 permissions
- ✅ Les 3 rôles (Super Admin, Admin Articles, Admin Utilisateurs)
- ✅ Les attributions de permissions aux rôles

### 2. Assigner un Rôle Admin à un Utilisateur

Vous avez plusieurs options:

**Option A: Via Tinker**
```bash
php artisan tinker
```

```php
$user = User::find(1);  // ou l'ID de votre user
$role = Role::where('nom', 'Super Admin')->first();
$user->update(['id_role' => $role->id]);
```

**Option B: Modification directe en base**
```sql
UPDATE utilisateurs SET id_role = (SELECT id FROM roles WHERE nom = 'Super Admin') WHERE id = 1;
```

## 🗺️ Routes Admin

Tous les routes commencent par `/admin` et nécessitent d'être authentifié et administrateur.

```
/admin                              → Tableau de bord
/admin/produits                     → Liste des produits
/admin/produits/creer              → Créer un produit
/admin/produits/{id}/editer        → Éditer un produit
/admin/categories                   → Liste des catégories
/admin/categories/creer            → Créer une catégorie
/admin/categories/{id}/editer      → Éditer une catégorie
/admin/commandes                    → Liste des commandes
/admin/commandes/{id}              → Détails d'une commande
/admin/paiements                    → Validation des paiements
/admin/livraisons                   → Gestion des livraisons
/admin/utilisateurs                 → Liste des utilisateurs
/admin/utilisateurs/creer          → Créer un utilisateur
/admin/utilisateurs/{id}/editer    → Éditer un utilisateur
```

## 🔒 Sécurité

### Middlewares Implémentés

1. **`admin.access`** - Vérifie que l'utilisateur est un administrateur
   ```php
   Route::middleware(['auth', 'admin.access'])->group(...)
   ```

2. **`permission:{permission_name}`** - Vérifie une permission spécifique
   ```php
   Route::get('/produits/creer', ...)->middleware('permission:gerer_produits')
   ```

### Méthodes Disponibles dans le User Model

```php
auth()->user()->hasPermission('gerer_produits')     // Vérifie une permission
auth()->user()->isAdmin()                            // Est un admin
auth()->user()->isSuperAdmin()                       // Est super admin
auth()->user()->role->permissions                    // Obtient toutes les permissions
```

### Utilisation dans les Vues Blade

```blade
@if(auth()->user()->hasPermission('gerer_produits'))
    <a href="{{ route('admin.produits.create') }}">Ajouter un produit</a>
@endif

@if(auth()->user()->isSuperAdmin())
    <!-- Contenu réservé au Super Admin -->
@endif
```

## 📁 Structure des Fichiers

```
app/
├── Http/
│   └── Middleware/
│       ├── EnsureAdminAccess.php
│       └── EnsurePermission.php
├── Models/
│   ├── User.php (+ méthodes helpers)
│   ├── Role.php
│   ├── Permission.php
│   └── Attribution.php
└── Livewire/
    └── Actions/

database/
├── seeders/
│   └── RolesAndPermissionsSeeder.php
└── migrations/
    └── (rôles, permissions, attributions - existantes)

resources/
└── views/
    ├── components/
    │   └── admin-layout.blade.php
    └── livewire/
        └── admin/
            ├── dashboard.blade.php
            ├── produits/
            │   ├── index.blade.php
            │   ├── create.blade.php
            │   └── edit.blade.php
            ├── categories/
            │   ├── index.blade.php
            │   ├── create.blade.php
            │   └── edit.blade.php
            ├── commandes/
            │   ├── index.blade.php
            │   └── show.blade.php
            ├── paiements/
            │   └── index.blade.php
            ├── livraisons/
            │   └── index.blade.php
            └── utilisateurs/
                ├── index.blade.php
                ├── create.blade.php
                └── edit.blade.php
```

## 🎯 Prochaines Étapes

### Phase 2: Intégration des Données

1. **Connecter les composants Livewire aux modèles**
   - Récupérer les vrais produits, catégories, commandes, etc.
   - Implémenter la pagination, recherche, filtres

2. **Implémenter les Actions CRUD**
   ```php
   // Créer une action Livewire pour chaque fonction
   // app/Livewire/Actions/CreateProduit.php
   // app/Livewire/Actions/UpdateProduit.php
   ```

3. **Ajouter les validations complètes**
   ```php
   public function rules()
   {
       return [
           'nom' => 'required|string|max:255',
           'prix' => 'required|numeric|min:0',
           'stock' => 'required|integer|min:0',
       ];
   }
   ```

4. **Tester les permissions**
   - Vérifier que Admin Articles ne peut pas créer d'utilisateurs
   - Vérifier que Admin Utilisateurs ne peut pas créer de produits

### Phase 3: Améliorations UI

1. Ajouter des messages flash de succès/erreur
2. Ajouter des confirmations de suppression
3. Ajouter des icônes et animations
4. Implémenter des graphiques dans le dashboard
5. Ajouter la pagination et recherche

## 📝 Exemple de Test

```bash
# 1. Aller sur /admin
http://localhost:8000/admin

# 2. Si non connecté, vous serez redirigé vers /login

# 3. Après connexion en tant qu'admin:
# - Super Admin voit tous les menus
# - Admin Articles voit: Produits, Catégories, Commandes, Paiements, Livraisons
# - Admin Utilisateurs voit: Utilisateurs, Commandes

# 4. Cliquer sur un menu affiche la page correspondante
```

## 🆘 Dépannage

**Erreur "Access Denied":**
- Vérifiez que votre user a un rôle assigné
- Vérifiez que le rôle a les permissions appropriées

**Menus n'apparaissent pas:**
- Vérifiez les permissions avec: `auth()->user()->role->permissions`
- Vérifiez l'ordre des middlewares dans les routes

**Base de données non peuplée:**
- Avez-vous lancé le seeder?
- Avez-vous des erreurs lors du seeding?

## 📚 Ressources Supplémentaires

- [Laravel Policies](https://laravel.com/docs/11.x/authorization)
- [Livewire Documentation](https://livewire.laravel.com/)
- [Tailwind CSS](https://tailwindcss.com/)
