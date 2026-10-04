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
        Schema::create('produtos', function (Blueprint $table) {
            $table->id();
            $table->string("nome");
            $table->integer("quantidade")->default(0);
            $table->integer("quantidade_retalho")->default(0);
            $table->integer("quant_uni_grosso")->default(0);
            $table->decimal("preco", 20, 6);
            $table->decimal("custo_compra_ante", 20, 6)->default(0);
            $table->decimal("custo_compra", 20, 6)->default(0);
            $table->decimal("custo_u_compra", 20, 6)->default(0);
            $table->decimal("custo_u_retalho_compra", 20,6)->default(0);
            $table->decimal("custo_m_p",  38,20)->default(0);
            $table->decimal("custo_m_p_retalho",38, 20)->default(0);
            $table->integer("limite_minimo")->default(0);
            $table->integer("imposto")->default(0);
            $table->string("codigo")->unique();
            $table->string('slug');
            $table->enum('status',['Valido','Arquivado'])->default('Valido');
            $table->foreignId("subCategoria_id")->nullable()->constrained('subcategorias')->onDelete("set null")->onUpdate("cascade");
            $table->foreignId("armazem_id")->nullable()->constrained('armazens')->onDelete("set null")->onUpdate("cascade");
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
        Schema::dropIfExists('produtos');
    }
};
