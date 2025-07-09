<?php

namespace App\Http\Controllers;

use App\Models\AnimalBreed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AnimalBreedController extends Controller
{
    public function index(Request $request)
    {
        try {
            $animalBreeds = AnimalBreed::select('id', 'name')->get();
            return response()->json([
                'status' => true,
                'message' => 'Animal breeds fetched successfully',
                'data' => $animalBreeds
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
            $animalBreed = AnimalBreed::with('animalType')->find($id);
            if (!$animalBreed) {
                return response()->json(['status' => false, 'message' => 'Animal breed not found'], 404);
            }
            return response()->json(['status' => true, 'message' => 'Animal breed fetched successfully', 'data' => $animalBreed]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:animal_breeds,name',
            'description' => 'nullable|string',
            'animal_type_id' => 'required|exists:animal_types,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }
        try {
            $animalBreed = AnimalBreed::create($request->all());
            return response()->json(['status' => true, 'message' => 'Animal breed created successfully', 'data' => $animalBreed], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255|unique:animal_breeds,name,' . $id,
            'description' => 'nullable|string',
            'animal_type_id' => 'sometimes|required|exists:animal_types,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }
        try {
            $animalBreed = AnimalBreed::find($id);
            if (!$animalBreed) {
                return response()->json(['status' => false, 'message' => 'Animal breed not found'], 404);
            }
            $animalBreed->update($request->all());
            return response()->json(['status' => true, 'message' => 'Animal breed updated successfully', 'data' => $animalBreed]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $animalBreed = AnimalBreed::find($id);
            if (!$animalBreed) {
                return response()->json(['status' => false, 'message' => 'Animal breed not found'], 404);
            }
            $animalBreed->delete();
            return response()->json(['status' => true, 'message' => 'Animal breed deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
