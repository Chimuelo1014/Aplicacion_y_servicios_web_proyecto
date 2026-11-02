<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehiculos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marca_id')->constrained('marcas')->restrictOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('modelo', 100);
            $table->unsignedSmallInteger('anio');
            $table->string('placa', 10)->unique();
            $table->string('color', 40)->nullable();
            $table->string('tipo', 30);
            $table->unsignedInteger('kilometraje')->default(0);
            $table->decimal('precio', 14, 2);
            $table->string('estado', 20)->default('disponible');
            $table->text('descripcion')->nullable();
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehiculos');
    }
};
