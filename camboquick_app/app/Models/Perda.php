<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perda extends Model
{
    use HasFactory;
    protected $table="perdas";
    protected $fillable=["user_id","total","status","motivo"];

    public function produtos(){
        return $this->belongsToMany(Produto::class,'perda_produto')
        ->withPivot('quantidade', 'preco', 'subtotal')
        ->withTimesTamps();
    }
    public function User(){
        return $this->belongsTo(User::class,'user_id');
    }
       public function relatorioperda()
    {
        return $this->hasMany(RelatorioPerda::class, 'perda_id');

    }
}
