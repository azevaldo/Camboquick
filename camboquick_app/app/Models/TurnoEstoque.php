<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TurnoEstoque extends Model
{
    use HasFactory;
    protected $table='turno_estoque';

     protected $fillable = [
        'turno_id',
        'produto_id',
        'qnt1_grosso',
               'qnt1_restante',
                      'qnt1_retalho',
  
                            'qnt2_grosso',
               'qnt2_restante',
                      'qnt2_retalho',
  
    ];

    // Relacionamento com o turno
    public function turno()
    {
        return $this->belongsTo(Turno::class);
    }

    // Relacionamento com o produto
    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }
}
