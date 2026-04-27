<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produits', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->integer('stock')->default(0);
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->decimal('prix_unitaire', 12, 2);
            $table->enum('statut', ['disponible', 'non disponible'])->default('disponible');
            $table->timestamp('date_ajout')->useCurrent();
            $table->foreignId('id_categories')->constrained('categorie_produits')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produits');
    }
};
