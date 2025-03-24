<?php

namespace Modules\BankCards\Tests\Unit;

use Modules\BankCards\Entities\BankCards;
use Tests\TestCase;

class UpdateCardName extends TestCase
{
    #[\PHPUnit\Framework\Attributes\Test]
    public function user_can_update_their_own_bank_card()
    {
        $loginResponse = $this->postJson('/api/login-user', [
            'email' => 'mail@mailssss.com',
            'password' => 'Camel1!ghts',
        ]);

        $loginResponse->assertStatus(200);
        $token = $loginResponse['token'];
        $userId = $loginResponse['user']['id'];

        // Create bank card
        $bankCard = BankCards::create([
            'user_id' => $userId,
            'bank_card_name' => 'Old Card Name',
            'bank_card_number' => '1234567890123456',
            'cvv' => '123',
            'expiry_date' => '2029-03-24',
            'bank_card_type_id' => 1,
        ]);

        // Act: Update the bank card name
        $response = $this->withHeader('Authorization', "Bearer $token")
            ->putJson("/api/v1/debit-cards/{$bankCard->id}", [
                'bank_card_name' => 'Updated Card Name',
            ]);

        // Assert: response + database
        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Bank card updated successfully.',
            'card' => [
                'id' => $bankCard->id,
                'bank_card_name' => 'Updated Card Name',
            ]
        ]);

        $this->assertDatabaseHas('bank_cards', [
            'id' => $bankCard->id,
            'bank_card_name' => 'Updated Card Name',
        ]);
    }
}
