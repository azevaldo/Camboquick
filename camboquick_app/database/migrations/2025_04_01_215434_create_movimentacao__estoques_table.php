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
        Schema::create('movimentacao__estoques', function (Blueprint $table) {
            $table->id();
            $table->integer('quantidade');
            $table->decimal('preco_compra', 20, 6);
            $table->enum('tipo',['Saida','Entrada','Perda','Saida Cancelada','Entrada Cancelada','Perda Cancelada']);
            $table->integer('imposto')->default(0);
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('produto_id')->constrained('produtos')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('movimentacao__estoques');
    }
};
