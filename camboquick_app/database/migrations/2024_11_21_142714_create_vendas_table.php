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
        Schema::create('vendas', function (Blueprint $table) {
            $table->id();
            $table->string('nome_cliente')->nullable();
            $table->string('nif_cliente')->nullable();
            $table->string('contato_cliente')->nullable();
            $table->string('local_cliente')->nullable();
            $table->string('n_fatura')->nullable()->unique();
            
            $table->decimal("total",  20, 6)->default("0");
            $table->integer("quantidade")->default("0");
            $table->enum('forma_pag', ['Dinheiro', 'Cartão']);
            $table->enum('status',['Valida','Cancelada'])->default('Valida');
            $table->foreignId('user_id')->nullable()
                  ->constrained('users')
                  ->onDelete('set null')
                  ->onUpdate('cascade');
    
            $table->foreignId('cliente_id')->nullable()
                  ->constrained('clientes')
                  ->onDelete('set null')
                  ->onUpdate('cascade');
    
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
        Schema::dropIfExists('vendas');
    }
};
