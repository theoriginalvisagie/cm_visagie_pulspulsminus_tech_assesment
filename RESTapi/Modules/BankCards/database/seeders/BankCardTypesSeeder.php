<?php

namespace Modules\BankCards\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\BankCards\Entities\BankCardTypes;

class BankCardTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bankCardTypes = [
            [
                "name" => "Debit Card",
                "slug" => "debit-card",
            ],
            [
                "name" => "Credit Card",
                "slug" => "credit-card",
            ]
        ];

        foreach ($bankCardTypes as $cardType) {
            BankCardTypes::create($cardType);
        }
    }
}
