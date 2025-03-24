<?php

namespace Modules\BankAccountTransactions\Database\Seeders;

use Illuminate\Database\Seeder;

class BankAccountTransactionsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $this->call(TransactionsTypeSeeder::class);
    }
}
