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
        'email',
        'password',
        'username'
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
        'password' => 'hashed',
    ];

    public function posts()
    {
        // 1 usuario puede tener N posts
        return $this->hasMany(Post::class, 'user_id');
    }

    public function likes()
    {
        // 1 usuario puede tener N likes
        return $this->hasMany(Like::class);
    }

    // Almacena los seguidores de un usuario
    public function followers()
    {
        // 1 usuario puede tener muchos seguidores
        return $this->belongsToMany(User::class, 'followers', 'user_id', 'follower_id');
    }

    // Almacenar los usuarios que seguimos
    public function followings()
    {
        // 1 usuario puede seguir a muchos usuarios
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'user_id');
    }

    // Comprueba si un usuario ya sigue a otro
    public function siguiendo(User $user)
    {
        return $this->followers->contains( $user->id );
    }
}
