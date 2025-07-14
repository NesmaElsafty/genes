<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Farm;
use App\Models\User;
use App\Models\Animal;
use App\Models\EventType;
use App\Models\Event;
class AdminHomeController extends Controller
{
    //
    // stats for admin home page
    public function stats()
    {
        try {
            $totalFarms = Farm::count();
            // get users where has role not admin
            $totalUsers = User::whereHas('roles', function ($query) {
                $query->where('name', '!=', 'admin');
            })->count();
            // get animals where has farm
            $totalAnimals = Animal::count();
            return response()->json([
                'status' => true,
                'message' => 'Stats fetched successfully',
                'data' => [
                    'total_farms' => $totalFarms,
                    'total_animals' => $totalAnimals,
                    'total_users' => $totalUsers,
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

    // Farm registration stats for line chart
    public function farmRegistrationTrends(Request $request)
    {
        try {
            $now = now();
            $currentMonth = $now->month;
            $previousMonth = $now->copy()->subMonth()->month;
            $year = $now->year;

            // Helper to get week number in month
            $getWeekOfMonth = function($date) {
                $firstDay = $date->copy()->startOfMonth();
                return intval(floor(($date->day - 1 + $firstDay->dayOfWeek) / 7) + 1);
            };

            // Current month data
            $currentMonthData = [1 => 0, 2 => 0, 3 => 0, 4 => 0];
            $farmsCurrent = Farm::whereYear('created_at', $year)
                ->whereMonth('created_at', $currentMonth)
                ->get();
            foreach ($farmsCurrent as $farm) {
                $week = $getWeekOfMonth($farm->created_at);
                if (isset($currentMonthData[$week])) {
                    $currentMonthData[$week]++;
                }
            }

            // Previous month data
            $previousMonthData = [1 => 0, 2 => 0, 3 => 0, 4 => 0];
            $farmsPrevious = Farm::whereYear('created_at', $year)
                ->whereMonth('created_at', $previousMonth)
                ->get();
            foreach ($farmsPrevious as $farm) {
                $week = $getWeekOfMonth($farm->created_at);
                if (isset($previousMonthData[$week])) {
                    $previousMonthData[$week]++;
                }
            }

            // Total for current month
            $totalCurrent = array_sum($currentMonthData);
            $totalPrevious = array_sum($previousMonthData);
            $change = $totalPrevious > 0 ? round((($totalCurrent - $totalPrevious) / $totalPrevious) * 100, 2) : 0;

            return response()->json([
                'status' => true,
                'message' => 'Farm registration trends fetched successfully',
                'data' => [
                    'weeks' => ['الأسبوع 4', 'الأسبوع 3', 'الأسبوع 2', 'الأسبوع 1'],
                    'current_month' => array_values($currentMonthData),
                    'previous_month' => array_values($previousMonthData),
                    'total_current' => $totalCurrent,
                    'total_previous' => $totalPrevious,
                    'change_percent' => $change,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // User registration stats for line chart
    public function userRegistrationTrends(Request $request)
    {
        try {
            $now = now();
            $currentMonth = $now->month;
            $previousMonth = $now->copy()->subMonth()->month;
            $year = $now->year;

            $getWeekOfMonth = function($date) {
                $firstDay = $date->copy()->startOfMonth();
                return intval(floor(($date->day - 1 + $firstDay->dayOfWeek) / 7) + 1);
            };

            // Current month data
            $currentMonthData = [1 => 0, 2 => 0, 3 => 0, 4 => 0];
            $usersCurrent = User::whereYear('created_at', $year)
                ->whereMonth('created_at', $currentMonth)
                ->whereHas('roles', function ($query) {
                    $query->where('name', '!=', 'admin');
                })
                ->get();
            foreach ($usersCurrent as $user) {
                $week = $getWeekOfMonth($user->created_at);
                if (isset($currentMonthData[$week])) {
                    $currentMonthData[$week]++;
                }
            }

            // Previous month data
            $previousMonthData = [1 => 0, 2 => 0, 3 => 0, 4 => 0];
            $usersPrevious =User::whereYear('created_at', $year)
                ->whereMonth('created_at', $previousMonth)
                ->whereHas('roles', function ($query) {
                    $query->where('name', '!=', 'admin');
                })
                ->get();
            foreach ($usersPrevious as $user) {
                $week = $getWeekOfMonth($user->created_at);
                if (isset($previousMonthData[$week])) {
                    $previousMonthData[$week]++;
                }
            }

            $totalCurrent = array_sum($currentMonthData);
            $totalPrevious = array_sum($previousMonthData);
            $change = $totalPrevious > 0 ? round((($totalCurrent - $totalPrevious) / $totalPrevious) * 100, 2) : 0;

            return response()->json([
                'status' => true,
                'message' => 'User registration trends fetched successfully',
                'data' => [
                    'weeks' => ['الأسبوع 4', 'الأسبوع 3', 'الأسبوع 2', 'الأسبوع 1'],
                    'current_month' => array_values($currentMonthData),
                    'previous_month' => array_values($previousMonthData),
                    'total_current' => $totalCurrent,
                    'total_previous' => $totalPrevious,
                    'change_percent' => $change,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

        // Animal event type stats for all animals (admin/global)
        public function globalAnimalEventTypeStats(Request $request)
    {
        try {
            // Get all animals
            $animalIds = Animal::pluck('id');
            // Get all event types
            $eventTypes = EventType::all();
            // Count events for each type
            $eventTypeStats = [];
            $total = 0;
            foreach ($eventTypes as $eventType) {
                $count = Event::whereIn('animal_id', $animalIds)
                    ->where('eventType_id', $eventType->id)
                    ->count();
                $eventTypeStats[] = [
                    'name' => $eventType->name,
                    'count' => $count,
                ];
                $total += $count;
            }
            // Calculate percentages
            foreach ($eventTypeStats as &$stat) {
                $stat['percentage'] = $total > 0 ? round($stat['count'] / $total * 100, 2) : 0;
            }
            return response()->json([
                'status' => true,
                'message' => 'Global animal event type stats fetched successfully',
                'data' => $eventTypeStats,
                'total' => $total,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // Animal breed stats for all animals (admin/global)
    public function globalAnimalBreedStats(Request $request)
    {
        try {
            $breeds = \App\Models\AnimalBreed::all();
            $breedStats = [];
            foreach ($breeds as $breed) {
                $count = \App\Models\Animal::where('breed_id', $breed->id)->count();
                $breedStats[] = [
                    'name' => $breed->name,
                    'count' => $count,
                ];
            }
            return response()->json([
                'status' => true,
                'message' => 'Global animal breed stats fetched successfully',
                'data' => $breedStats,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // Most active farms stats for admin dashboard
    public function mostActiveFarms(Request $request)
    {
        try {
            $farms = \App\Models\Farm::all();
            $result = [];
            foreach ($farms as $farm) {
                $animalIds = $farm->animals()->pluck('id');
                // Health events (eventType_id = 1)
                $healthEvents = \App\Models\Event::whereIn('animal_id', $animalIds)
                    ->where('eventType_id', 1)
                    ->count();
                // Birth events (eventType_id = 2)
                $birthEvents = \App\Models\Event::whereIn('animal_id', $animalIds)
                    ->where('eventType_id', 2)
                    ->count();
                // Genetic analyses (random number for demo)
                $geneticAnalyses = rand(50, 150);
                $result[] = [
                    'farm_name' => $farm->name,
                    'health_events' => $healthEvents,
                    'birth_events' => $birthEvents,
                    'genetic_analyses' => $geneticAnalyses,
                    'total_activity' => $healthEvents + $birthEvents + $geneticAnalyses,
                ];
            }
            // Sort by total_activity desc and take top 5
            usort($result, function($a, $b) { return $b['total_activity'] <=> $a['total_activity']; });
            $result = array_slice($result, 0, 5);
            return response()->json([
                'status' => true,
                'message' => 'Most active farms fetched successfully',
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    
}

