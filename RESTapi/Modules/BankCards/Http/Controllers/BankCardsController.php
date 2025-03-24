<?php

namespace Modules\BankCards\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\BankCards\Entities\BankCards;
use Modules\BankCards\Entities\BankCardTypes;

class BankCardsController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/v1/debit-cards",
     *     tags={"Bank Cards"},
     *     summary="Get all debit cards for the authenticated user",
     *     description="Returns all debit cards owned by the authenticated user, including card type details.",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of bank cards",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=201),
     *             @OA\Property(property="message", type="string", example="success"),
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=2),
     *                 @OA\Property(property="user_id", type="integer", example=2),
     *                 @OA\Property(property="bank_card_type_id", type="integer", example=1),
     *                 @OA\Property(property="bank_card_number", type="integer", example=1234567898765432),
     *                 @OA\Property(property="cvv", type="integer", example=321),
     *                 @OA\Property(property="expiry_date", type="string", format="date", example="2029-03-24"),
     *                 @OA\Property(property="bank_card_name", type="string", example="Test Debit Card"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-03-24T12:07:09.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-03-24T12:07:09.000000Z"),
     *                 @OA\Property(property="deleted_at", type="string", format="nullable", example=null),
     *                 @OA\Property(property="bank_card_type", type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Debit Card"),
     *                     @OA\Property(property="slug", type="string", example="debit-card"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-03-24T12:05:02.000000Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-03-24T12:05:02.000000Z")
     *                 )
     *             ))
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function getAllUserCards(Request $request){
        $user = $request->user();
        $userId = $user->id;
        $userName = "{$user->name} {$user->surname}";
        $cardTypeSlug = $this->getCardTypeSlug();
        $cardTypeID = BankCardTypes::where('slug', $cardTypeSlug)->first()->id;

        $bankCards = BankCards::with(['bankCardType'])
            ->where('user_id', $userId)
            ->where('bank_card_type_id', $cardTypeID)
            ->get();

        if($bankCards->count() > 0){
            return response([
                "status"=> 201,
                "message"=>"success",
                "data"=>$bankCards
            ]);
        }else{
            return response([
                "status"=> 201,
                "message"=>"success",
                "data"=>"No Cards Found for user {$userName}"
            ]);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/debit-cards/{id}",
     *     tags={"Bank Cards"},
     *     summary="Get a specific bank card by ID",
     *     description="Returns a single bank card (with card type) belonging to the authenticated user.",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID of the bank card",
     *         @OA\Schema(type="integer", example=2)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Card found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=201),
     *             @OA\Property(property="message", type="string", example="success"),
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=2),
     *                 @OA\Property(property="user_id", type="integer", example=2),
     *                 @OA\Property(property="bank_card_type_id", type="integer", example=1),
     *                 @OA\Property(property="bank_card_number", type="integer", example=1234567898765432),
     *                 @OA\Property(property="cvv", type="integer", example=321),
     *                 @OA\Property(property="expiry_date", type="string", example="2029-03-24"),
     *                 @OA\Property(property="bank_card_name", type="string", example="Test Debit Card"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-03-24T12:07:09.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-03-24T12:07:09.000000Z"),
     *                 @OA\Property(property="deleted_at", type="string", nullable=true, example=null),
     *                 @OA\Property(property="bank_card_type", type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Debit Card"),
     *                     @OA\Property(property="slug", type="string", example="debit-card"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-03-24T12:05:02.000000Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-03-24T12:05:02.000000Z")
     *                 )
     *             ))
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function getUserCardWithId(Request $request, $id){
        $user = $request->user();
        $userId = $user->id;
        $userName = "{$user->name} {$user->surname}";

        $cardTypeSlug = $this->getCardTypeSlug();
        $cardTypeID = BankCardTypes::where('slug', $cardTypeSlug)->first()->id;

        $bankCard = BankCards::with(['bankCardType'])
            ->where('user_id', $userId)
            ->where('bank_card_type_id', $cardTypeID)
            ->where('id',$id)
            ->get();

        if($bankCard->count() > 0){
            return response([
                "status"=> 201,
                "message"=>"success",
                "data"=>$bankCard
            ]);
        }else{
            return response([
                "status"=> 201,
                "message"=>"success",
                "data"=>"No Cards Found for user {$userName}"
            ]);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/debit-cards",
     *     tags={"Bank Cards"},
     *     summary="Create a new debit card",
     *     description="Creates a new debit card for the authenticated user. The card number, CVV, and expiry date are generated automatically.",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"bank_card_name"},
     *             @OA\Property(property="bank_card_name", type="string", example="My New bank Card"),
     *             @OA\Property(property="account_id", type="string", example="1")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Card created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=201),
     *             @OA\Property(property="message", type="string", example="My New bank Card successfully created")
     *         )
     *     ),
     *     @OA\Response(
     *         response=501,
     *         description="Failed to create card",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=501),
     *             @OA\Property(property="message", type="string", example="My New bank Card could not be created")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function createNewCard(Request $request)
    {
        $user = $request->user();
        $userId = $user->id;
        $cardName = $request->get('bank_card_name');
        $cardTypeSlug = $this->getCardTypeSlug();
        $cardTypeID = BankCardTypes::where('slug', $cardTypeSlug)->first()->id;
        $account_id = $request->get('account_id');

        $cardDetails = $this->createCardDetails();

        $cardNumber = $cardDetails['card_number'];
        $cvv = $cardDetails['cvv'];
        $expiryDate = $cardDetails['expiry_date'];

        $newCard = BankCards::create([
            'user_id' => $userId,
            'bank_card_number' => $cardNumber,
            'cvv' => $cvv,
            'expiry_date' => $expiryDate,
            'bank_card_type_id' => $cardTypeID,
            'bank_card_name' => $cardName,
            'bank_account_id' => $account_id,
        ]);

        if($newCard){
            return response()->json([
                "status" => 201,
                "message" => "{$cardName} successfully created",
            ]);
        }else{
            return response()->json([
                "status" => 501,
                "message" => "{$cardName} could not be created",
            ]);
        }
    }

    public function createCardDetails () {
        $cardDetails = [];
        $cvv = "";

        $dateDay = date('d');
        $dateMonth = date('m');
        $dateYear = date('y');

        $firstSegment = $dateDay.$dateMonth;
        $secondSegment = $dateYear.rand(0,9).rand(0,9);
        $thirdSegment = "";
        $forthSegment = "";

        for($i = 0; $i <= 3; $i++) {
            $thirdSegment .= rand(0,9);
        }

        for($i = 0; $i <= 3; $i++) {
            $forthSegment .= rand(0,9);
        }


        for($i = 0; $i <= 2; $i++) {
            $cvv .= rand(0,9);
        }

        $expiryDate = date("Y-m-d",strtotime("+ 4years"));

        $cardDetails['card_number'] = $firstSegment.$secondSegment.$thirdSegment.$forthSegment;
        $cardDetails['cvv'] = $cvv;
        $cardDetails['expiry_date'] = $expiryDate;

        return $cardDetails;

    }

    public function getCardTypeSlug(){
        $segments = request()->segments();

        $last = end($segments);
        $isId = is_numeric($last);
        $cardTypeSlug = $isId ? $segments[count($segments) - 2] : $last;

        return substr($cardTypeSlug, -1) === 's'
            ? substr($cardTypeSlug, 0, -1)
            : $cardTypeSlug;
    }

    /**
     * @OA\Put(
     *     path="/api/v1/debit-cards/{id}",
     *     tags={"Bank Cards"},
     *     summary="Update a user's bank card",
     *     description="Updates the bank card name for the authenticated user.",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Bank card ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"bank_card_name"},
     *             @OA\Property(property="bank_card_name", type="string", example="Updated Card Name")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Bank card updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Bank card updated successfully."),
     *             @OA\Property(property="card", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="bank_card_name", type="string", example="Updated Card Name")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=404, description="Card not found")
     * )
     */
    public function updateBankCard(Request $request, $id){
        $user = $request->user();

        $request->validate([
            'bank_card_name' => 'required|string|max:255',
        ]);

        $newCardName = $request->get('bank_card_name');

        $bankCard = BankCards::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$bankCard) {
            return response()->json([
                'message' => 'Bank card not found or access denied.',
            ], 404);
        }

        $bankCard->bank_card_name = $newCardName;
        $bankCard->save();

        return response()->json([
            'message' => 'Bank card updated successfully.',
            'card' => $bankCard,
        ], 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/debit-cards/{id}",
     *     tags={"Bank Cards"},
     *     summary="Delete a specific debit card",
     *     description="Deletes a debit card owned by the authenticated user.",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID of the bank card to delete",
     *         @OA\Schema(type="integer", example=2)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Card deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Card 'Test Debit Card' deleted successfully by John Doe.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Card not found or access denied",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Card not found or access denied.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function destroy(Request $request, $id) {
        $user = $request->user();
        $userId = $user->id;
        $userName = "{$user->name} {$user->surname}";
        $cardTypeSlug = $this->getCardTypeSlug();

        $cardType = BankCardTypes::where('slug', $cardTypeSlug)->first();

        if (!$cardType) {
            return response()->json([
                'message' => 'Card type not found.',
            ], 404);
        }

        $card = BankCards::where('id', $id)
            ->where('user_id', $userId)
            ->where('bank_card_type_id', $cardType->id)
            ->first();

        if (!$card) {
            return response()->json([
                'message' => "Card not found or access denied.",
            ], 404);
        }

        $cardName = $card->bank_card_name;
        $card->delete();

        return response()->json([
            'message' => "Card '{$cardName}' deleted successfully by {$userName}.",
        ], 200);
    }
}
