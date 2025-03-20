<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    /**
     * List all roles
     */
    public function index()
    {
        try {
            $roles = Role::with('permissions')->get();

            return response()->json([
                'status' => 'success',
                'data' => $roles
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Create a new role
     */
    public function store(Request $request)
    {
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:roles,name',
                'permissions' => 'sometimes|array',
                'permissions.*' => 'exists:permissions,name'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors()
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            // Create role
            $role = Role::create([
                'name' => $request->input('name')
            ]);
            
            if ($request->has('permissions')) {
                $role->syncPermissions($request->input('permissions'));
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Role created successfully',
                'data' => $role->load('permissions')
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $role = Role::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|unique:roles,name,' . $role->id,
                'permissions' => 'sometimes|array',
                'permissions.*' => 'exists:permissions,name'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors()
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            if ($request->has('name')) {
                $role->name = $request->input('name');
                $role->save();
            }

            if ($request->has('permissions')) {
                $role->syncPermissions($request->input('permissions'));
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Role updated successfully',
                'data' => $role->load('permissions')
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function destroy($id)
    {
        try {
            $role = Role::findOrFail($id);
            $role->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Role deleted successfully'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}