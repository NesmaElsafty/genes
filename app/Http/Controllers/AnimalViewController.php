<?php

namespace App\Http\Controllers;

use App\Models\AnimalView;
use App\Http\Resources\AnimalViewResource;
use App\Services\AnimalViewService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\PaginationHelper;

class AnimalViewController extends Controller
{
    protected $animalViewService;

    public function __construct(AnimalViewService $animalViewService)
    {
        $this->animalViewService = $animalViewService;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'animal_id' => 'required|exists:animals,id',
            'external_chars' => 'required|string',
            'value' => 'required|string',
            'expects' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $animalView = $this->animalViewService->createAnimalView($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Animal view created successfully',
                'data' => new AnimalViewResource($animalView),
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
        $animalView = $this->animalViewService->getAnimalViewById($id);
        if (!$animalView) {
                return response()->json(['status' => false, 'message' => 'Animal view not found'], 404);
            }
            return response()->json([
                'status' => true,
                'message' => 'Animal view fetched successfully',
                'data' => new AnimalViewResource($animalView),
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $animalView = $this->animalViewService->getAnimalViewById($id);
        if (!$animalView) {
            return response()->json(['status' => false, 'message' => 'Animal view not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'animal_id' => 'required|exists:animals,id',
            'external_chars' => 'required|string',
            'value' => 'required|string',
            'expects' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $animalView = $this->animalViewService->updateAnimalView($id, $request->all());

            return response()->json([
                'status' => true,
                'message' => 'Animal view updated successfully',
                'data' => new AnimalViewResource($animalView),
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $animalView = $this->animalViewService->getAnimalViewById($id);
        if (!$animalView) {
            return response()->json(['status' => false, 'message' => 'Animal view not found'], 404);
        }

        try {
            $this->animalViewService->deleteAnimalView($id);
            return response()->json(['status' => true, 'message' => 'Animal view deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getByAnimal($animalId)
    {
        try {
            $animalViews = $this->animalViewService->getAnimalViewsByAnimal($animalId, 10);
            return response()->json([
                'status' => true,
                'message' => 'Animal views fetched successfully',
                'data' => AnimalViewResource::collection($animalViews),
                'pagination' => PaginationHelper::paginate($animalViews),
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'search' => 'required|string|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $animalViews = $this->animalViewService->searchAnimalViews($request->search, 10);
            return response()->json([
                'status' => true,
                'message' => 'Animal views fetched successfully',
                'data' => AnimalViewResource::collection($animalViews),
                'pagination' => PaginationHelper::paginate($animalViews),
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }
} 