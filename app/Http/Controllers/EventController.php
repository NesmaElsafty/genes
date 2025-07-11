<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Http\Resources\EventResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Helpers\PaginationHelper;
use App\Services\EventService;

class EventController extends Controller
{
    protected $eventService;
    public function __construct(EventService $eventService)
    {
        $this->eventService = $eventService;
    }

    public function index()
    {
        $events = Event::with(['eventType', 'animal'])->paginate(10);
        return response()->json([
            'status' => true,
            'message' => 'Events fetched successfully',
            'data' => EventResource::collection($events),
            'pagination' => PaginationHelper::paginate($events),
        ]);
    }

    public function show($id)
    {
        $event = Event::with(['eventType', 'animal'])->find($id);
        if (!$event) {
            return response()->json(['status' => false, 'message' => 'Event not found'], 404);
        }
        return response()->json([
            'status' => true,
            'message' => 'Event fetched successfully',
            'data' => new EventResource($event),
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'event_type_id' => 'required|exists:event_types,id',
            'animal_id' => 'required|exists:animals,id',
            'note' => 'nullable|string',
            'files.*' => 'nullable|file',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }
        $authUser = auth()->user();
        if ($authUser->hasRole('admin')) {
            return response()->json(['status' => false, 'message' => 'You are not authorized to create an event'], 403);
        }
        DB::beginTransaction();
        try {
            // use service to create event
            $event = $this->eventService->createEvent($request->all());
           
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Event created successfully',
                'data' => new EventResource($event),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $event = Event::find($id);
        if (!$event) {
            return response()->json(['status' => false, 'message' => 'Event not found'], 404);
        }
        $validator = Validator::make($request->all(), [
            'date' => 'sometimes|required|date',
            'event_type_id' => 'sometimes|required|exists:event_types,id',
            'animal_id' => 'sometimes|required|exists:animals,id',
            'note' => 'nullable|string',
            'files.*' => 'nullable|file',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }
        DB::beginTransaction();
        try {
            $event->update($request->only([
                'date', 'event_type_id', 'animal_id', 'note',
            ]));
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $event->addMedia($file)->toMediaCollection('files');
                }
            }
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Event updated successfully',
                'data' => new EventResource($event),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $event = Event::find($id);
        if (!$event) {
            return response()->json(['status' => false, 'message' => 'Event not found'], 404);
        }
        try {
            $event->delete();
            return response()->json(['status' => true, 'message' => 'Event deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }
} 