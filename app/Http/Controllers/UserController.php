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

    public function index()
    {
        try {
            $users = $this->userService->getAllUsers()->paginate(10);
            return response()->json([
                'status' => true,
                'message' => 'Users fetched successfully',
                'data' => UserResource::collection($users),
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

    public function destroy($id)
    {
        try {
            $deleted = $this->userService->deleteUser($id);
            if (!$deleted) {
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
                    'message' => 'User deleted successfully',
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
