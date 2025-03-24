<?php

namespace Modules\BankAccountTransactions\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\BankAccounts\Entities\BankAccount;
use Modules\BankAccountTransactions\Entities\BankAccountTransaction;
use Modules\BankAccountTransactions\Entities\TransactionTypes;

class BankAccountTransactionsController extends Controller
{
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

    public function createNewTransaction(Request $request){
        $user = $request->user();
        $userId = $user->id;
        $userName = "{$user->name} {$user->surname}";
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
