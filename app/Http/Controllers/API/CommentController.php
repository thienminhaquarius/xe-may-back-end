<?php

namespace App\Http\Controllers\API;

use App\Bike;
use App\Comment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;

class CommentController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api')->except(['index', 'show']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bike_id' => 'required|String',
            'skip' => 'required|Numeric',
            'take' => 'required|Numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $listIdComments = Comment::where('bike_id', $request->bike_id)
            ->orderBy('created_at', 'desc')->skip($request->skip)->take($request->take)
            ->get(['id']);

        return $listIdComments;
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
            'title' => 'required|string',
            'content' => 'required|string',
            'bike_id' => 'required',
            'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if ($request->user()->id != $request->user_id) {
            return response()->json(['error' => 'User is not the same'], 403);
        }

        if (!Bike::find($request->bike_id)) {
            return response()->json(['error' => 'Product not found!'], 400);
        }

        $comment = Comment::create($request->only(['title', 'content', 'bike_id', 'user_id']));
        return $comment;
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
        $comment = Comment::findOrFail($id);
        $comment->user;
        return $comment;
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

    }
}
