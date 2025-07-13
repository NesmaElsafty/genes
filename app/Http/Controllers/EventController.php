<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Http\Resources\EventResource;
use App\Services\EventService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Animal;
use App\Models\AnimalEventType;
use App\Services\AlertService;
use App\Services\SettingService;
use App\Models\User;

class EventController extends Controller
{
    protected $eventService;
    protected $alertService;
    protected $settingService;

    public function __construct(EventService $eventService, AlertService $alertService, SettingService $settingService)
    {
        $this->eventService = $eventService;
        $this->alertService = $alertService;
        $this->settingService = $settingService;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'animal_id' => 'required|exists:animals,id',
            'date' => 'required|date',
            'eventType_id' => 'required|exists:event_types,id',
            'notes' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $event = $this->eventService->createEvent($request->all());
            // create animal event type
            $animal = Animal::find($request->animal_id);
            $animalEventType = new AnimalEventType();
            $animalEventType->animal_id = $animal->id;
            $animalEventType->name = $event->eventType->name;
            $animalEventType->save();

            $animal = Animal::find($request->animal_id);

            // send notification to admin
            if ($this->settingService->notificationIsValid('health_event_registration')) {
                $admins = User::where('role', 'admin')->get();
                foreach ($admins as $admin) {
                    // create alert
                    $data = [
                        'title' => 'تم تسجيل حدث جديد',
                        'message' => 'تم تسجيل حدث جديد داخل ' . $animal->farm->name . ' برقم ' . $animal->animal_id . ' بنوع ' . $event->eventType->name,
                        'is_read' => false,
                    ];
                    $this->alertService->createAlert($data, $admin->id);
                }
            }

           
            return response()->json([
                'status' => true,
                'message' => 'Event created successfully',
                'data' => new EventResource($event),
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $event = $this->eventService->getEventById($id);
            if (!$event) {
                return response()->json(['status' => false, 'message' => 'Event not found'], 404);
            }
            return response()->json([
                'status' => true,
                'message' => 'Event fetched successfully',
                'data' => new EventResource($event),
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $event = $this->eventService->getEventById($id);
        if (!$event) {
            return response()->json(['status' => false, 'message' => 'Event not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'animal_id' => 'nullable|exists:animals,id',
            'date' => 'nullable|date',
            'eventType_id' => 'nullable|exists:event_types,id',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $event = $this->eventService->updateEvent($id, $request->all());
            // update animal event type
            return response()->json([
                'status' => true,
                'message' => 'Event updated successfully',
                'data' => new EventResource($event),
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $event = $this->eventService->getEventById($id);
        if (!$event) {
            return response()->json(['status' => false, 'message' => 'Event not found'], 404);
        }

        try {
            $this->eventService->deleteEvent($id);
            return response()->json(['status' => true, 'message' => 'Event deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getByAnimal($animalId)
    {
        try {
            $events = $this->eventService->getEventsByAnimal($animalId);
            return response()->json([
                'status' => true,
                'message' => 'Events fetched successfully',
                'data' => EventResource::collection($events),
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }
} 