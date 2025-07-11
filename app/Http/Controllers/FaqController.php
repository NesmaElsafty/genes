<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Services\FaqService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\FaqResource;
use App\Helpers\PaginationHelper;

class FaqController extends Controller
{
    protected $faqService;

    public function __construct(FaqService $faqService)
    {
        $this->faqService = $faqService;
    }

    public function index(Request $request)
    {
        try {
            $faqs = $this->faqService->getAllFaqs($request->all(), null)->paginate(10);
            if(!auth()->user()->hasRole('admin')){
                $faqs = $this->faqService->getAllFaqs($request->all(), auth()->user()->role)->paginate(10);
            }
            // $faqs = $this->faqService->getAllFaqs($request->all())->paginate(10);
            return response()->json([
                'status' => true,
                'message' => 'FAQs fetched successfully',
                'data' => FaqResource::collection($faqs),
                // 'stats' => $this->faqService->stats(),
                'pagination' => PaginationHelper::paginate($faqs),
            ]);
        } catch (\Exception $e) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Error fetching FAQs',
                    'error' => $e->getMessage(),
                ],
                500,
            );
        }
    }

    public function show($id)
    {
        try {
            $faq = $this->faqService->getFaqById($id);
            if (!$faq) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'FAQ not found',
                    ],
                    404,
                );
            }
            return response()->json(
                [
                    'status' => true,
                    'message' => 'FAQ fetched successfully',
                    'data' => new FaqResource($faq),
                ],
                200,
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Error fetching FAQ',
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
                'question' => 'required|string|max:1000',
                'answer' => 'required|string|max:2000',
                'is_active' => 'sometimes|boolean',
                'role' => 'sometimes|string|max:255',
            ]);
            
            $faq = $this->faqService->createFaq($request->all());

            return response()->json(
                [
                    'status' => true,
                    'message' => 'FAQ created successfully',
                    'data' => new FaqResource($faq),
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
                'question' => 'sometimes|required|string|max:1000',
                'answer' => 'sometimes|required|string|max:2000',
                'is_active' => 'sometimes|boolean',
                'role' => 'sometimes|string|max:255',
            ]);

            $faq = $this->faqService->updateFaq($id, $request->all());
            if (!$faq) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'FAQ not found',
                    ],
                    404,
                );
            }

            return response()->json(
                [
                    'status' => true,
                    'message' => 'FAQ updated successfully',
                    'data' => new FaqResource($faq),
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
            $deleted = $this->faqService->deleteFaq($id);
            if (!$deleted) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'FAQ not found',
                    ],
                    404,
                );
            }

            return response()->json(
                [
                    'status' => true,
                    'message' => 'FAQ deleted successfully',
                ],
                200,
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Error deleting FAQ',
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
                'id' => 'required|exists:faqs,id',
            ]);
            
            $faq = $this->faqService->toggleFaq($request->id);
            if (!$faq) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'FAQ not found',
                    ],
                    404,
                );
            }

            return response()->json(
                [
                    'status' => true,
                    'message' => 'FAQ status toggled successfully',
                    'data' => new FaqResource($faq),
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

    public function bulkToggle(Request $request)
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'required|exists:faqs,id',
            ]);
            
            $this->faqService->bulkToggle($request->ids);
            
            return response()->json(
                [
                    'status' => true,
                    'message' => 'FAQs status toggled successfully',
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

    public function bulkDelete(Request $request)
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'required|exists:faqs,id',
            ]);
            
            $this->faqService->bulkDelete($request->ids);
            
            return response()->json(
                [
                    'status' => true,
                    'message' => 'FAQs deleted successfully',
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

    // export sheet
    public function exportSheet(Request $request)
    {
        try {
            $ids = $request->ids;
            $this->faqService->exportSheet($ids, auth()->user());
            return response()->json(
                [
                    'status' => true,
                    'message' => 'FAQs exported successfully',
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