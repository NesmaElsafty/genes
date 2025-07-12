<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\UserResource;
use App\Helpers\PaginationHelper;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        try {
            $users = $this->userService->getAllUsers($request->all())->paginate(10);
            return response()->json([
                'status' => true,
                'message' => 'Users fetched successfully',
                'data' => UserResource::collection($users),
                'stats' => $this->userService->stats(),
                'pagination' => PaginationHelper::paginate($users),
            ]);
        } catch (\Exception $e) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Error fetching users',
                    'error' => $e->getMessage(),
                ],
                500,
            );
        }
    }

    public function show($id)
    {
        try {
            $user = $this->userService->getUserById($id);
            if (!$user) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'User not found',
                    ],
                    404,
                );
            }
            return response()->json(
                [
                    'status' => true,
                    'message' => 'User fetched successfully',
                    'data' => new UserResource($user),
                ],
                200,
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Error fetching user',
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
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'phone' => 'required|string|max:20',
                'is_active' => 'required|boolean',
                'password' => 'required|string|min:6',
                'role' => 'required|string|exists:roles,name',
            ]);
            $user = $this->userService->createUser($request->all());
            $user->syncRoles([$request->role]);

            return response()->json(
                [
                    'status' => true,
                    'message' => 'User created successfully',
                    'data' => new UserResource($user),
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
                'name' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|email|unique:users,email,' . $id,
                'phone' => 'sometimes|required|string|max:20',
                'is_active' => 'sometimes|required|boolean',
                'password' => 'sometimes|nullable|string|min:6',
                'role' => 'sometimes|string|exists:roles,name',
            ]);

            $user = $this->userService->updateUser($id, $request->all());
            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }
            $user->syncRoles([$request->role]);

            return response()->json(
                [
                    'status' => true,
                    'message' => 'User updated successfully',
                    'data' => new UserResource($user),
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

    // block list
    public function blockList(Request $request)
    {
        try {
            $blockedUsers = $this->userService->blockList()->paginate(10);
            return response()->json(
                [
                    'status' => true,
                    'message' => 'Blocked users fetched successfully',
                    'data' => UserResource::collection($blockedUsers),
                    'pagination' => PaginationHelper::paginate($blockedUsers),
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

    // block user
    public function block(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|exists:users,id',
            ]);
            $blocked = $this->userService->blockUser($request->id);
            
            return response()->json(
                [
                    'status' => true,
                    'message' => 'User blocked successfully',
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

    // bulk block users
    public function bulkBlock(Request $request)
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'required|exists:users,id',
            ]);
            $this->userService->bulkBlock($request->ids);
            return response()->json(
                [
                    'status' => true,
                    'message' => 'Users blocked successfully',
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

    // unblock user

    public function unblock(Request $request)
    {
        try {
            $this->userService->unblockUser($request->id);
            return response()->json(
                [
                    'status' => true,
                    'message' => 'User unblocked successfully',
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

    // bulk unblock users
    public function bulkUnblock(Request $request)
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'required|exists:users,id',
            ]);
            $this->userService->bulkUnblock($request->ids);
            return response()->json(
                [
                    'status' => true,
                    'message' => 'Users unblocked successfully',
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

    // activate and deactivate
    public function toggle(Request $request)
    {
        try {
            $this->userService->toggleUser($request->id);
            return response()->json(
                [
                    'status' => true,
                    'message' => 'User Activation/Deactivation successfully',
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

    // bulk activate and deactivate
    public function bulkToggle(Request $request)
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'required|exists:users,id',
            ]);
            $this->userService->bulkToggle($request->ids);
            return response()->json(
                [
                    'status' => true,
                    'message' => 'Users Activation/Deactivation successfully',
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
            $authUser = auth()->user();

            $request->validate([
                'ids' => 'nullable|array',
                'ids.*' => 'nullable|exists:users,id',
            ]);
            $ids = User::pluck('id')->toArray();
            if ($request->ids) {
                $ids = $request->ids;
            }

            $filePath = $this->userService->exportSheet($ids, $authUser);
            $filePath = str_replace('public/', '', $filePath);
            return response()->json(
                [
                    'status' => true,
                    'message' => 'تم تصدير المستخدمين بنجاح',
                    'data' => $filePath,
                ],
                200,
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'status' => false,
                    'message' => $e->getMessage(),
                    'data' => null,
                ],
                500,
            );
        }
    }
}
