<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class AppUser extends Authenticatable
{
    use HasFactory,HasApiTokens;

    protected $table="app_users";
    protected $fillable=[
        'name',
        'email',
        'mobile',
        'referal',
        'status',
        'isLogin',
        'remember_token',
        'business_id'
    ];

    protected $hidden = [
        'remember_token',
    ];

}
