<?php

namespace Modules\BankCards\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\BankAccounts\Entities\BankAccount;
use Modules\Users\Entities\Users;

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
        'bank_card_name',
        'bank_account_id'
    ];

    public function bankCardType(){
        return $this->belongsTo(BankCardTypes::class, 'bank_card_type_id');
    }

    public function user(){
        return $this->belongsTo(Users::class, 'user_id');
    }

    public function bankAccount(){
        return $this->belongsTo(BankAccount::class, 'bank_account_id');
    }
}
