<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Animal;
use App\Http\Resources\AnimalResource;
use App\Models\FarmUser;
use App\Models\Farm;
use App\Models\EventType;
use App\Models\AnimalMating;    
use App\Models\Event;
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

    // calculate stats for the home page client
    public function clientStats(Request $request){
        try {
            $authUser = auth()->user();
            $farm = Farm::find($request->farm_id);
            // dd($farm);
            $totalAnimals = Animal::where('farm_id', $farm->id)->count();
            
            // Reproduction success rate
            $reproductionSuccessRate = 0;
            // get all animal that belongs to the farm
            $animals = Animal::where('farm_id', $farm->id)->pluck('id');
            $totalPregnantAnimals = Event::whereIn('animal_id', $animals)->where('eventType_id', 2)->count();
            $totalMatedAnimals = AnimalMating::whereIn('sir_id', $animals)->orWhereIn('dam_id', $animals)->count();
            $reproductionSuccessRate = $totalMatedAnimals / $totalPregnantAnimals * 100;
            
            // Genetic analysis rate
            $geneticAnalysisRate = 60;
            
            return response()->json([
                'status' => true,
                'message' => 'Stats fetched successfully',
                'data' => [
                    'total_animals' => $totalAnimals,
                    'reproduction_success_rate' => $reproductionSuccessRate,
                    'genetic_analysis_rate' => $geneticAnalysisRate,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
