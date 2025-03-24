<?php

namespace Modules\BankAccounts\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\BankAccounts\Entities\BankAccount;

class BankAccountsController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/v1/accounts",
     *     tags={"Bank Accounts"},
     *     summary="Get all bank accounts for the authenticated user",
     *     description="Returns all bank accounts owned by the authenticated user.",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of bank accounts",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=201),
     *             @OA\Property(property="message", type="string", example="success"),
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="user_id", type="integer", example=2),
     *                 @OA\Property(property="name", type="string", example="New Account"),
     *                 @OA\Property(property="account_balance", type="string", example="100000.00"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-03-24T17:14:23.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-03-24T17:14:23.000000Z"),
     *                 @OA\Property(property="deleted_at", type="string", format="nullable", example=null)
     *             ))
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function getAllUserBankAccounts(Request $request){
        $user = $request->user();
        $userId = $user->id;
        $userName = "{$user->name} {$user->surname}";

        $bankAccounts = BankAccount::where('user_id',$userId)->get();

        if($bankAccounts->count() > 0){
            return response([
                "status"=> 201,
                "message"=>"success",
                "data"=>$bankAccounts
            ]);
        }else{
            return response([
                "status"=> 201,
                "message"=>"success",
                "data"=>"No accounts Found for user {$userName}"
            ]);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/accounts/{id}",
     *     tags={"Bank Accounts"},
     *     summary="Get a specific bank account by ID",
     *     description="Returns a bank account owned by the authenticated user, based on the given ID.",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID of the bank account to retrieve",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Bank account found",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=201),
     *             @OA\Property(property="message", type="string", example="success"),
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="user_id", type="integer", example=2),
     *                 @OA\Property(property="name", type="string", example="New Account"),
     *                 @OA\Property(property="account_balance", type="string", example="100000.00"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-03-24T17:14:23.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-03-24T17:14:23.000000Z"),
     *                 @OA\Property(property="deleted_at", type="string", format="nullable", example=null)
     *             ))
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Bank account not found or access denied"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function getBankAccountWithId(Request $request, $id){
        $user = $request->user();
        $userId = $user->id;
        $userName = "{$user->name} {$user->surname}";

        $bankAccounts = BankAccount::where('user_id',$userId)->where('id', $id)->get();

        if($bankAccounts->count() > 0){
            return response([
                "status"=> 201,
                "message"=>"success",
                "data"=>$bankAccounts
            ]);
        }else{
            return response([
                "status"=> 201,
                "message"=>"success",
                "data"=>"No account Found for user {$userName}"
            ]);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/accounts",
     *     tags={"Bank Accounts"},
     *     summary="Create a new bank account",
     *     description="Creates a new bank account for the authenticated user.",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"account_name"},
     *             @OA\Property(property="account_name", type="string", example="New Account")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Bank account created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=201),
     *             @OA\Property(property="message", type="string", example="New Account successfully created for John Doe")
     *         )
     *     ),
     *     @OA\Response(
     *         response=501,
     *         description="Bank account creation failed",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=501),
     *             @OA\Property(property="message", type="string", example="New Account could not be created for John Doe")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="account_name", type="array", @OA\Items(type="string", example="The account name field is required."))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function createNewUserBankAccount(Request $request){
        $user = $request->user();
        $userId = $user->id;
        $userName = "{$user->name} {$user->surname}";

        $newAccount = BankAccount::create([
            'user_id' => $userId,
            'name' => $request->account_name,
            'account_balance' => 100000.00
        ]);

        if($newAccount){
            return response()->json([
                "status" => 201,
                "message" => "{$request->account_name} successfully created for {$userName}",
            ]);
        }else{
            return response()->json([
                "status" => 501,
                "message" => "{$request->account_name} could not be created for {$userName}",
            ]);
        }
    }
}
