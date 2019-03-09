<?php

namespace App\Http\Controllers\API;

use App\Bike;
use App\Http\Controllers\Controller;
use App\Rating;
use Illuminate\Http\Request;
use Validator;

class RatingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __constructor()
    {
        $this->middleware('auth:api')->except(['index']);
    }

    public function index()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'value' => 'required|numeric|min:0|max:5',
            'bike_id' => 'required',
            'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if ($request->user()->id != $request->user_id) {
            return response()->json(['error' => 'User not found'], 403);
        }

        if (!Bike::find($request->bike_id)) {
            return response()->json(['error' => 'Product not found!'], 400);
        }
        //crate or update rating
        $rating = Rating::updateOrCreate([
            'bike_id' => $request->bike_id,
            'user_id' => $request->user_id,
        ], [
            'value' => $request->value,
        ]);

        return $rating;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleteBike = Rating::findOrFail($id)->delete();
        return response()->json(['messages' => 'delete rating success'], 204);
    }
}
