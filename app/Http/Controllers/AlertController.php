<?php

namespace App\Http\Controllers;

use App\Services\AlertService;
use Illuminate\Http\Request;
use App\Http\Resources\AlertResource;
use App\Helpers\PaginationHelper;
use Illuminate\Support\Facades\Auth;

class AlertController extends Controller
{
    protected $alertService;

    public function __construct(AlertService $alertService)
    {
        $this->alertService = $alertService;
    }

    public function index(Request $request)
    {
        try {
            $userId = Auth::id();
            $alerts = $this->alertService->getUserAlerts($userId)->paginate(10);
            
            return response()->json([
                'status' => true,
                'message' => 'Alerts fetched successfully',
                'data' => AlertResource::collection($alerts),
                'pagination' => PaginationHelper::paginate($alerts),
                'unread_count' => $this->alertService->getUnreadCount($userId),
            ]);
        } catch (\Exception $e) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Error fetching alerts',
                    'error' => $e->getMessage(),
                ],
                500,
            );
        }
    }

    public function show($id)
    {
        try {
            $userId = Auth::id();
            $alert = $this->alertService->getAlertById($id, $userId);
            
            if (!$alert) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'Alert not found',
                    ],
                    404,
                );
            }

            // Mark as read when viewed
            $this->alertService->markAsRead($id, $userId);

            return response()->json(
                [
                    'status' => true,
                    'message' => 'Alert fetched successfully',
                    'data' => new AlertResource($alert),
                ],
                200,
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Error fetching alert',
                    'error' => $e->getMessage(),
                ],
                500,
            );
        }
    }

    public function markAsRead($id)
    {
        try {
            $userId = Auth::id();
            $alert = $this->alertService->markAsRead($id, $userId);
            
            if (!$alert) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'Alert not found',
                    ],
                    404,
                );
            }

            return response()->json(
                [
                    'status' => true,
                    'message' => 'Alert marked as read successfully',
                    'data' => new AlertResource($alert),
                ],
                200,
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Error marking alert as read',
                    'error' => $e->getMessage(),
                ],
                500,
            );
        }
    }

    public function unreadCount()
    {
        try {
            $userId = Auth::id();
            $count = $this->alertService->getUnreadCount($userId);

            return response()->json(
                [
                    'status' => true,
                    'message' => 'Unread count fetched successfully',
                    'data' => [
                        'unread_count' => $count,
                    ],
                ],
                200,
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Error fetching unread count',
                    'error' => $e->getMessage(),
                ],
                500,
            );
        }
    }
} 