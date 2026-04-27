<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('commandes')) {
            return;
        }

        // Mettre à jour l'enum pour utiliser des clés standardisées sans espaces ni accents
        DB::statement("
            ALTER TABLE commandes
            MODIFY statut ENUM('rejete', 'annulee', 'en_attente', 'confirmee', 'livree')
            NOT NULL DEFAULT 'en_attente'
        ");
    }

    public function down(): void
    {
        if (! Schema::hasTable('commandes')) {
            return;
        }

        DB::statement("
            ALTER TABLE commandes
            MODIFY statut ENUM('rejeté', 'annulé', 'en attente', 'confirmé', 'livré')
            NOT NULL DEFAULT 'en attente'
        ");
    }
};
