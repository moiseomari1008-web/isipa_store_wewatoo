# 🚀 GUIDE DE DÉMARRAGE RAPIDE - Espace Admin

## ⚡ 5 Minutes pour Démarrer

### Étape 1: Peupler les Rôles et Permissions (1 min)

```bash
cd c:\xampp2\isipa_store_wewatoo

# Lancer le seeder
php artisan db:seed --class=RolesAndPermissionsSeeder
```

**Résultat:** 
- ✅ 3 rôles créés
- ✅ 12 permissions créées
- ✅ Attributions complètes

---

### Étape 2: Créer un User Admin (2 min)

#### Option A: Utilisez Tinker (Recommandé)

```bash
php artisan tinker
```

```php
# Créer un nouvel utilisateur admin
$user = User::create([
    'nom_complet' => 'Admin Test',
    'email' => 'admin@test.com',
    'password' => bcrypt('password'),
    'telephone' => '77 123 45 67',
    'adresse' => 'Dakar, Sénégal',
]);

# Assigner le rôle Super Admin
$role = Role::where('nom', 'Super Admin')->first();
$user->update(['id_role' => $role->id]);

# Quitter Tinker
exit
```

#### Option B: Modifiez directement en Base de Données

```sql
-- Créer un user
INSERT INTO utilisateurs (nom_complet, email, password, telephone, adresse, created_at, updated_at) 
VALUES ('Admin Test', 'admin@test.com', '$2y$12$...hashed_password...', '77 123 45 67', 'Dakar', NOW(), NOW());

-- Assigner le rôle Super Admin (remplacez l'ID)
UPDATE utilisateurs SET id_role = (SELECT id FROM roles WHERE nom = 'Super Admin') WHERE email = 'admin@test.com';
```

---

### Étape 3: Accéder à l'Admin (1 min)

1. **Ouvrez votre navigateur:**
   ```
   http://localhost:8000
   ```

2. **Allez au login:**
   ```
   Cliquez sur "Se connecter" ou allez directement à /login
   ```

3. **Connectez-vous:**
   ```
   Email: admin@test.com
   Mot de passe: password
   ```

4. **Accédez à l'admin:**
   ```
   http://localhost:8000/admin
   ```

---

## 📊 Une Fois Connecté à l'Admin

### Vous verrez un dashboard avec:
- 📦 Nombre total de produits
- 📋 Nombre total de commandes
- 👥 Nombre total d'utilisateurs
- 💳 Paiements en attente

### Sidebar avec les menus (selon votre rôle):
- ✅ Tableau de bord
- ✅ Produits (si admin articles ou super admin)
- ✅ Catégories (si admin articles ou super admin)
- ✅ Commandes
- ✅ Paiements
- ✅ Livraisons (si admin articles ou super admin)
- ✅ Utilisateurs (si admin utilisateurs ou super admin)

---

## 🧪 Tests des Rôles

### Test 1: Super Admin

```
✅ Connectez-vous avec le Super Admin
✅ Vous devriez voir TOUS les menus
✅ Vous pouvez créer, modifier, supprimer n'importe quoi
```

### Test 2: Admin Articles

```bash
# Créer un Admin Articles
php artisan tinker

$user = User::create([
    'nom_complet' => 'Admin Articles',
    'email' => 'admin.articles@test.com',
    'password' => bcrypt('password'),
]);

$role = Role::where('nom', 'Admin Articles')->first();
$user->update(['id_role' => $role->id]);

exit
```

**Connectez-vous et vérifiez:**
- ✅ Vous voyez: Produits, Catégories, Commandes, Paiements, Livraisons
- ❌ Vous ne voyez PAS: Utilisateurs (403 Forbidden si vous essayez)
- ✅ Vous pouvez créer/modifier les produits

### Test 3: Admin Utilisateurs

```bash
php artisan tinker

$user = User::create([
    'nom_complet' => 'Admin Utilisateurs',
    'email' => 'admin.users@test.com',
    'password' => bcrypt('password'),
]);

$role = Role::where('nom', 'Admin Utilisateurs')->first();
$user->update(['id_role' => $role->id]);

exit
```

