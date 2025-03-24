<?php

namespace Modules\BankAccounts\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Users\Entities\Users;

// use Modules\BankAccounts\Database\Factories\BankAccountFactory;

class BankAccount extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'id',
        'user_id',
        'name',
        'account_balance',
    ];

    public function user(){
        return $this->belongsTo(Users::class, 'user_id');
    }

}
