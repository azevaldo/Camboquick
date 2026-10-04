<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::create('turnos', function (Blueprint $table) {
    $table->id();
    $table->foreignId('usuario_id')->constrained('users');
    $table->dateTime('inicio');
    $table->dateTime('fim')->nullable();
    $table->enum('estado', ['aberto', 'fechado'])->default('aberto');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('turnos');
    }
};
