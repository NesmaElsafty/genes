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
use App\Models\AnimalBreed;
class HomeController extends Controller
{
    //
    public function animals()
    {
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
            return response()->json(
                [
                    'status' => false,
                    'message' => $e->getMessage(),
                ],
                500,
            );
        }
    }

    // calculate stats for the home page client
    public function clientStats(Request $request)
    {
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
            $reproductionSuccessRate = ($totalMatedAnimals / $totalPregnantAnimals) * 100;

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
            return response()->json(
                [
                    'status' => false,
                    'message' => $e->getMessage(),
                ],
                500,
            );
        }
    }

    // Animal event type stats for chart
    public function animalEventTypeStats(Request $request)
    {
        try {
            $authUser = auth()->user();
            // Get all farms for the user
            $farmId = Farm::find($request->farm_id);
            // dd($farmId);
            // Get all animals in those farms
            $animalIds = Animal::where('farm_id', $farmId->id)->pluck('id');
            // Get all event types
            $eventTypes = EventType::all();
            // Count events for each type
            $eventTypeStats = [];
            $total = 0;
            foreach ($eventTypes as $eventType) {
                $count = Event::whereIn('animal_id', $animalIds)->where('eventType_id', $eventType->id)->count();
                $eventTypeStats[] = [
                    'name' => $eventType->name,
                    'count' => $count,
                ];
                $total += $count;
            }
            // Calculate percentages
            foreach ($eventTypeStats as &$stat) {
                $stat['percentage'] = $total > 0 ? round(($stat['count'] / $total) * 100, 2) : 0;
            }
            return response()->json([
                'status' => true,
                'message' => 'Animal event type stats fetched successfully',
                'data' => $eventTypeStats,
                'total' => $total,
            ]);
        } catch (\Exception $e) {
            return response()->json(
                [
                    'status' => false,
                    'message' => $e->getMessage(),
                ],
                500,
            );
        }
    }

    // Animal breed stats for chart
    public function animalBreedStats(Request $request)
    {
        try {
            $farm = Farm::find($request->farm_id);
            if (!$farm) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'Farm not found',
                    ],
                    404,
                );
            }
            $animalIds = Animal::where('farm_id', $farm->id)->pluck('id');
            $breeds = AnimalBreed::all();
            $breedStats = [];
            foreach ($breeds as $breed) {
                $count = Animal::where('farm_id', $farm->id)->where('breed_id', $breed->id)->count();
                $breedStats[] = [
                    'name' => $breed->name,
                    'count' => $count,
                ];
            }
            return response()->json([
                'status' => true,
                'message' => 'Animal breed stats fetched successfully',
                'data' => $breedStats,
            ]);
        } catch (\Exception $e) {
            return response()->json(
                [
                    'status' => false,
                    'message' => $e->getMessage(),
                ],
                500,
            );
        }
    }

    // selectable farms by current user id
    public function selectableFarms()
    {
        try {
            $currentUser = auth()->user();
            if ($currentUser->role == 'admin') {
                return response()->json([
                    'status' => false,
                    'message' => 'You are not authorized to access this resource',
                ], 403);    
            }
            $farms = Farm::where('user_id', $currentUser->id)->select('id', 'name')->get();
            return response()->json([
                'status' => true,
                'message' => 'Selectable farms fetched successfully',
                'data' => $farms,
            ]);
        } catch (\Exception $e) {
            return response()->json(
                [
                    'status' => false,
                    'message' => $e->getMessage(),
                ],
                500,
            );
        }
    }
}
