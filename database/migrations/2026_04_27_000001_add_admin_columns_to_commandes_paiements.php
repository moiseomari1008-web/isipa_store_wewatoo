<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ajouter montant_total et statut_livraison à la table commandes
        Schema::table('commandes', function (Blueprint $table) {
            if (!Schema::hasColumn('commandes', 'montant_total')) {
                $table->decimal('montant_total', 12, 2)->default(0)->after('adresse_livraison');
            }
            if (!Schema::hasColumn('commandes', 'statut_livraison')) {
                $table->enum('statut_livraison', ['en_attente', 'en_cours', 'livree'])
                      ->default('en_attente')
                      ->after('montant_total');
            }
        });

        // Ajouter statut et mode_paiement à la table paiements
        Schema::table('paiements', function (Blueprint $table) {
            if (!Schema::hasColumn('paiements', 'statut')) {
                $table->enum('statut', ['en_attente', 'valide', 'rejete'])
                      ->default('en_attente')
                      ->after('montant');
            }
            if (!Schema::hasColumn('paiements', 'mode_paiement')) {
                $table->string('mode_paiement', 100)->nullable()->after('statut');
            }
        });
    }

    public function down(): void
    {
        Schema::table('commandes', function (Blueprint $table) {
            $table->dropColumn(['montant_total', 'statut_livraison']);
        });
        Schema::table('paiements', function (Blueprint $table) {
            $table->dropColumn(['statut', 'mode_paiement']);
        });
    }
};
