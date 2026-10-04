<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemPerda extends Model
{
    use HasFactory;
    protected $table='iitens_perda';
    protected $fillable=['produto_id','perda_id'];
}
