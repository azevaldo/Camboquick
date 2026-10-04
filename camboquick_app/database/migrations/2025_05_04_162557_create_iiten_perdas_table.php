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
        Schema::create('perda_produto', function (Blueprint $table) {
            $table->id();
            $table->integer('quantidade');
            $table->decimal('preco', 20, 6); // preço unitário no momento da venda
            $table->decimal('subtotal',  20, 6); // quantidade * preco
            $table->foreignId('produto_id')->constrained('produtos')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('perda_id')->constrained('perdas')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('iiten_perdas');
    }
};
