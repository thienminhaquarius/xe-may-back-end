<?php

namespace App\Http\Controllers\API;

use App\Bike;
use App\Bikedetail;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Validator;

class BikeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth:api')->except(['index', 'show']);
    }

    public function index(Request $request)
    {
        $skip = $request->query('skip');
        if ($skip == null) {
            $skip = 0;
        }

        $number = $request->query('number');
        if ($number == null) {
            $number = 4;
        }
        $listBikes = Bike::withCount('comments')->
            orderBy('created_at', 'desc')->skip($skip)->take($number)->get();

        foreach ($listBikes as $bike) {
            $bike['ratings_avg'] = $bike->ratings->avg('value');
            if ($bike['ratings_avg'] == null) {
                $bike['ratings_avg'] = 0;
            }
        }
        return $listBikes;

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $payload = auth()->payload();
        $user = User::where('email', $payload['useremail'])->first();

        if (!$user) {
            return response()->json(['errors' => 'User not found'], 422);
        }

        // Bike validation
        $validator = Validator::make($request->all(), [
            //bike validator
            'name' => 'required',
            'price' => 'required',
            'thumbnailImage' => 'required|string',

            // //bikedetail validator
            // 'info' => 'required|string',

        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $decodedImg = base64_decode($request->thumbnailImage);

        $imageName = time() . '.' . 'jpg';
        $imagePath = 'productThumbnailImage/' . $imageName;

        $isSuccess = Storage::disk('upload_image')->put($imagePath, $decodedImg);
        if (!$isSuccess) {
            return reponse()->json(['errors' => 'System errors'], 422);
        }

        $newBike = Bike::create(
            ['name' => $request->name,
                'price' => $request->price,
                'thumbnailImage' => $imageName,
                'user_id' => $user->id,
            ]
        );
        Bikedetail::create([
            'info' => $request->info,
            'bike_id' => $newBike->id,
        ]);

        return Bike::with('bikedetail')->findOrFail($newBike->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $bike = Bike::with('bikedetail')->withCount('ratings')->findOrFail($id);
        $bike['ratings_avg'] = $bike->ratings->avg('value');
        if ($bike['ratings_avg'] == null) {
            $bike['ratings_avg'] = 0;
        }
        unset($bike['ratings']);

        return $bike;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $payload = auth()->payload();
        if ($payload['useremail'] != 'boss@gmail.com') {
            return response()->json(['errors' => 'Only admin can delete product'], 422);
        }

        $deleteBikeById = Bike::find($id);
        $bikedetail = $deleteBikeById->bikedetail;
        if($bikedetail){
            $bikedetail->delete();
        }
        
        $deleteBikeById->delete();
        return response()->json([], 204);
    }
}
