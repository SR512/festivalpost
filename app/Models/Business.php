<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    use HasFactory;
    protected $table="businesses";
    protected $fillable=[
        'user_id',
        'business_name',
        'business_email',
        'business_number',
        'business_website',
        'business_address',
        'date_premium',
        'isPremium',
        'status',
    ];
}
