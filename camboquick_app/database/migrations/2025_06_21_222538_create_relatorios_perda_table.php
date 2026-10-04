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
        Schema::create('relatorios_perda', function (Blueprint $table) {
            $table->id();
            $table->integer('quantidade_perdida');
            $table->decimal('valor_total_perda',38, 20);
            $table->decimal('preco_unitario_perda',38, 20);
            $table->foreignId('perda_id')->constrained('perdas')->onDelete('cascade');
            $table->foreignId('produto_id')->constrained('produtos')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('relatorios_perda');
    }
};
