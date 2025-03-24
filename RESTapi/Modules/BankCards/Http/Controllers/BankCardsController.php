<?php

namespace Modules\BankCards\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\BankCards\Entities\BankCards;
use Modules\BankCards\Entities\BankCardTypes;

class BankCardsController extends Controller
{

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

    public function getUserCardWithId(Request $request){
        $user = $request->user();
        $userId = $user->id;
        $userName = "{$user->name} {$user->surname}";

        $cardTypeSlug = $this->getCardTypeSlug();
        $cardTypeID = BankCardTypes::where('slug', $cardTypeSlug)->first()->id;

        $bankCard = BankCards::with(['bankCardType'])
            ->where('user_id', $userId)
            ->where('bank_card_type_id', $cardTypeID)
            ->where('id',$request->card_id)
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

    public function createNewCard(Request $request)
    {
        $user = $request->user();
        $userId = $user->id;
        $cardName = $request->get('bank_card_name');
        $cardTypeSlug = $this->getCardTypeSlug();

        $cardTypeID = BankCardTypes::where('slug', $cardTypeSlug)->first()->id;

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
        $cardTypeSlug = isset($segments[count($segments) - 2]) ? $segments[count($segments) - 2] : null;

        if (!$cardTypeSlug) {
            return null;
        }

        return substr($cardTypeSlug, -1) === 's' ? substr($cardTypeSlug, 0, -1) : $cardTypeSlug;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('bankcards::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('bankcards::create');
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
        return view('bankcards::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('bankcards::edit');
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
