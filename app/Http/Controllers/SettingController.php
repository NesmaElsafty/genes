<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Services\SettingService;
use Illuminate\Http\Request;
use App\Http\Resources\SettingResource;

class SettingController extends Controller
{
    protected $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    public function index()
    {
        try {
            $settings = $this->settingService->getData();
            return response()->json([
                'status' => true,
                'message' => 'Settings fetched successfully',
                'data' => SettingResource::collection($settings),
            ]);
        } catch (\Exception $e) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Error fetching settings',
                    'error' => $e->getMessage(),
                ],
                500,
            );
        }
    }

    public function show($id)
    {
        try {
            $setting = $this->settingService->getSettingById($id);
            if (!$setting) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'Setting not found',
                    ],
                    404,
                );
            }
            return response()->json(
                [
                    'status' => true,
                    'message' => 'Setting fetched successfully',
                    'data' => new SettingResource($setting),
                ],
                200,
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Error fetching setting',
                    'error' => $e->getMessage(),
                ],
                500,
            );
        }
    }
    public function toggle(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|exists:settings,id',
            ]);
            $setting = $this->settingService->toggleSetting($request->id);
            
            if (!$setting) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'Setting not found',
                    ],
                    404,
                );
            }

            return response()->json(
                [
                    'status' => true,
                    'message' => 'Setting status toggled successfully',
                    'data' => new SettingResource($setting),
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
} 