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

        DB::statement("
            ALTER TABLE commandes
            MODIFY statut ENUM('rejeté', 'annulé', 'en attente', 'confirmé', 'livré')
            NOT NULL DEFAULT 'en attente'
        ");
    }

    public function down(): void
    {
        if (! Schema::hasTable('commandes')) {
            return;
        }

        DB::statement("
            ALTER TABLE commandes
            MODIFY statut ENUM('rejete', 'annule', 'en attente', 'confirme', 'livre')
            NOT NULL DEFAULT 'en attente'
        ");
    }
};
