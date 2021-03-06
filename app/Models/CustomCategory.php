<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomCategory extends Model
{
    use HasFactory;
    protected $table = "custom_categories";

    protected $fillable = [
        'category',
        'status'
    ];

    public function getImages(){
        return $this->hasMany('App\Models\CustomImage');
    }
}
