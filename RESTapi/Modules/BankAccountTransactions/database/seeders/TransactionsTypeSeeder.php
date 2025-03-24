<?php

namespace Modules\BankAccountTransactions\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\BankAccountTransactions\Entities\TransactionTypes;

class TransactionsTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $transactionTypes = [
            [
                "name"=>"Debit",
                "slug"=>"debit-transaction"
            ],
            [
                "name"=>"Credit",
                "slug"=>"credit-transaction"
            ]
        ];

        foreach ($transactionTypes as $transactionType) {
            TransactionTypes::create($transactionType);
        }

    }
}
