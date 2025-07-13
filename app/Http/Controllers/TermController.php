<?php

namespace App\Http\Controllers;

use App\Models\Term;
use App\Services\TermService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\TermResource;
use App\Helpers\PaginationHelper;

class TermController extends Controller
{
    protected $termService;

    public function __construct(TermService $termService)
    {
        $this->termService = $termService;
    }

    public function index(Request $request)
    {
        try {
            $terms = $this->termService->getAllTerms($request->all(), null)->paginate(10);
            if(!auth()->user()->hasRole('admin')){
                $terms = $this->termService->getAllTerms($request->all(), auth()->user()->role)->paginate(10);
            }
            return response()->json([
                'status' => true,
                'message' => 'Terms fetched successfully',
                'data' => TermResource::collection($terms),
                'pagination' => PaginationHelper::paginate($terms),
            ]);
        } catch (\Exception $e) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Error fetching terms',
                    'error' => $e->getMessage(),
                ],
                500,
            );
        }
    }

    public function show($id)
    {
        try {
            $term = $this->termService->getTermById($id);
            if (!$term) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'Term not found',
                    ],
                    404,
                );
            }
            return response()->json(
                [
                    'status' => true,
                    'message' => 'Term fetched successfully',
                    'data' => new TermResource($term),
                ],
                200,
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Error fetching term',
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
                'type' => 'required|in:terms,privacy_policy',
                'title' => 'required|string|max:255',
                'description' => 'required|string|max:2000',
                'is_active' => 'sometimes|boolean',
                'role' => 'sometimes|string|max:255',
            ]);
            
            $term = $this->termService->createTerm($request->all());

            return response()->json(
                [
                    'status' => true,
                    'message' => 'Term created successfully',
                    'data' => new TermResource($term),
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
                'type' => 'sometimes|required|in:terms,privacy_policy',
                'title' => 'sometimes|required|string|max:255',
                'description' => 'sometimes|required|string|max:2000',
                'is_active' => 'sometimes|boolean',
                'role' => 'sometimes|string|max:255',
            ]);

            $term = $this->termService->updateTerm($id, $request->all());
            if (!$term) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'Term not found',
                    ],
                    404,
                );
            }

            return response()->json(
                [
                    'status' => true,
                    'message' => 'Term updated successfully',
                    'data' => new TermResource($term),
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
            $deleted = $this->termService->deleteTerm($id);
            if (!$deleted) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'Term not found',
                    ],
                    404,
                );
            }

            return response()->json(
                [
                    'status' => true,
                    'message' => 'Term deleted successfully',
                ],
                200,
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Error deleting term',
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
                'id' => 'required|exists:terms,id',
            ]);
            
            $term = $this->termService->toggleTerm($request->id);
            if (!$term) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'Term not found',
                    ],
                    404,
                );
            }

            return response()->json(
                [
                    'status' => true,
                    'message' => 'Term status toggled successfully',
                    'data' => new TermResource($term),
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
                'ids.*' => 'required|exists:terms,id',
            ]);
            
            $this->termService->bulkToggle($request->ids);
            
            return response()->json(
                [
                    'status' => true,
                    'message' => 'Terms status toggled successfully',
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
                'ids.*' => 'required|exists:terms,id',
            ]);
            
            $this->termService->bulkDelete($request->ids);
            
            return response()->json(
                [
                    'status' => true,
                    'message' => 'Terms deleted successfully',
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
            $file_name = $this->termService->exportSheet($ids, auth()->user());
            return response()->json(
                [
                    'status' => true,
                    'message' => 'Terms exported successfully',
                    "data" => $file_name,
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