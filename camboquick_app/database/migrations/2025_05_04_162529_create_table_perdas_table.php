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
        Schema::create('perdas', function (Blueprint $table) {
            $table->id();
            $table->decimal('total', 20, 6)->default(0);
            $table->enum('status',['Valida','Cancelada'])->default('Valida');
            $table->longText('motivo')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_perdas');
    }
};
