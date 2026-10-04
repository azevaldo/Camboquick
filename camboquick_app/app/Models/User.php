<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        "papel_id",
        "sexo",
       'slug'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function papel(){
        return $this->belongsTo(Papel::class);
    }
    public function vendas(){
        return $this->hasMany(Venda::class,'user_id');
    }

    public function compras(){
        return $this->hasMany(Compra::class,'user_id');
    }
       public function turnos(){
        return $this->hasMany(Turno::class,'usuario_id');
    }
    public function movimentacoes(){
        return $this->hasMany(Venda::class,'user_id');
    }
    public function perdas(){
        return $this->hasMany(Perda::class,'user_id');
    }
     public function pedidos(){
        return $this->hasMany(Pedido::class,'user_id');
    }
}
