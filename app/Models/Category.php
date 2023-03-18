<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    public $timestamps=false;

    public function services(){
        return $this->hasMany(Service::class, 'category_id');
    }
    public function consults()
    {
        return $this->hasMany(Consult::class, 'category_id');
    }
}
