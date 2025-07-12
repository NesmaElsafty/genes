<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use App\Http\Resources\NotificationResource;
use App\Helpers\PaginationHelper;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index(Request $request)
    {
        try {
            $notifications = $this->notificationService->getAllNotifications($request->all())->paginate(10);
            return response()->json([
                'status' => true,
                'message' => 'Notifications fetched successfully',
                'data' => NotificationResource::collection($notifications),
                'stats' => $this->notificationService->stats(),
                'pagination' => PaginationHelper::paginate($notifications),
            ]);
        } catch (\Exception $e) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Error fetching notifications',
                    'error' => $e->getMessage(),
                ],
                500,
            );
        }
    }

    public function show($id)
    {
        try {
            $notification = $this->notificationService->getNotificationById($id);
            if (!$notification) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'Notification not found',
                    ],
                    404,
                );
            }
            return response()->json(
                [
                    'status' => true,
                    'message' => 'Notification fetched successfully',
                    'data' => new NotificationResource($notification),
                ],
                200,
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Error fetching notification',
                    'error' => $e->getMessage(),
                ],
                500,
            );
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'type' => 'required|in:alert,notify',
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'role' => 'required|string',
                'status' => 'sometimes|in:pending,sent,unsent',
            ]);

            $notification = $this->notificationService->createNotification($request->all());

            return response()->json(
                [
                    'status' => true,
                    'message' => 'Notification created successfully',
                    'data' => new NotificationResource($notification),
                ],
                201,
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'status' => false,
                    'message' => $e->getMessage(),
                ],
                500,
            );
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'type' => 'sometimes|required|in:alert,notify',
                'title' => 'sometimes|required|string|max:255',
                'description' => 'sometimes|required|string',
                'role' => 'sometimes|required|string',
                'status' => 'sometimes|in:pending,sent,unsent',
            ]);

            $notification = $this->notificationService->updateNotification($id, $request->all());
            if (!$notification) {
                return response()->json(['error' => 'Notification not found'], 404);
            }

            return response()->json(
                [
                    'status' => true,
                    'message' => 'Notification updated successfully',
                    'data' => new NotificationResource($notification),
                ],
                200,
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'status' => false,
                    'message' => $e->getMessage(),
                ],
                500,
            );
        }
    }

    public function destroy($id)
    {
        try {
            $deleted = $this->notificationService->deleteNotification($id);
            if (!$deleted) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'Notification not found',
                    ],
                    404,
                );
            }

            return response()->json(
                [
                    'status' => true,
                    'message' => 'Notification deleted successfully',
                ],
                200,
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Error deleting notification',
                    'error' => $e->getMessage(),
                ],
                500,
            );
        }
    }

    public function bulkDelete(Request $request)
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'required|integer|exists:notifications,id',
            ]);

            $this->notificationService->bulkDelete($request->ids);

            return response()->json(
                [
                    'status' => true,
                    'message' => 'Notifications deleted successfully',
                ],
                200,
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'status' => false,
                    'message' => $e->getMessage(),
                ],
                500,
            );
        }
    }

    public function sendNotification($id)
    {
        try {
            $notification = $this->notificationService->sendNotification($id);
            if (!$notification) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'Notification not found',
                    ],
                    404,
                );
            }

            return response()->json(
                [
                    'status' => true,
                    'message' => 'Notification sent successfully',
                    'data' => new NotificationResource($notification),
                ],
                200,
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Error sending notification',
                    'error' => $e->getMessage(),
                ],
                500,
            );
        }
    }

    public function exportSheet(Request $request)
    {
        try {
            $request->validate([
                'ids' => 'sometimes|array',
                'ids.*' => 'sometimes|integer|exists:notifications,id',
            ]);

            $ids = $request->ids ?? [];
            // get the result by spatie media library
            $result = $this->notificationService->exportSheet($ids, auth()->user());
            return response()->json(
                [
                    'status' => true,
                    'message' => 'Export completed successfully',
                    'data' => $result,
                ],
                200,
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Error exporting notifications',
                    'error' => $e->getMessage(),
                ],
                500,
            );
        }
    }
} 