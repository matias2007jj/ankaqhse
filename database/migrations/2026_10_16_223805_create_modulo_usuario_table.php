<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('modulo_usuario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('modulo_id')->constrained('modulos')->cascadeOnDelete();
            $table->timestamp('completado_el')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'modulo_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('modulo_usuario');
    }
};
