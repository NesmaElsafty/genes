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
            // $perPage = $request->get('per_page', 10);
            $farms = $this->farmService->getAllFarms(10);
            return response()->json([
                'status' => true,
                'message' => 'Farms fetched successfully',
                'data' => FarmResource::collection($farms),
                'pagination' => PaginationHelper::paginate($farms),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
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
            'user_id' => 'required|exists:users,id',
            'city' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'capacity' => 'required|integer|min:1',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }
        try {
            $farm = $this->farmService->createFarm($request->all());
            return response()->json(
                [
                    'status' => true,
                    'message' => 'Farm created successfully',
                    'data' => new FarmResource($farm),
                ],
                201,
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Error creating farm',
                    'error' => $e->getMessage(),
                ],
                500,
            );
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'user_id' => 'sometimes|required|exists:users,id',
            'city' => 'sometimes|required|string|max:255',
            'location' => 'sometimes|required|string|max:255',
            'postal_code' => 'sometimes|required|string|max:20',
            'capacity' => 'sometimes|required|integer|min:1',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }
        try {
            $farm = $this->farmService->updateFarm($id, $request->all());
            if (!$farm) {
                return response()->json(['status' => false, 'message' => 'Farm not found'], 404);
            }
            return response()->json(
                [
                    'status' => true,
                    'message' => 'Farm updated successfully',
                    'data' => new FarmResource($farm),
                ],
                200,
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Error updating farm',
                    'error' => $e->getMessage(),
                ],
                500,
            );
        }
    }

    public function destroy($id)
    {
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
}
