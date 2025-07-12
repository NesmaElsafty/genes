<?php

namespace App\Http\Controllers;

use App\Models\AnimalMating;
use App\Http\Resources\AnimalMatingResource;
use App\Services\AnimalMatingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Animal;
class AnimalMatingController extends Controller
{
    protected $animalMatingService;

    public function __construct(AnimalMatingService $animalMatingService)
    {
        $this->animalMatingService = $animalMatingService;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'sir_id' => 'required|exists:animals,id',
            'dam_id' => 'required|exists:animals,id',
            'mating_type' => 'required|in:artificial_ins,natural_mating',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $animalMating = $this->animalMatingService->createAnimalMating($request->all());
            // add event type to animal event history
            $animal = Animal::find($request->sir_id);
            $animal->animalEventHistory()->create([
                'name' => 'Mating',
            ]);
            $animal = Animal::find($request->dam_id);
            $animal->animalEventHistory()->create([
                'name' => 'Mating',
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Animal mating created successfully',
                'data' => new AnimalMatingResource($animalMating),
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $animalMating = $this->animalMatingService->getAnimalMatingById($id);
            if (!$animalMating) {
                return response()->json(['status' => false, 'message' => 'Animal mating not found'], 404);
            }
            return response()->json([
                'status' => true,
                'message' => 'Animal mating fetched successfully',
                'data' => new AnimalMatingResource($animalMating),
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $animalMating = $this->animalMatingService->getAnimalMatingById($id);
        if (!$animalMating) {
            return response()->json(['status' => false, 'message' => 'Animal mating not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'sir_id' => 'required|exists:animals,id',
            'dam_id' => 'required|exists:animals,id',
            'mating_type' => 'required|in:artificial_ins,natural_mating',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $animalMating = $this->animalMatingService->updateAnimalMating($id, $request->all());

            return response()->json([
                'status' => true,
                'message' => 'Animal mating updated successfully',
                'data' => new AnimalMatingResource($animalMating),
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $animalMating = $this->animalMatingService->getAnimalMatingById($id);
        if (!$animalMating) {
            return response()->json(['status' => false, 'message' => 'Animal mating not found'], 404);
        }

        try {
            $this->animalMatingService->deleteAnimalMating($id);
            return response()->json(['status' => true, 'message' => 'Animal mating deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getByAnimal($animalId)
    {
        try {
            $animalMatings = $this->animalMatingService->getMatingsByAnimal($animalId);
            return response()->json([
                'status' => true,
                'message' => 'Animal matings fetched successfully',
                'data' => AnimalMatingResource::collection($animalMatings),
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getByType($matingType)
    {
        try {
            $animalMatings = $this->animalMatingService->getMatingsByType($matingType);
            return response()->json([
                'status' => true,
                'message' => 'Animal matings fetched successfully',
                'data' => AnimalMatingResource::collection($animalMatings),
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }
} 