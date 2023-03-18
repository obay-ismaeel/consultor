<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expert extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $with = ['user'];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
    public function services(){
        return $this->hasMany(Service::class, 'expert_id');
    }
    public function consults(){
        return $this->hasMany(Consult::class, 'expert_id');
    }
    public function favourites(){
        return $this->hasMany(Favourite::class, 'expert_id');
    }
}
