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
        Schema::create('turno_estoque', function (Blueprint $table) {
           $table->id();
    $table->foreignId('turno_id')->constrained('turnos')->onDelete('cascade');
    $table->foreignId('produto_id')->constrained('produtos')->onDelete('cascade')->onUpdate('cascade');
    $table->integer('qnt1_grosso');
    $table->integer('qnt1_restante');
    $table->integer('qnt1_retalho');

    $table->integer('qnt2_grosso')->nullable();
    $table->integer('qnt2_restante')->nullable();
    $table->integer('qnt2_retalho')->nullable();

    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('turno_estoque');
    }
};
