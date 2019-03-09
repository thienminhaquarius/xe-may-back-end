<?php

namespace App\Http\Controllers\API;

use App\Bike;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BikedetailController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth:api');

    }
    public function index(Request $request)
    {
        // $bikedetail =
        // Bike::with(['ratings', 'bikedetail'])->findOrFail(1);
        // $detailId = $bikedetail["bikedetail"]["id"];

        // $imgArrs = Bikedetail::with('imagedetails')->findOrFail($detailId);

        // $bikedetail["bikedetail"]["imagedetails"] = $imgArrs;
        // return $bikedetail;

        $info = Bike::find(1);

        return $request;
    }

    public function store(Request $request)
    {
        // $bikedetail =
        // Bike::with(['ratings', 'bikedetail'])->findOrFail(1);
        // $detailId = $bikedetail["bikedetail"]["id"];

        // $imgArrs = Bikedetail::with('imagedetails')->findOrFail($detailId);

        // $bikedetail["bikedetail"]["imagedetails"] = $imgArrs;
        // return $bikedetail;

        $info = Bike::find(1);
        // $info->bikedetail->update($request->only(['info']));
        // $info->bikedetail->create(['info' => 'det', 'bike_id' => $info->id]);
        // Bikedetail::create(['info' => 'det2', 'bike_id' => $info->id]);
        // return $request->only('name');
        // auth()->factory()->getTTL()

        // "exp": 1551379022,
        // return auth()->checkOrFail();

        // $payload = auth()->payload();

        // return ['payload' => $payload['exp'],
        //     '$timestamp' => time(),
        // ];

        // try {

        // } catch (TokenExpiredException $e) {
        //     //     if ($payload['exp'] - time() < 0) {

        //     //     }
        // }
        $bike = new Bike();

        return $request->input('detailImage' . (string) 1);
    }
}
