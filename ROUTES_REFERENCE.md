# 🗺️ Référence Complète des Routes Admin

## 📋 Toutes les Routes Admin

### Convention
```
/admin/{section}/{action}
```

Toutes les routes sont protégées par:
- `auth` - Connecté
- `admin.access` - Est administrateur
- `permission:*` - A la permission spécifique (le cas échéant)

---

## 🏠 Dashboard

| Route | Méthode | Permission | Description |
|-------|---------|-----------|-------------|
| `/admin` | GET | - | Tableau de bord principal |

**Contient:**
- Statistiques (produits, commandes, utilisateurs, paiements)
- Dernières commandes
- Menu de navigation

---

## 📦 Produits

| Route | Méthode | Permission | Description |
|-------|---------|-----------|-------------|
| `/admin/produits` | GET | - | Liste tous les produits |
| `/admin/produits/creer` | GET | gerer_produits | Form création |
| `/admin/produits/{id}` | POST | gerer_produits | Crée un produit |
| `/admin/produits/{id}/editer` | GET | gerer_produits | Form édition |
| `/admin/produits/{id}` | PUT | gerer_produits | Met à jour |
| `/admin/produits/{id}` | DELETE | gerer_produits | Supprime |

**Fonctionnalités:**
- ✅ Recherche live
- ✅ Pagination
- ✅ Création avec validation
- ✅ Édition avec préchargement
- ✅ Suppression avec confirmation

**Exemple implémenté:** COMPLET avec Livewire Volt

---

## 🏷️ Catégories

| Route | Méthode | Permission | Description |
|-------|---------|-----------|-------------|
| `/admin/categories` | GET | - | Liste toutes les catégories |
| `/admin/categories/creer` | GET | gerer_categories | Form création |
| `/admin/categories/{id}` | POST | gerer_categories | Crée une catégorie |
| `/admin/categories/{id}/editer` | GET | gerer_categories | Form édition |
| `/admin/categories/{id}` | PUT | gerer_categories | Met à jour |
| `/admin/categories/{id}` | DELETE | gerer_categories | Supprime |

**À implémenter:** Suivre exemple produits

---

## 📋 Commandes

| Route | Méthode | Permission | Description |
|-------|---------|-----------|-------------|
| `/admin/commandes` | GET | voir_commandes | Liste toutes les commandes |
| `/admin/commandes/{id}` | GET | voir_commandes | Détails d'une commande |
| `/admin/commandes/{id}/statut` | PUT | voir_commandes | Change le statut |

**Fonctionnalités:**
- ✅ Recherche
- ✅ Filtrage par statut
- ✅ Vue détails

**À implémenter:** Logique métier

---

## 💳 Paiements

| Route | Méthode | Permission | Description |
|-------|---------|-----------|-------------|
| `/admin/paiements` | GET | valider_paiements | Liste tous les paiements |
| `/admin/paiements/{id}/valider` | POST | valider_paiements | Valide un paiement |
| `/admin/paiements/{id}/rejeter` | POST | valider_paiements | Rejette un paiement |

**Fonctionnalités:**
- ✅ Filtrage par statut (en attente, validé, rejeté)
- ✅ Actions rapides

**À implémenter:** Logique métier

---

## 🚚 Livraisons

| Route | Méthode | Permission | Description |
|-------|---------|-----------|-------------|
| `/admin/livraisons` | GET | gerer_livraisons | Liste toutes les livraisons |
| `/admin/livraisons/{id}` | GET | gerer_livraisons | Détails livraison |
| `/admin/livraisons/{id}/statut` | PUT | gerer_livraisons | Change le statut |

**Statuts disponibles:**
- En préparation
- En transit
- Livrée
- Retournée

**À implémenter:** Logique métier

---

## 👥 Utilisateurs

| Route | Méthode | Permission | Description |
|-------|---------|-----------|-------------|
| `/admin/utilisateurs` | GET | gerer_utilisateurs | Liste tous les utilisateurs |
| `/admin/utilisateurs/creer` | GET | gerer_utilisateurs | Form création |
| `/admin/utilisateurs/{id}` | POST | gerer_utilisateurs | Crée un utilisateur |
| `/admin/utilisateurs/{id}/editer` | GET | gerer_utilisateurs | Form édition |
| `/admin/utilisateurs/{id}` | PUT | gerer_utilisateurs | Met à jour |
| `/admin/utilisateurs/{id}` | DELETE | gerer_utilisateurs | Supprime |
| `/admin/utilisateurs/{id}/role` | PUT | attribuer_roles | Change le rôle |

**Fonctionnalités:**
- ✅ Recherche
- ✅ Filtrage par rôle
- ✅ CRUD complet
- ✅ Attribution de rôles

**À implémenter:** Logique métier CRUD

---

## 🔐 Accès par Rôle

