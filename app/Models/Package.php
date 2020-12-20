<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $table = "packages";

    protected $fillable = [
        'plan_name',
        'plan_title',
        'plan_price',
        'plan_discount_price',
        'plan_info',
        'plan_validity',
        'status'
    ];
}
