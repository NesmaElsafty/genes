<?php

namespace App\Http\Controllers;

use App\Services\RoleService;
use App\Http\Resources\RoleResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    protected $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function index()
    {
        try {
            $roles = $this->roleService->getAll();
            $permissions = Permission::all()->pluck('name');
            return response()->json(
                [
                    'status' => true,
                    'message' => 'Roles fetched successfully',
                    'data' => [
                        'roles' => RoleResource::collection($roles),
                        'permissions' => $permissions,
                    ],
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

    public function show($id)
    {
        try {
            $role = $this->roleService->getById($id);
            if (!$role) {
                return response()->json(['error' => 'Role not found'], 404);
            }
            return response()->json(
                [
                    'status' => true,
                    'message' => 'Role fetched successfully',
                    'data' => new RoleResource($role),
                ],
                200,
            );
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'sometimes|array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        try {
            $role = $this->roleService->create($request->all());
            return response()->json(
                [
                    'status' => true,
                    'message' => 'Role created successfully',
                    'data' => new RoleResource($role),
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
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|unique:roles,name,' . $id,
            'permissions' => 'sometimes|array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        try {
            $role = $this->roleService->update($id, $request->all());
            if (!$role) {
                return response()->json(['error' => 'Role not found'], 404);
            }
            return response()->json(
                [
                    'status' => true,
                    'message' => 'Role updated successfully',
                    'data' => new RoleResource($role),
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
            $deleted = $this->roleService->delete($id);
            if (!$deleted) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'Role not found',
                    ],
                    404,
                );
            }
            return response()->json(
                [
                    'status' => true,
                    'message' => 'Role deleted successfully',
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
