<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Animal;
use App\Http\Resources\AnimalResource;

class HomeController extends Controller
{
    //
    public function animals(){
        try {
            $authUser = auth()->user();
            $animals = Animal::where('farm_id', $authUser->farm_id)->take(6)->get();
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
