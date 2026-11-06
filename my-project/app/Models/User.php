<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'is_main_admin'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

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
            'is_main_admin' => 'boolean',
        ];
    }

    /**
     * Check if user is a Main Admin.
     */
    public function isMainAdmin(): bool
    {
        return (bool) $this->is_main_admin;
    }

    /**
     * Human-friendly role display name.
     */
    public function getRoleNameAttribute(): string
    {
        return $this->isMainAdmin() ? 'Main Admin' : 'Admin';
    }
}
