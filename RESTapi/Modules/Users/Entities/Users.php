<?php

namespace Modules\Users\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class Users extends Model
{
    use SoftDeletes, HasApiTokens, HasFactory;
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'surname',
        'email',
        'password',
        'user_type',
        'user_account_type'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
