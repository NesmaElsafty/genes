<?php

namespace App\Http\Controllers;

use App\Models\AnimalType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AnimalTypeController extends Controller
{
    public function index(Request $request)
    {
        try {
            $animalTypes = AnimalType::select('id', 'name')->get();
            return response()->json([
                'status' => true,
                'message' => 'Animal types fetched successfully',
                'data' => $animalTypes
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
            $animalType = AnimalType::find($id);
            if (!$animalType) {
                return response()->json(['status' => false, 'message' => 'Animal type not found'], 404);
            }
            return response()->json(['status' => true, 'message' => 'Animal type fetched successfully', 'data' => $animalType]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:animal_types,name',
            'description' => 'nullable|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }
        try {
            $animalType = AnimalType::create($request->all());
            return response()->json(['status' => true, 'message' => 'Animal type created successfully', 'data' => $animalType], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255|unique:animal_types,name,' . $id,
            'description' => 'nullable|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }
        try {
            $animalType = AnimalType::find($id);
            if (!$animalType) {
                return response()->json(['status' => false, 'message' => 'Animal type not found'], 404);
            }
            $animalType->update($request->all());
            return response()->json(['status' => true, 'message' => 'Animal type updated successfully', 'data' => $animalType]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $animalType = AnimalType::find($id);
            if (!$animalType) {
                return response()->json(['status' => false, 'message' => 'Animal type not found'], 404);
            }
            $animalType->delete();
            return response()->json(['status' => true, 'message' => 'Animal type deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
