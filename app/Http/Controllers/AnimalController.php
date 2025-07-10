<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Http\Resources\AnimalResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class AnimalController extends Controller
{
    public function index()
    {
        $animals = Animal::with(['farm', 'event', 'breed'])->paginate(10);
        return response()->json([
            'status' => true,
            'message' => 'Animals fetched successfully',
            'data' => AnimalResource::collection($animals),
        ]);
    }

    public function show($id)
    {
        $animal = Animal::with(['farm', 'event', 'breed'])->find($id);
        if (!$animal) {
            return response()->json(['status' => false, 'message' => 'Animal not found'], 404);
        }
        return response()->json([
            'status' => true,
            'message' => 'Animal fetched successfully',
            'data' => new AnimalResource($animal),
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'animal_id' => 'required|string|unique:animals,animal_id',
            'sir_id' => 'nullable|string',
            'dam_id' => 'nullable|string',
            'gender' => 'required|in:male,female',
            'farm_id' => 'required|exists:farms,id',
            'event_id' => 'nullable|exists:event_types,id',
            'breed_id' => 'nullable|exists:animal_breeds,id',
            'files.*' => 'nullable|file',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }
        DB::beginTransaction();
        try {
            $animal = Animal::create($request->only([
                'animal_id', 'sir_id', 'dam_id', 'gender', 'farm_id', 'event_id', 'breed_id',
            ]));
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $animal->addMedia($file)->toMediaCollection('files');
                }
            }
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Animal created successfully',
                'data' => new AnimalResource($animal),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $animal = Animal::find($id);
        if (!$animal) {
            return response()->json(['status' => false, 'message' => 'Animal not found'], 404);
        }
        $validator = Validator::make($request->all(), [
            'animal_id' => 'sometimes|required|string|unique:animals,animal_id,' . $id,
            'sir_id' => 'nullable|string',
            'dam_id' => 'nullable|string',
            'gender' => 'sometimes|required|in:male,female',
            'farm_id' => 'sometimes|required|exists:farms,id',
            'event_id' => 'nullable|exists:event_types,id',
            'breed_id' => 'nullable|exists:animal_breeds,id',
            'files.*' => 'nullable|file',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }
        DB::beginTransaction();
        try {
            $animal->update($request->only([
                'animal_id', 'sir_id', 'dam_id', 'gender', 'farm_id', 'event_id', 'breed_id',
            ]));
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $animal->addMedia($file)->toMediaCollection('files');
                }
            }
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Animal updated successfully',
                'data' => new AnimalResource($animal),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $animal = Animal::find($id);
        if (!$animal) {
            return response()->json(['status' => false, 'message' => 'Animal not found'], 404);
        }
        try {
            $animal->delete();
            return response()->json(['status' => true, 'message' => 'Animal deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }
} 