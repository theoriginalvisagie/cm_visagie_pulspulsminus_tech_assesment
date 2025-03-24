<?php

namespace Modules\BankCards\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

// use Modules\BankCards\Database\Factories\BankCardsFactory;

class BankCards extends Model
{
    use SoftDeletes;

    protected $table = 'bank_cards';
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'id',
        'user_id',
        'bank_card_type_id',
        'bank_card_number',
        'cvv',
        'expiry_date',
        'bank_card_name'
    ];
}
