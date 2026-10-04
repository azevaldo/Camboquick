<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;
    protected $table = 'empresa';
    protected $fillable = ['nome','nif','email','local'];

    public function telefones()
    {
        return $this->hasMany(Telefone::class, 'empresa_id');
    }
}
