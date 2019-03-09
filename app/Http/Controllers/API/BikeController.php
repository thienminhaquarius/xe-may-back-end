<?php

namespace App\Http\Controllers\API;

use App\Bike;
use App\Bikedetail;
use App\Comment;
use App\Http\Controllers\Controller;
use App\Rating;
use App\User;
use DB;
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
        $validator = Validator::make($request->all(), [
            'skip' => 'required|Numeric',
            'take' => 'required|Numeric',
            'order' => 'required|String',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $skip = $request->query('skip');
        $take = $request->query('take');

        if ($request->order == 'rating') {

            $listBikes = Bike::select(['id'])
                ->withCount(['ratings as average_rating' => function ($q) {
                    $q->select(DB::raw('coalesce(avg(value),0)'));
                }])
                ->orderByDesc('average_rating')
                ->skip($skip)->take($take)
                ->get();

            foreach ($listBikes as $bike) {
                unset($bike['average_rating']);
            }

        } elseif ($request->order == 'comment') {

            $listBikes = Bike::select(['id'])
                ->withCount('comments')
                ->orderBy('comments_count', 'desc')
                ->skip($skip)->take($take)
                ->get();

            foreach ($listBikes as $bike) {
                unset($bike['comments_count']);
            }
        } elseif ($request->order == 'time') {
            $listBikes = Bike::select(['id'])->orderBy('created_at', 'desc')->skip($skip)->take($take)->get();
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
            'info' => 'required|string',
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
        $userHasRating = null;
        //user request
        // $payload = auth()->payload();

        $user = auth()->user();

        if ($user) {
            //find rating user has rated this bike or return []
            $userHasRating = Rating::where([
                'user_id' => $user['id'],
                'bike_id' => $id,
            ])->first();
        }

        $bike = Bike::with('bikedetail')->withCount('ratings')->findOrFail($id);
        $bike['ratings_avg'] = round($bike->ratings->avg('value'), 2);
        if ($bike['ratings_avg'] == null) {
            $bike['ratings_avg'] = 0;
        }

        $bike['userHasRatingThis'] = $userHasRating;

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

        $bike = Bike::findOrFail($id);

        //delele Image
        $imageFolder = 'productThumbnailImage/';
        Storage::disk('upload_image')->delete($imageFolder . $bike->thumbnailImage);

        //delete bike detail
        Bikedetail::where('bike_id', $bike->id)->delete();

        //delete rating
        Rating::where('bike_id', $bike->id)->delete();

        //delete comment
        Comment::where('bike_id', $bike->id)->delete();

        $bike->delete();
        return response()->json([], 204);
    }
}
