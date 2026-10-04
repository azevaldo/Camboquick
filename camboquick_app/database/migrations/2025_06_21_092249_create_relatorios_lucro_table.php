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
        Schema::create('relatorios_lucro', function (Blueprint $table){
            $table->id();
            $table->integer('quantidade');
            $table->decimal('preco_venda_unitario', 38, 20);
            $table->decimal('preco_custo_unitario', 38, 20);
            $table->decimal('lucro_unitario', 38, 20);
            $table->decimal('lucro_total', 38, 20);
            $table->foreignId('venda_id')->constrained('vendas')->onDelete('cascade');
            $table->foreignId('produto_id')->constrained('produtos')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('relatorios_lucro');
    }
};
