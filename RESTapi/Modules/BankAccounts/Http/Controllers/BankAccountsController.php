<?php

namespace Modules\BankAccounts\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\BankAccounts\Entities\BankAccount;

class BankAccountsController extends Controller
{

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

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('bankaccounts::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('bankaccounts::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('bankaccounts::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('bankaccounts::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}
}
