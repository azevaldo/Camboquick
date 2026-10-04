<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    use HasFactory;

    protected $table = 'inventarios';

    protected $fillable = [
        'user_id'
    ];

    // Relacionamento com o usuário (quem fez o inventário)
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Registros de estoque ligados a esse inventário
    public function inventarioEstoques()
    {
        return $this->hasMany(InventarioEstoque::class);
    }
}