**Connectez-vous et vérifiez:**
- ✅ Vous voyez: Utilisateurs, Commandes
- ❌ Vous ne voyez PAS: Produits (403 Forbidden si vous essayez)
- ✅ Vous pouvez créer/modifier les utilisateurs

---

## 🔧 Exemplestestration des Fonctionnalités

### 📦 Tester la Gestion des Produits (Super Admin)

```
1. Allez à /admin/produits
2. Cliquez sur "+ Ajouter un produit"
3. Remplissez le formulaire:
   - Nom: "T-shirt Premium"
   - Description: "Très beau t-shirt 100% coton"
   - Prix: "5000"
   - Stock: "50"
   - Catégorie: Sélectionnez une catégorie
4. Cliquez "Créer le produit"
5. ✅ Vous verrez le message de succès
6. ✅ Le produit apparaîtra dans la liste
7. Cliquez "✏️ Éditer" pour modifier
8. Cliquez "🗑️ Supprimer" pour supprimer
```

### 👥 Tester la Gestion des Utilisateurs (Admin Utilisateurs)

```
1. Allez à /admin/utilisateurs
2. Cliquez sur "+ Ajouter un utilisateur"
3. Remplissez le formulaire:
   - Nom: "Jean Dupont"
   - Email: "jean@test.com"
   - Téléphone: "77 123 45 67"
   - Rôle: Choisir un rôle
4. Cliquez "Créer l'utilisateur"
5. ✅ Nouvel utilisateur créé
```

### 🔒 Tester la Sécurité

```
# Test 1: Admin Articles essaie d'accéder à /admin/utilisateurs
Result: 403 Forbidden ✅

# Test 2: Admin Utilisateurs essaie d'accéder à /admin/produits/creer
Result: 403 Forbidden ✅

# Test 3: Utilisateur normal essaie d'accéder à /admin
Result: 403 Forbidden ✅

# Test 4: Non-connecté essaie d'accéder à /admin
Result: Redirect to /login ✅
```

---

## 📝 Checklist de Configuration

- [ ] Seeder exécuté
- [ ] Un user admin créé
- [ ] Connexion réussie
- [ ] Dashboard visible
- [ ] Menu navigation fonctionnel
- [ ] Au moins une création testée
- [ ] Test de sécurité (accès refusé)

---

## 🚨 Problèmes Courants

### "Accès refusé" quand j'accède à /admin

**Solution:**
```bash
# Vérifiez que votre user a un rôle assigné
php artisan tinker

$user = User::find(1);
dd($user->role);  # Devrait afficher le rôle, pas null
```

### "Le seeder n'a pas fonctionné"

**Solution:**
```bash
# Vérifiez les erreurs
php artisan db:seed --class=RolesAndPermissionsSeeder

# Si erreur "class not found":
composer dump-autoload
php artisan db:seed --class=RolesAndPermissionsSeeder
```

### "Je ne vois pas les menus"

**Solution:**
```bash
# Vérifiez les permissions du rôle
php artisan tinker

$role = Role::find(1);
$role->permissions;  # Devrait afficher les permissions
```

### "Formulaire ne soumet pas"

**Solution:**
- Vérifiez que Livewire est bien chargé (`@livewireScripts`)
- Vérifiez les erreurs dans la console du navigateur (F12)
- Vérifiez `storage/logs/laravel.log`

---

## 📞 Support Supplémentaire

Consultez:
- **[ADMIN_DOCUMENTATION.md](ADMIN_DOCUMENTATION.md)** - Documentation complète
- **[ADMIN_IMPLEMENTATION_SUMMARY.md](ADMIN_IMPLEMENTATION_SUMMARY.md)** - Détails techniques

---

## 🎉 Prêt à Commencer?

```bash
# 1. Lancer le seeder
php artisan db:seed --class=RolesAndPermissionsSeeder

# 2. Créer un admin via tinker
php artisan tinker

# 3. Ouvrir le navigateur
http://localhost:8000/admin

# 4. Se connecter avec vos identifiants
```

**Bienvenue sur l'espace administrateurs!** 🚀
