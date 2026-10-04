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
        Schema::create('item_vendas', function (Blueprint $table) {
            $table->id();
            $table->integer("quantidade");
            $table->decimal("preco",  20, 6);
            $table->decimal("total", 20, 6);
            $table->integer('imposto')->default(0);
            $table->foreignId("produto_id")->constrained("produtos")->onDelete("cascade")->onUpdate("cascade");
            $table->foreignId("venda_id")->constrained("vendas")->onDelete("cascade")->onUpdate("cascade");
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
        Schema::dropIfExists('item_vendas');
    }
};
