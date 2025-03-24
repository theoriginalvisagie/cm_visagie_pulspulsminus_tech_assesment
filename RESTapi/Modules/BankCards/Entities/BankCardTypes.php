<?php

namespace Modules\BankCards\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BankCardTypes extends Model
{

    protected $table = 'bank_card_types';
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'id',
        'name',
        'slug'
    ];
}
