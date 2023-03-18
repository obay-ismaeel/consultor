<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    public $timestamps=false;

    public function category(){
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function expert(){
        return $this->belongsTo(Expert::class, 'expert_id');
    }
}
