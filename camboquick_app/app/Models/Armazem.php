<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Armazem extends Model
{
    use HasFactory;
    /**
     *     $table->string('descricao');
            $table->string('localizacao');
            $table->string('codigo')->unique();
     */
    protected $fillable=['descricao','localizacao','codigo','slug','status'];
    protected $table='armazens';
    public function produtos()
{
    return $this->hasMany(Produto::class,'armazem_id');
}



}
