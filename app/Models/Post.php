<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $table="posts";
    protected $fillable=[
        'category'
    ];

    public function getCategory()
    {
        return $this->belongsTo('App\Models\Category','category');
    }

    public function getimages()
    {
        return $this->hasMany('App\Models\Image');
    }
}
