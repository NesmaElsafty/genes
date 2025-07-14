<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Services\SettingService;
use Illuminate\Http\Request;
use App\Http\Resources\SettingResource;
use Mpdf\Mpdf;

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

    public function exportPdf()
    {
        $data = [
            'type' => 'شروط الاستخدام',
            'title' => 'سياسة الخصوصية',
            'description' => 'هذا نص عربي تجريبي للتصدير إلى PDF. يمكنك وضع أي نص تريده هنا.',
            'created_at' => now(),
        ];

        $html = view('exports.pdf_table', compact('data'))->render();

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'default_font' => 'dejavusans',
            'directionality' => 'rtl',
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
        ]);

        $mpdf->WriteHTML($html);

        return response($mpdf->Output('export.pdf', 'S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="export.pdf"'
        ]);
    }
} 