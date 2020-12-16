<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $table = "images";
    protected $fillable = [
        'festival_id',
        'post_id',
        'name'
    ];

    public function getFestival()
    {
        return $this->belongsTo('App\Models\Festival','festival_id');
    }
    public function getPost()
    {
        return $this->belongsTo('App\Models\Festival','post_id');
    }
}
