<?php

namespace App\Http\Controllers;

use App\Services\FarmService;
use App\Models\Farm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\PaginationHelper;
use App\Http\Resources\FarmResource;

class FarmController extends Controller
{
    protected $farmService;

    public function __construct(FarmService $farmService)
    {
        $this->farmService = $farmService;
    }

    public function index(Request $request)
    {
        try {
            $farms = $this->farmService->getAllFarms($request->all())->paginate(10);
            if(!auth()->user()->hasRole('admin')){
                $farms = $this->farmService->getAllUserFarms(auth()->user()->id, 10);
            }
            return response()->json([
                'status' => true,
                'message' => 'Farms fetched successfully',
                'data' => FarmResource::collection($farms),
                'stats' => $this->farmService->stats(),
                'pagination' => PaginationHelper::paginate($farms),
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

    // selectable farms
    public function selectableFarms(Request $request)
    {
            $farms = $this->farmService->selectableFarms();
            return response()->json(['status' => true, 'message' => 'Selectable farms fetched successfully', 'data' => $farms]);
        
    }

    public function show($id)
    {
        try {
            $farm = $this->farmService->getFarmById($id);
            if (!$farm) {
                return response()->json(['status' => false, 'message' => 'Farm not found'], 404);
            }
            return response()->json(
                [
                    'status' => true,
                    'message' => 'Farm fetched successfully',
                    'data' => new FarmResource($farm),
                ],
                200,
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Error fetching farm',
                    'error' => $e->getMessage(),
                ],
                500,
            );
        }
    }

    public function store(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'capacity' => 'required|integer|min:1',
            'animal_type_name' => 'nullable|string|max:255',
            'animal_breed_name' => 'nullable|string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $authUser = auth()->user();
        if (!$authUser->hasRole('admin')) {
            return response()->json(['status' => false, 'message' => 'You are not authorized to create a farm'], 403);
        }
        try {
            $farm = $this->farmService->createFarm($request->all());
            return response()->json(['status' => true, 'message' => 'Farm created successfully', 'data' => $farm], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Error creating farm', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'city' => 'sometimes|required|string|max:255',
            'location' => 'sometimes|required|string|max:255',
            'postal_code' => 'sometimes|required|string|max:20',
            'capacity' => 'sometimes|required|integer|min:1',
            'animal_type_name' => 'nullable|string|max:255',
            'animal_breed_name' => 'nullable|string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }
        $farm = Farm::find($id);
        if (!$farm) {
            return response()->json(['status' => false, 'message' => 'Farm not found'], 404);
        }
        if ($farm->user_id != auth()->user()->id) {
            return response()->json(['status' => false, 'message' => 'You are not authorized to update this farm'], 403);
        }
        try {
            $farm = $this->farmService->updateFarm($id, $request->all());
            if (!$farm) {
                return response()->json(['status' => false, 'message' => 'Farm not found'], 404);
            }
            return response()->json(['status' => true, 'message' => 'Farm updated successfully', 'data' => $farm]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Error updating farm', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $farm = Farm::find($id);
        if (!$farm) {
            return response()->json(['status' => false, 'message' => 'Farm not found'], 404);
        }
        if ($farm->user_id != auth()->user()->id) {
            return response()->json(['status' => false, 'message' => 'You are not authorized to delete this farm'], 403);
        }
        try {
            $deleted = $this->farmService->deleteFarm($id);
            if (!$deleted) {
                return response()->json(['status' => false, 'message' => 'Farm not found'], 404);
            }
            return response()->json(
                [
                    'status' => true,
                    'message' => 'Farm deleted successfully',
                ],
                200,
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Error deleting farm',
                    'error' => $e->getMessage(),
                ],
                500,
            );
        }
    }

    // export farms
    public function exportFarms(Request $request)
    {
        try {
            $farms = $this->farmService->exportSheet($request->ids, auth()->user());
            return response()->json(['status' => true, 'message' => 'Farms exported successfully', 'data' => $farms]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Error exporting farms', 'error' => $e->getMessage()], 500);
        }
    }
}
