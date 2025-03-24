<?php

namespace Modules\BankAccountTransactions\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\BankAccounts\Entities\BankAccount;
use Modules\BankCards\Entities\BankCards;
use Modules\Users\Entities\Users;

// use Modules\BankAccountTransactions\Database\Factories\BankAccountTransactionFactory;

class BankAccountTransaction extends Model
{
    use SoftDeletes;

    protected $table = 'bank_account_transactions';
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'id',
        'bank_account_id',
        'bank_card_id',
        'user_id',
        'amount',
        'transaction_type_id'
    ];

    public function bankAccount(){
        return $this->belongsTo(BankAccount::class);
    }

    public function bankCard(){
        return $this->belongsTo(BankCards::class);
    }

    public function user(){
        return $this->belongsTo(Users::class, 'user_id');
    }

    public function transactionType(){
        return $this->belongsTo(TransactionTypes::class, 'transaction_type_id');
    }

}

