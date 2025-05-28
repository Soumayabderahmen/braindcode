<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\DatabaseNotification;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
       
            'name', 'email', 'password', 'role', 
             'visibility', 'image', 'domain_name', 'specialty','document',
        'statut','phone_number','google_id'
       
    
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function startup()
    {
        return $this->hasOne(Startup::class, 'user_id');
    }

    public function coach(): HasOne
    {
        return $this->hasOne(Coach::class);
    }

    public function investisseur(): HasOne
    {
        return $this->hasOne(Investisseur::class);
    }

    public function admin(): HasOne
    {
        return $this->hasOne(Admin::class);
    }
    // Méthode pour savoir si l'utilisateur est un Coach
    public function isCoach(): bool
    {
        return $this->role === 'coach';
    }

    // Méthode pour savoir si l'utilisateur est une Startup
    public function isStartup(): bool
    {
        return $this->role === 'startup';
    }

    // Méthode pour savoir si l'utilisateur est un Investisseur
    public function isInvestisseur(): bool
    {
        return $this->role === 'investisseur';
    }

    // Méthode pour savoir si l'utilisateur est un Admin
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
    
}
