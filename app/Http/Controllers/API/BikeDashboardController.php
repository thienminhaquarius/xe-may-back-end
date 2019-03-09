<?php

namespace App\Http\Controllers\API;

use App\Bike;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BikeDashboardController extends Controller
{
    //
    public function index(Request $request)
    {
        $id = $request->query('id');
        if ($id == null) {
            return response()->json(['errors' => 'Bike id not found'], 422);
        }

        $bike = Bike::withCount('comments')->findOrFail($id);
        $bike['ratings_avg'] = $bike->ratings->avg('value');
        if ($bike['ratings_avg'] == null) {
            $bike['ratings_avg'] = 0;
        }

        return $bike;

    }
}
