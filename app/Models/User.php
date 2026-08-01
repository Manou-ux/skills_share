<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'bio', 'city',
    ];

    public function userSkills()
    {
        return $this->hasMany(UserSkill::class);
    }

    public function sentRequests()
    {
        return $this->hasMany(ExchangeRequest::class, 'sender_id');
    }

    public function receivedRequests()
    {
        return $this->hasMany(ExchangeRequest::class, 'receiver_id');
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
