<?php

namespace App\Http\Controllers;

use App\Models\EventType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EventTypeController extends Controller
{
    public function index(Request $request)
    {
        try {
            $eventTypes = EventType::select('id', 'name')->get();
            return response()->json([
                'status' => true,
                'message' => 'Event types fetched successfully',
                'data' => $eventTypes
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
            $eventType = EventType::find($id);
            if (!$eventType) {
                return response()->json(['status' => false, 'message' => 'Event type not found'], 404);
            }
            return response()->json(['status' => true, 'message' => 'Event type fetched successfully', 'data' => $eventType]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:event_types,name',
            'description' => 'nullable|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }
        try {
            $eventType = EventType::create($request->all());
            return response()->json(['status' => true, 'message' => 'Event type created successfully', 'data' => $eventType], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255|unique:event_types,name,' . $id,
            'description' => 'nullable|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }
        try {
            $eventType = EventType::find($id);
            if (!$eventType) {
                return response()->json(['status' => false, 'message' => 'Event type not found'], 404);
            }
            $eventType->update($request->all());
            return response()->json(['status' => true, 'message' => 'Event type updated successfully', 'data' => $eventType]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $eventType = EventType::find($id);
            if (!$eventType) {
                return response()->json(['status' => false, 'message' => 'Event type not found'], 404);
            }
            $eventType->delete();
            return response()->json(['status' => true, 'message' => 'Event type deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }
} 