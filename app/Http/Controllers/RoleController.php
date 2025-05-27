<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    // GET /get-roles
    public function getRoles()
    {
        $roles = Role::all();
        return response()->json($roles);
    }

    // POST /add-roles
    public function addRole(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $role = Role::create($request->all());

        return response()->json([
            'message' => 'Role added successfully',
            'role' => $role
        ], 201);
    }

    // PUT /edit-roles/{id}
    public function editRole(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
        ]);

        $role->update($request->all());

        return response()->json([
            'message' => 'Role updated successfully',
            'role' => $role
        ]);
    }

    // DELETE /delete-roles/{id}
    public function deleteRole($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return response()->json([
            'message' => 'Role deleted successfully'
        ]);
    }
}
