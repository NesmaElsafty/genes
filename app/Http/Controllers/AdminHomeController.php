<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Farm;
use App\Models\User;
use App\Models\Animal;
use App\Models\EventType;
use App\Models\Event;
use App\Models\AnimalBreed;
class AdminHomeController extends Controller
{
    // Helper to apply date filter
    private function applyDateFilter($query, $request, $column = 'created_at')
    {
        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween($column, [$request->from, $request->to]);
        }
        return $query;
    }

    // stats for admin home page
    public function stats(Request $request)
    {
        try {
            $farmsQuery = $this->applyDateFilter(Farm::query(), $request);
            $totalFarms = $farmsQuery->count();

            $usersQuery = $this->applyDateFilter(
                User::whereHas('roles', function ($query) {
                    $query->where('name', '!=', 'admin');
                }),
                $request,
            );
            $totalUsers = $usersQuery->count();

            $animalsQuery = $this->applyDateFilter(Animal::query(), $request);
            $totalAnimals = $animalsQuery->count();

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
            $carbon = new \Carbon\Carbon();
            if ($request->filled('from') && $request->filled('to')) {
                $from = \Carbon\Carbon::parse($request->from)->startOfDay();
                $to = \Carbon\Carbon::parse($request->to)->endOfDay();
                $periodDays = $from->diffInDays($to) + 1;
                $prevTo = $from->copy()->subDay();
                $prevFrom = $prevTo->copy()->subDays($periodDays - 1);
            } else {
                $from = $now->copy()->startOfMonth();
                $to = $now->copy()->endOfMonth();
                $prevTo = $from->copy()->subDay();
                $prevFrom = $prevTo->copy()->startOfMonth();
            }

            // Helper to get week number in period
            $getWeekOfPeriod = function ($date, $periodStart) {
                return intval(floor($date->diffInDays($periodStart) / 7) + 1);
            };

            // Current period
            $farmsCurrent = Farm::whereBetween('created_at', [$from, $to])->get();
            $weeksCurrent = [];
            foreach ($farmsCurrent as $farm) {
                $week = $getWeekOfPeriod($farm->created_at, $from);
                if (!isset($weeksCurrent[$week])) {
                    $weeksCurrent[$week] = 0;
                }
                $weeksCurrent[$week]++;
            }

            // Previous period
            $farmsPrevious = Farm::whereBetween('created_at', [$prevFrom, $prevTo])->get();
            $weeksPrevious = [];
            foreach ($farmsPrevious as $farm) {
                $week = $getWeekOfPeriod($farm->created_at, $prevFrom);
                if (!isset($weeksPrevious[$week])) {
                    $weeksPrevious[$week] = 0;
                }
                $weeksPrevious[$week]++;
            }

            // Determine max number of weeks in either period
            $allWeeks = array_keys($weeksCurrent + $weeksPrevious);
            $maxWeek = !empty($allWeeks) ? max($allWeeks) : 1;
            $weekLabels = [];
            $currentMonthData = [];
            $previousMonthData = [];
            for ($i = 1; $i <= $maxWeek; $i++) {
                $weekLabels[] = "الأسبوع $i";
                $currentMonthData[] = $weeksCurrent[$i] ?? 0;
                $previousMonthData[] = $weeksPrevious[$i] ?? 0;
            }

            $totalCurrent = array_sum($currentMonthData);
            $totalPrevious = array_sum($previousMonthData);
            $change = $totalPrevious > 0 ? round((($totalCurrent - $totalPrevious) / $totalPrevious) * 100, 2) : 0;

            return response()->json([
                'status' => true,
                'message' => 'Farm registration trends fetched successfully',
                'data' => [
                    'weeks' => $weekLabels,
                    'current_period' => $currentMonthData,
                    'previous_period' => $previousMonthData,
                    'total_current' => $totalCurrent,
                    'total_previous' => $totalPrevious,
                    'change_percent' => $change,
                    'current_period_range' => [$from->toDateString(), $to->toDateString()],
                    'previous_period_range' => [$prevFrom->toDateString(), $prevTo->toDateString()],
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

    // User registration stats for line chart
    public function userRegistrationTrends(Request $request)
    {
        try {
            $now = now();
            $currentMonth = $now->month;
            $previousMonth = $now->copy()->subMonth()->month;
            $year = $now->year;

            $getWeekOfMonth = function ($date) {
                $firstDay = $date->copy()->startOfMonth();
                return intval(floor(($date->day - 1 + $firstDay->dayOfWeek) / 7) + 1);
            };

            // Current month data
            $currentMonthData = [1 => 0, 2 => 0, 3 => 0, 4 => 0];
            $usersCurrent = $this->applyDateFilter(
                User::whereYear('created_at', $year)
                    ->whereMonth('created_at', $currentMonth)
                    ->whereHas('roles', function ($query) {
                        $query->where('name', '!=', 'admin');
                    }),
                $request,
            )->get();
            foreach ($usersCurrent as $user) {
                $week = $getWeekOfMonth($user->created_at);
                if (isset($currentMonthData[$week])) {
                    $currentMonthData[$week]++;
                }
            }

            // Previous month data
            $previousMonthData = [1 => 0, 2 => 0, 3 => 0, 4 => 0];
            $usersPrevious = $this->applyDateFilter(
                User::whereYear('created_at', $year)
                    ->whereMonth('created_at', $previousMonth)
                    ->whereHas('roles', function ($query) {
                        $query->where('name', '!=', 'admin');
                    }),
                $request,
            )->get();
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
            return response()->json(
                [
                    'status' => false,
                    'message' => $e->getMessage(),
                ],
                500,
            );
        }
    }

    // Animal event type stats for all animals (admin/global)
    public function globalAnimalEventTypeStats(Request $request)
    {
        try {
            $animalIds = $this->applyDateFilter(Animal::query(), $request)->pluck('id');
            $eventTypes = EventType::all();
            $eventTypeStats = [];
            $total = 0;
            foreach ($eventTypes as $eventType) {
                $count = $this->applyDateFilter(Event::whereIn('animal_id', $animalIds)->where('eventType_id', $eventType->id), $request)->count();
                $eventTypeStats[] = [
                    'name' => $eventType->name,
                    'count' => $count,
                ];
                $total += $count;
            }
            foreach ($eventTypeStats as &$stat) {
                $stat['percentage'] = $total > 0 ? round(($stat['count'] / $total) * 100, 2) : 0;
            }
            return response()->json([
                'status' => true,
                'message' => 'Global animal event type stats fetched successfully',
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

    // Animal breed stats for all animals (admin/global)
    public function globalAnimalBreedStats(Request $request)
    {
        try {
            $breeds = AnimalBreed::all();
            $breedStats = [];
            foreach ($breeds as $breed) {
                $count = $this->applyDateFilter(Animal::where('breed_id', $breed->id), $request)->count();
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
            return response()->json(
                [
                    'status' => false,
                    'message' => $e->getMessage(),
                ],
                500,
            );
        }
    }

    // Most active farms stats for admin dashboard
    public function mostActiveFarms(Request $request)
    {
        try {
            $farms = $this->applyDateFilter(Farm::query(), $request)->get();
            $result = [];
            foreach ($farms as $farm) {
                $animalIds = $this->applyDateFilter($farm->animals(), $request)->pluck('id');
                // Health events (eventType_id = 1)
                $healthEvents = $this->applyDateFilter(Event::whereIn('animal_id', $animalIds)->where('eventType_id', 1), $request)->count();
                // Birth events (eventType_id = 2)
                $birthEvents = $this->applyDateFilter(Event::whereIn('animal_id', $animalIds)->where('eventType_id', 2), $request)->count();
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
            usort($result, function ($a, $b) {
                return $b['total_activity'] <=> $a['total_activity'];
            });
            $result = array_slice($result, 0, 5);
            return response()->json([
                'status' => true,
                'message' => 'Most active farms fetched successfully',
                'data' => $result,
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
