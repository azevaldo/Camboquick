<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    protected $table = 'categorias';
    protected $fillable = ['categoria','slug','status'];

    public function subCategorias()
    {
        return $this->hasMany(SubCategoria::class, 'categoria_id');
    }
}
