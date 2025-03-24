<?php

namespace Modules\BankCards\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\BankCards\Entities\BankCards;
use Modules\BankCards\Entities\BankCardTypes;

class BankCardsController extends Controller
{

    public function getAllUserCards($id){
//        $userCards =
    }

    public function createNewCard(Request $request)
    {
        $user = $request->user();
        $userId = $user->id;
        $cardName = $request->get('bank_card_name');
        $cardTypeSlug = request()->segment(count(request()->segments()));
        $cardTypeSlugSingular = substr($cardTypeSlug, 0, -1);

        $cardTypeID = BankCardTypes::where('slug', $cardTypeSlugSingular)->first()->id;

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
