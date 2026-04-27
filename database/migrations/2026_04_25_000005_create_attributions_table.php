<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attributions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_role')->constrained('roles')->cascadeOnDelete();
            $table->foreignId('id_permission')->constrained('permissions')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['id_role', 'id_permission']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attributions');
    }
};
