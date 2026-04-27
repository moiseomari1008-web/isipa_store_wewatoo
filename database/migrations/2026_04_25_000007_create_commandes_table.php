<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commandes', function (Blueprint $table) {
            $table->id();
            $table->timestamp('date_commande')->useCurrent();
            $table->enum('statut', ['rejeté', 'annulé', 'en attente', 'confirmé', 'livré'])->default('en attente');
            $table->string('adresse_livraison', 500);
            $table->timestamp('date_livraison')->nullable();
            $table->foreignId('id_utilisateur')->constrained('utilisateurs')->restrictOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commandes');
    }
};