### Super Admin (✅ Accès à tout)
```
✅ /admin
✅ /admin/produits/*
✅ /admin/categories/*
✅ /admin/commandes/*
✅ /admin/paiements/*
✅ /admin/livraisons/*
✅ /admin/utilisateurs/*
```

### Admin Articles (✅ Accès partiel)
```
✅ /admin
✅ /admin/produits/*
✅ /admin/categories/*
✅ /admin/commandes
✅ /admin/paiements/*
✅ /admin/livraisons/*
❌ /admin/utilisateurs/* → 403 Forbidden
```

### Admin Utilisateurs (✅ Accès partiel)
```
✅ /admin
✅ /admin/utilisateurs/*
✅ /admin/commandes
❌ /admin/produits/* → 403 Forbidden
❌ /admin/paiements/* → 403 Forbidden
❌ /admin/livraisons/* → 403 Forbidden
```

---

## 🧪 Tester les Routes

### Tester avec cURL
```bash
# Test avec authentification
curl -X GET "http://localhost:8000/admin/produits" \
  -H "Cookie: LARAVEL_SESSION=..." \
  -H "X-CSRF-TOKEN: ..."

# Test sans authentification (devrait rediriger)
curl -X GET "http://localhost:8000/admin"
→ 302 Redirect to /login
```

### Tester avec Postman
```
1. GET /admin → Connectez-vous d'abord
2. Login: POST /login
3. Réessayez GET /admin → Devrait fonctionner
4. Test GET /admin/utilisateurs (en tant qu'Admin Articles)
   → 403 Forbidden ✅
```

### Tester dans le Navigateur
```
1. Connectez-vous: /login
2. Allez à: http://localhost:8000/admin
3. Testez les liens du menu
4. Testez les formulaires
5. Testez les actions (créer, éditer, supprimer)
```

---

## 📊 Tableau Récapitulatif

```
┌─────────────────────────────────────────────────────────────┐
│              RÉSUMÉ DES ROUTES ADMIN                        │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│  Dashboard              1 route     gerer_tableau_de_bord  │
│  Produits             6+ routes     gerer_produits         │
│  Catégories           6+ routes     gerer_categories       │
│  Commandes            3+ routes     voir_commandes         │
│  Paiements            3+ routes     valider_paiements      │
│  Livraisons           3+ routes     gerer_livraisons       │
│  Utilisateurs         7+ routes     gerer_utilisateurs     │
│                       ─────────                            │
│  TOTAL               ~28+ routes                           │
│                                                             │
├─────────────────────────────────────────────────────────────┤
│ Toutes les routes sont RESTful et suivent Laravel standards │
└─────────────────────────────────────────────────────────────┘
```

---

## 🔄 Flux Recommandé pour le Développement

### Pour Implémenter une Nouvelle Section

1. **Créer le Livewire Component:**
   ```
   resources/views/livewire/admin/{section}/index.blade.php
   ```

2. **Suivre le Pattern Produits:**
   ```php
   - Déclarer les properties
   - Implémenter les #[Computed]
   - Ajouter les fonctions (store, edit, update, delete)
   - Ajouter les validations (rules)
   - Créer les formulaires
   ```

3. **Tester:**
   ```
   - Accès à la liste
   - Création
   - Édition
   - Suppression
   - Vérifier les permissions
   ```

---

## 📝 Notes Importantes

### Convention d'URL
```
/admin/resource/action
/admin/produits/creer      ← GET (affiche form)
/admin/produits            ← POST (traite form)
/admin/produits/{id}/editer ← GET (affiche form)
/admin/produits/{id}       ← PUT (traite)
```

### Middlewares Appliqués
```
auth             → Utilisateur connecté requis
admin.access     → Doit être administrateur
permission:*     → Doit avoir la permission spécifique
```

### Pagination
```
Toutes les listes utilisent pagination Laravel
Par défaut: 10-15 items par page
```

### Validation
```
Côté serveur avec Livewire rules()
Affichage d'erreurs automatique
Messages flash pour succès/erreur
```

---

## 🎯 Checklist Implémentation

Pour implémenter une section complète:

```
Section: Produits ✅ DONE
□ Models et relations OK
□ Routes créées OK
□ Middleware permissions OK
□ Livewire component créé OK
□ Formulaires validés OK
□ CRUD complet OK
□ Tests passés OK

Section: Catégories ⏳ À faire
□ Models et relations
□ Routes créées
□ Middleware permissions
□ Livewire component
□ Formulaires validés
□ CRUD complet
□ Tests

Section: Utilisateurs ⏳ À faire
□ Models et relations (OK)
□ Routes créées (OK)
□ Middleware permissions (OK)
□ Livewire component
□ Formulaires validés
□ CRUD complet
□ Tests
```

---

**Référence complète des routes créée!** 🗺️
