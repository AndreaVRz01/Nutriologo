<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('formularios_clinicos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained('users')->onDelete('cascade');
            $table->integer('edad');
            $table->decimal('peso', 5, 2);
            $table->decimal('estatura', 3, 2);
            $table->text('condicion_medica')->nullable();
            $table->text('alergias')->nullable();
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('formularios_clinicos');
    }
};