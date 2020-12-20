<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Festival extends Model
{
    use HasFactory;

    protected $table = "festivals";

    protected $fillable = [
        'festival_name',
        'festival_date',
        'festival_info',
        'festival_day',
        'status',
    ];

    public function getimages()
    {
        return $this->hasMany('App\Models\Image');
    }
    public function image()
    {
        return $this->hasOne('App\Models\Image')->orderBy('id', 'desc');
    }

}
