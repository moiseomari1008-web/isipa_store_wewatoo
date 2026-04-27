<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->decimal('montant', 12, 2);
            $table->string('type_paiement', 100);
            $table->timestamp('date_paiement')->useCurrent();
            $table->string('num_compte', 100)->nullable();
            $table->string('reference_transaction')->nullable();
            $table->foreignId('id_commande')->unique()->constrained('commandes')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};
