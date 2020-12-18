<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomImage extends Model
{
    use HasFactory;
    protected $table="custom_images";
    protected $fillable=[
        'custom_category_id',
        'image_one',
        'position_x',
        'position_y',
        'imgposition_x',
        'imgposition_y',
        'width',
        'height',
        'status'
    ];

    public function getCustomCategory()
    {
        return $this->belongsTo('App\Models\CustomCategory','custom_category_id');
    }
}
