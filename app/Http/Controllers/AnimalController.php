<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Http\Resources\AnimalResource;
use App\Services\AnimalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\PaginationHelper;

class AnimalController extends Controller
{
    protected $animalService;

    public function __construct(AnimalService $animalService)
    {
        $this->animalService = $animalService;
    }

    public function index(Request $request)
    {
        $authUser = auth()->user();
        $animals = $this->animalService->getAllAnimals($request->all())->paginate(10);

        if (!$authUser->hasRole('admin')) {
            $animals = $this->animalService->getAnimalsByFarmId($request->all(), $authUser->farms->pluck('id'))->paginate(10);
        }

        return response()->json([
            'status' => true,
            'message' => 'Animals fetched successfully',
            'data' => AnimalResource::collection($animals),
            'pagination' => PaginationHelper::paginate($animals),
        ]);
    }

    public function show($id)
    {
        $animal = $this->animalService->getAnimalById($id);
        if (!$animal) {
            return response()->json(['status' => false, 'message' => 'Animal not found'], 404);
        }
        return response()->json([
            'status' => true,
            'message' => 'Animal fetched successfully',
            'data' => new AnimalResource($animal),
        ]);
    }

    // get animals by gender
    public function getAnimalsByGender(Request $request)
    {
        try {
            $animals = $this->animalService->getAnimalsByGender($request->gender);
            return response()->json(['status' => true, 'message' => 'Animals fetched successfully', 'data' => $animals]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'animal_id' => 'required|string|unique:animals,animal_id',
                'sir_id' => 'required|string',
                'dam_id' => 'required|string',
                'birth_date' => 'required|date',
                'gender' => 'required|in:male,female',
                'farm_id' => 'required|exists:farms,id',
                'event_type_id' => 'required|exists:event_types,id',
                'breed_id' => 'required|exists:animal_breeds,id',
                'animal_type_id' => 'required|exists:animal_types,id',
                'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
            }

            $animal = $this->animalService->createAnimal($request->all());
            // store images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $animal->addMedia($image)->toMediaCollection('images');
                }
            }

            return response()->json(
                [
                    'status' => true,
                    'message' => 'Animal created successfully',
                    'data' => new AnimalResource($animal),
                ],
                201,
            );
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $animal = $this->animalService->getAnimalById($id);
        if (!$animal) {
            return response()->json(['status' => false, 'message' => 'Animal not found'], 404);
        }
        $validator = Validator::make($request->all(), [
            'animal_id' => 'sometimes|required|string|unique:animals,animal_id,' . $id,
            'sir_id' => 'nullable|string',
            'dam_id' => 'nullable|string',
            'gender' => 'sometimes|required|in:male,female',
            'farm_id' => 'sometimes|required|exists:farms,id',
            'event_type_id' => 'nullable|exists:event_types,id',
            'breed_id' => 'nullable|exists:animal_breeds,id',
            'animal_type_id' => 'nullable|exists:animal_types,id',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $animal = $this->animalService->updateAnimal($id, $request->all());
            // store images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $animal->addMedia($image)->toMediaCollection('images');
                }
            }
            return response()->json([
                'status' => true,
                'message' => 'Animal updated successfully',
                'data' => new AnimalResource($animal),
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $animal = $this->animalService->getAnimalById($id);
        if (!$animal) {
            return response()->json(['status' => false, 'message' => 'Animal not found'], 404);
        }
        try {
            $this->animalService->deleteAnimal($id);
            return response()->json(['status' => true, 'message' => 'Animal deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
