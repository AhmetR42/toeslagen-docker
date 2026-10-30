<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    public const ROLLEN = ['burger' => 'Burger', 'medewerker' => 'Medewerker', 'admin' => 'Admin'];

    // 'rol' staat bewust NIET in $fillable: nooit via een formulier in te stellen
    protected $fillable = ['name', 'email', 'password'];
    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return ['password' => 'hashed', 'geblokkeerd' => 'boolean'];
    }

    public function isAdmin(): bool
    {
        return $this->rol === 'admin';
    }
}
