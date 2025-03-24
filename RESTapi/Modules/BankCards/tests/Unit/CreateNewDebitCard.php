<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Modules\BankCards\Entities\BankCardTypes;
use Modules\BankCards\Entities\BankCards;
use Modules\Users\Entities\Users;
use Tests\TestCase;

class CreateNewDebitCard extends TestCase
{


    #[\PHPUnit\Framework\Attributes\Test]
    public function authenticated_user_can_create_new_card()
    {


        $loginResponse = $this->postJson('/api/login-user', [
            'email' => 'mail@mailssss.com',
            'password' => 'Camel1!ghts',
        ]);

        $cardType = BankCardTypes::where('slug','debit-card')->first();

        $loginResponse->assertStatus(200);
        $token = $loginResponse['token'];
        $userId = $loginResponse['user']['id'];

        $this->partialMock(\Modules\BankCards\Http\Controllers\BankCardsController::class, function ($mock) {
            $mock->shouldAllowMockingProtectedMethods()
                ->shouldReceive('getCardTypeSlug')
                ->once()
                ->andReturn('debit-card');

            $mock->shouldReceive('createCardDetails')
                ->once()
                ->andReturn([
                    'card_number' => '1234567898765432',
                    'cvv' => '321',
                    'expiry_date' => '2029-03-24',
                ]);
        });

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/v1/debit-cards', [
                'bank_card_name' => 'Test Debit Card',
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 201,
            'message' => 'Test Debit Card successfully created',
        ]);

        // 6. Assert it was saved in the database
        $this->assertDatabaseHas('bank_cards', [
            'user_id' => $userId,
            'bank_card_name' => 'Test Debit Card',
            'bank_card_type_id' => $cardType->id,
        ]);
    }
}

