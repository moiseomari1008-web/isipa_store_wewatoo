<?php

namespace Database\Seeders;

use App\Models\Attribution;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer les permissions
        $permissions = [
            ['nom' => 'voir_tableau_de_bord', 'description' => 'Voir le tableau de bord administrateur'],
            ['nom' => 'gerer_produits', 'description' => 'Ajouter, modifier, supprimer des produits'],
            ['nom' => 'gerer_categories', 'description' => 'Ajouter, modifier, supprimer des catégories'],
            ['nom' => 'voir_commandes', 'description' => 'Voir les commandes clients'],
            ['nom' => 'valider_paiements', 'description' => 'Valider les paiements'],
            ['nom' => 'gerer_livraisons', 'description' => 'Gérer les livraisons'],
            ['nom' => 'gerer_utilisateurs', 'description' => 'Ajouter, modifier, supprimer des utilisateurs'],
            ['nom' => 'attribuer_roles', 'description' => 'Attribuer et modifier les rôles des utilisateurs'],
            ['nom' => 'publication_produits', 'description' => 'Publier/dépublier les produits'],
            ['nom' => 'parametres_site', 'description' => 'Accès aux paramètres du site'],
            ['nom' => 'acces_boutique', 'description' => 'Accès à la boutique'],
            ['nom' => 'passer_commande', 'description' => 'Passer des commandes en tant qu\'administrateur'],
        ];

        $createdPermissions = [];
        foreach ($permissions as $permission) {
            $createdPermissions[$permission['nom']] = Permission::create($permission);
        }

        // Créer les rôles
        $superAdmin = Role::create([
            'nom' => 'Super Admin',
            'description' => 'Administrateur avec tous les droits',
        ]);

        $adminArticles = Role::create([
            'nom' => 'Admin Articles',
            'description' => 'Administrateur des articles et catégories',
        ]);

        $adminUtilisateurs = Role::create([
            'nom' => 'Admin Utilisateurs',
            'description' => 'Administrateur des utilisateurs',
        ]);

        // Attribuer les permissions au Super Admin (toutes)
        $superAdminPermissions = [
            'voir_tableau_de_bord',
            'gerer_produits',
            'gerer_categories',
            'voir_commandes',
            'valider_paiements',
            'gerer_livraisons',
            'gerer_utilisateurs',
            'attribuer_roles',
            'publication_produits',
            'parametres_site',
            'acces_boutique',
            'passer_commande',
        ];

        foreach ($superAdminPermissions as $permissionNom) {
            Attribution::create([
                'id_role' => $superAdmin->id,
                'id_permission' => $createdPermissions[$permissionNom]->id,
            ]);
        }

        // Attribuer les permissions à Admin Articles
        $adminArticlesPermissions = [
            'voir_tableau_de_bord',
            'gerer_produits',
            'gerer_categories',
            'voir_commandes',
            'valider_paiements',
            'gerer_livraisons',
            'publication_produits',
            'acces_boutique',
            'passer_commande',
        ];

        foreach ($adminArticlesPermissions as $permissionNom) {
            Attribution::create([
                'id_role' => $adminArticles->id,
                'id_permission' => $createdPermissions[$permissionNom]->id,
            ]);
        }

        // Attribuer les permissions à Admin Utilisateurs
        $adminUtilisateursPermissions = [
            'voir_tableau_de_bord',
            'gerer_utilisateurs',
            'attribuer_roles',
            'voir_commandes',
            'acces_boutique',
            'passer_commande',
        ];

        foreach ($adminUtilisateursPermissions as $permissionNom) {
            Attribution::create([
                'id_role' => $adminUtilisateurs->id,
                'id_permission' => $createdPermissions[$permissionNom]->id,
            ]);
        }
    }
}
