<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('itens_de_compra', function (Blueprint $table) {
            $table->id();
            $table->integer('quantidade');
            $table->decimal('custo_total', 20, 6);
            $table->decimal('custo_unitario', 20, 6);
            $table->integer('imposto')->default(0);
            $table->foreignId('produto_id')->constrained('produtos')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('compra_id')->constrained('compras')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('itens_de_compra');
    }
};
