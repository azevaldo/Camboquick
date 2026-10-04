<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fornecedor extends Model
{
    use HasFactory;

    
    protected $table = 'fornecedores';
    protected $fillable = ['nome','nif', 'localizacao', 'contato','slug','status'];

    public function compras()
    {
        return $this->hasMany(Compra::class, 'fornecedor_id');
    }

  
}
