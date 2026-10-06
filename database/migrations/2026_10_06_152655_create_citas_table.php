<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('citas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained('users')->onDelete('cascade');
            $table->date('fecha');
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->enum('estado', ['confirmada', 'reagendada', 'cancelada'])->default('confirmada');
            $table->text('notas')->nullable();
            $table->timestamps();

            $table->index(['fecha', 'hora_inicio']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('citas');
    }
};