<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produits_paniers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_produit')->constrained('produits')->restrictOnDelete();
            $table->foreignId('id_panier')->constrained('paniers')->cascadeOnDelete();
            $table->integer('quantite');
            $table->timestamps();

            $table->unique(['id_produit', 'id_panier']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produits_paniers');
    }
};
