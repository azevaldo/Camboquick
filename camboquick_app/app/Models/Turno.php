<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Turno extends Model
{
    use HasFactory;
 protected $table='turnos';

    protected $fillable = [
        'usuario_id',
        'inicio',
        'fim',
        'estado'
    ];

    // Relacionamento com o usuário (caixa)
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    // Relacionamento com os registros de estoque deste turno
    public function turnoEstoques()
    {
        return $this->hasMany(TurnoEstoque::class);
    }
}
