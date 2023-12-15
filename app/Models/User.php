<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'surname',
        'description',
        'email',
        'password',
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

    public function posts(){
        return $this->hasMany(Post::class);

    } public function comentarios(){
        return $this->hasMany(Comentario::class);

    }

    public function lista_favoritos(){
        return $this->hasMany(Lista_Favorito::class);

    }
    public function lista_pedidos(){
        return $this->hasMany(Lista_pedido::class);

    }
    public function notificaciones(){
        return $this->hasMany(Notificacion::class);

    }
    public function resenias(){
        return $this->hasMany(Resenia::class);

    }
}
