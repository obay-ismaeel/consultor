<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use GuzzleHttp\Psr7\Message;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    
    protected $guarded = ['id'];

    
    protected $hidden = [
        'password',
        'remember_token',
    ];

 
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function expert(){
        return $this->hasOne(Expert::class, 'user_id');
    }
    public function consults(){
        return $this->hasMany(Consult::class, 'user_id');
    }
    public function favourites(){
        return $this->hasMany(Favourite::class, 'user_id');
    }
    // public function messages()
    // {
    //     return $this->hasMany(Message::class, ')
    // }

}
