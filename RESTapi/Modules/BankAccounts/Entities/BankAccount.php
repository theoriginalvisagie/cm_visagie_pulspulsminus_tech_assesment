<?php

namespace Modules\BankAccounts\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\BankAccounts\Database\Factories\BankAccountFactory;

class BankAccount extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    // protected static function newFactory(): BankAccountFactory
    // {
    //     // return BankAccountFactory::new();
    // }
}
