<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Animal;
use App\Http\Resources\AnimalResource;
use App\Models\FarmUser;
class HomeController extends Controller
{
    //
    public function animals(){
        try {
            $authUser = auth()->user();
            // get all farms that the user is associated with
            $farms = FarmUser::where('user_id', $authUser->id)->pluck('farm_id');
            $animals = Animal::whereIn('farm_id', $farms)->take(6)->get();
            return response()->json([
                'status' => true,
                'message' => 'Animals fetched successfully',
                'data' => AnimalResource::collection($animals),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
