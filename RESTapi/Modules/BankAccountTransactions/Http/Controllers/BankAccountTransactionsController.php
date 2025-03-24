<?php

namespace Modules\BankAccountTransactions\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\BankAccounts\Entities\BankAccount;
use Modules\BankAccountTransactions\Entities\BankAccountTransaction;
use Modules\BankAccountTransactions\Entities\TransactionTypes;

class BankAccountTransactionsController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/debit-card-transactions",
     *     tags={"Bank Account Transactions"},
     *     summary="Get all transactions for the authenticated user's debit cards",
     *     description="Returns all transactions (debit or credit) related to the authenticated user's debit cards.",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of transactions or message if none found",
     *         @OA\JsonContent(
     *             oneOf={
     *                 @OA\Schema(
     *                     @OA\Property(property="status", type="integer", example=201),
     *                     @OA\Property(property="message", type="string", example="success"),
     *                     @OA\Property(property="data", type="array", @OA\Items(
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="bank_account_id", type="integer", example=1),
     *                         @OA\Property(property="bank_card_id", type="integer", example=10),
     *                         @OA\Property(property="user_id", type="integer", example=2),
     *                         @OA\Property(property="amount", type="string", example="150.00"),
     *                         @OA\Property(property="transaction_type_id", type="integer", example=1),
     *                         @OA\Property(property="created_at", type="string", format="date-time", example="2025-03-24T18:03:54.000000Z"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time", example="2025-03-24T18:03:54.000000Z"),
     *                         @OA\Property(property="deleted_at", type="string", nullable=true, example=null),
     *                         @OA\Property(property="bank_account", type="object",
     *                             @OA\Property(property="id", type="integer", example=1),
     *                             @OA\Property(property="user_id", type="integer", example=2),
     *                             @OA\Property(property="name", type="string", example="New Account"),
     *                             @OA\Property(property="account_balance", type="string", example="101350"),
     *                             @OA\Property(property="created_at", type="string", format="date-time", example="2025-03-24T17:14:23.000000Z"),
     *                             @OA\Property(property="updated_at", type="string", format="date-time", example="2025-03-24T18:05:34.000000Z"),
     *                             @OA\Property(property="deleted_at", type="string", nullable=true, example=null)
     *                         ),
     *                         @OA\Property(property="bank_card", type="object",
     *                             @OA\Property(property="id", type="integer", example=10),
     *                             @OA\Property(property="user_id", type="integer", example=2),
     *                             @OA\Property(property="bank_card_type_id", type="integer", example=1),
     *                             @OA\Property(property="bank_card_number", type="integer", example=2403251442892351),
     *                             @OA\Property(property="cvv", type="integer", example=785),
     *                             @OA\Property(property="expiry_date", type="string", example="2029-03-24"),
     *                             @OA\Property(property="bank_card_name", type="string", example="My New bank Card for account"),
     *                             @OA\Property(property="bank_account_id", type="string", example="1"),
     *                             @OA\Property(property="created_at", type="string", format="date-time", example="2025-03-24T17:18:03.000000Z"),
     *                             @OA\Property(property="updated_at", type="string", format="date-time", example="2025-03-24T17:18:03.000000Z"),
     *                             @OA\Property(property="deleted_at", type="string", nullable=true, example=null)
     *                         ),
     *                         @OA\Property(property="user", type="object",
     *                             @OA\Property(property="id", type="integer", example=2),
     *                             @OA\Property(property="name", type="string", example="John"),
     *                             @OA\Property(property="surname", type="string", example="Doe"),
     *                             @OA\Property(property="email", type="string", example="mail@mailssss.com"),
     *                             @OA\Property(property="email_verified_at", type="string", nullable=true, example=null),
     *                             @OA\Property(property="created_at", type="string", format="date-time", example="2025-03-24T12:05:17.000000Z"),
     *                             @OA\Property(property="updated_at", type="string", format="date-time", example="2025-03-24T13:22:03.000000Z"),
     *                             @OA\Property(property="deleted_at", type="string", nullable=true, example=null)
     *                         ),
     *                         @OA\Property(property="transaction_type", type="object",
     *                             @OA\Property(property="id", type="integer", example=2),
     *                             @OA\Property(property="name", type="string", example="Credit"),
     *                             @OA\Property(property="slug", type="string", example="credit-transaction"),
     *                             @OA\Property(property="created_at", type="string", format="date-time", example="2025-03-24T17:44:44.000000Z"),
     *                             @OA\Property(property="updated_at", type="string", format="date-time", example="2025-03-24T17:44:44.000000Z")
     *                         )
     *                     ))
     *                 ),
     *                 @OA\Schema(
     *                     @OA\Property(property="status", type="integer", example=201),
     *                     @OA\Property(property="message", type="string", example="success"),
     *                     @OA\Property(property="data", type="string", example="No transactions Found for user John Doe in account #1")
     *                 )
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function getAllUserTransactions(Request $request){
        $user = $request->user();
        $userId = $user->id;
        $userName = "{$user->name} {$user->surname}";

        $transactions = BankAccountTransaction::with(['bankAccount','bankCard','user','transactionType'])->where('user_id',$userId)
            ->get();

        if($transactions->count() > 0){
            return response([
                "status"=> 201,
                "message"=>"success",
                "data"=>$transactions
            ]);
        }else{
            return response([
                "status"=> 201,
                "message"=>"success",
                "data"=>"No transactions Found for user {$userName} in account #{$request->input("account_id")}"
            ]);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/debit-card-transactions/{id}",
     *     tags={"Bank Account Transactions"},
     *     summary="Get a specific transaction by ID",
     *     description="Returns a transaction (including related account, card, user, and type) owned by the authenticated user.",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID of the transaction",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Transaction found or message if not",
     *         @OA\JsonContent(
     *             oneOf={
     *                 @OA\Schema(
     *                     @OA\Property(property="status", type="integer", example=201),
     *                     @OA\Property(property="message", type="string", example="success"),
     *                     @OA\Property(property="data", type="array", @OA\Items(
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="bank_account_id", type="integer", example=1),
     *                         @OA\Property(property="bank_card_id", type="integer", example=1),
     *                         @OA\Property(property="user_id", type="integer", example=2),
     *                         @OA\Property(property="amount", type="string", example="150.00"),
     *                         @OA\Property(property="transaction_type_id", type="integer", example=1),
     *                         @OA\Property(property="created_at", type="string", format="date-time", example="2025-03-24T18:03:54.000000Z"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time", example="2025-03-24T18:03:54.000000Z"),
     *                         @OA\Property(property="deleted_at", type="string", nullable=true, example=null),
     *                         @OA\Property(property="bank_account", type="object",
     *                             @OA\Property(property="id", type="integer", example=1),
     *                             @OA\Property(property="user_id", type="integer", example=2),
     *                             @OA\Property(property="name", type="string", example="New Account"),
     *                             @OA\Property(property="account_balance", type="string", example="101350"),
     *                             @OA\Property(property="created_at", type="string", format="date-time", example="2025-03-24T17:14:23.000000Z"),
     *                             @OA\Property(property="updated_at", type="string", format="date-time", example="2025-03-24T18:05:34.000000Z"),
     *                             @OA\Property(property="deleted_at", type="string", nullable=true, example=null)
     *                         ),
     *                         @OA\Property(property="bank_card", type="object",
     *                             @OA\Property(property="id", type="integer", example=1),
     *                             @OA\Property(property="user_id", type="integer", example=2),
     *                             @OA\Property(property="bank_card_type_id", type="integer", example=1),
     *                             @OA\Property(property="bank_card_number", type="integer", example=2403258845722227),
     *                             @OA\Property(property="cvv", type="integer", example=150),
     *                             @OA\Property(property="expiry_date", type="string", example="2029-03-24"),
     *                             @OA\Property(property="bank_card_name", type="string", example="New Name"),
     *                             @OA\Property(property="bank_account_id", type="string", nullable=true, example=null),
     *                             @OA\Property(property="created_at", type="string", format="date-time", example="2025-03-24T12:06:50.000000Z"),
     *                             @OA\Property(property="updated_at", type="string", format="date-time", example="2025-03-24T12:55:20.000000Z"),
     *                             @OA\Property(property="deleted_at", type="string", format="date-time", example="2025-03-24T12:55:20.000000Z")
     *                         ),
     *                         @OA\Property(property="user", type="object",
     *                             @OA\Property(property="id", type="integer", example=2),
     *                             @OA\Property(property="name", type="string", example="John"),
     *                             @OA\Property(property="surname", type="string", example="Doe"),
     *                             @OA\Property(property="email", type="string", example="mail@mailssss.com"),
     *                             @OA\Property(property="email_verified_at", type="string", nullable=true, example=null),
     *                             @OA\Property(property="created_at", type="string", format="date-time", example="2025-03-24T12:05:17.000000Z"),
     *                             @OA\Property(property="updated_at", type="string", format="date-time", example="2025-03-24T13:22:03.000000Z"),
     *                             @OA\Property(property="deleted_at", type="string", nullable=true, example=null)
     *                         ),
     *                         @OA\Property(property="transaction_type", type="object",
     *                             @OA\Property(property="id", type="integer", example=1),
     *                             @OA\Property(property="name", type="string", example="Debit"),
     *                             @OA\Property(property="slug", type="string", example="debit-transaction"),
     *                             @OA\Property(property="created_at", type="string", format="date-time", example="2025-03-24T17:44:44.000000Z"),
     *                             @OA\Property(property="updated_at", type="string", format="date-time", example="2025-03-24T17:44:44.000000Z")
     *                         )
     *                     ))
     *                 ),
     *                 @OA\Schema(
     *                     @OA\Property(property="status", type="integer", example=201),
     *                     @OA\Property(property="message", type="string", example="success"),
     *                     @OA\Property(property="data", type="string", example="No transaction Found for user John Doe with transaction id #1")
     *                 )
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function getTransactionWithID(Request $request, $id){
        $user = $request->user();
        $userId = $user->id;
        $userName = "{$user->name} {$user->surname}";

        $transaction = BankAccountTransaction::with(['bankAccount','bankCard','user','transactionType'])
            ->where('user_id',$userId)
            ->where('bank_account_id',$id)
            ->get();

        if($transaction->count() > 0){
            return response([
                "status"=> 201,
                "message"=>"success",
                "data"=>$transaction
            ]);
        }else{
            return response([
                "status"=> 201,
                "message"=>"success",
                "data"=>"No transaction Found for user {$userName} with transaction id #$id"
            ]);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/debit-card-transactions",
     *     tags={"Bank Account Transactions"},
     *     summary="Create a new debit card transaction",
     *     description="Creates a new debit or credit transaction for a debit card owned by the authenticated user.",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"card_id","account_id","amount","transaction_type_id"},
     *             @OA\Property(property="card_id", type="string", example="10"),
     *             @OA\Property(property="account_id", type="string", example="1"),
     *             @OA\Property(property="amount", type="string", example="1500.00"),
     *             @OA\Property(property="transaction_type_id", type="string", example="1")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Transaction created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=201),
     *             @OA\Property(property="message", type="string", example="Account #1 credited with 1500.00. Account balance is 101350")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Account not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Account not found.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation failed",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="amount", type="array", @OA\Items(type="string", example="The amount field is required."))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function createNewTransaction(Request $request){
        $user = $request->user();
        $userId = $user->id;
        $cardID = $request->input("card_id");
        $accountID = $request->input("account_id");
        $amount = $request->input("amount");
        $transactionTypeID = $request->input("transaction_type_id");

        $transActionTypeSlug = TransactionTypes::where('id', $transactionTypeID)->first()->slug;
        $accountDetails = BankAccount::where('id', $accountID)->first();

        if (!$accountDetails) {
            return response()->json([
                'message' => 'Account not found.',
            ], 404);
        }

        if($transActionTypeSlug == "debit-transaction"){
            $accountFunds = $accountDetails->account_balance - $amount;
            $messageType = "debited";
        }else if($transActionTypeSlug == "credit-transaction"){
            $accountFunds = $accountDetails->account_balance + $amount;
            $messageType = "credited";
        } else {
            return response()->json([
                'message' => 'Invalid transaction type.',
            ], 400);
        }

        $accountDetails->account_balance = $accountFunds;
        $accountDetails->save();

        $transaction = BankAccountTransaction::create([
            'user_id' => $userId,
            'bank_card_id' => $cardID,
            'bank_account_id' => $accountID,
            'amount' => $amount,
            'transaction_type_id' => $transactionTypeID,
        ]);

        if($transaction){
            return response()->json([
                "status"=> 201,
                "message"=>"Account #{$accountDetails->id} $messageType with $amount. Account balance is $accountFunds",
            ]);
        }
    }
}
