<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermissionsController extends BaseController
{
    public function index(Request $request)
    {
        $query = Role::with('permissions')->where('deleted_at', null);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        $totalRows = (clone $query)->count();
        $limit = (int) $request->get('limit', 10);
        $page = max((int) $request->get('page', 1), 1);
        $roles = $query
            ->orderBy('id', 'desc')
            ->offset(($page - 1) * $limit)
            ->limit($limit)
            ->get()
            ->map(function ($role) {
                return [
                    'id' => $role->id,
                    'name' => $role->name,
                    'permissions' => $role->permissions->pluck('name')->values(),
                ];
            });

        return response()->json([
            'roles' => $roles,
            'totalRows' => $totalRows,
            'permissions' => Permission::orderBy('name')->pluck('name')->values(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'permissions' => 'array',
        ]);

        $role = DB::transaction(function () use ($request) {
            $role = Role::create(['name' => $request->name]);
            $this->syncPermissions($role, $request->permissions ?: []);

            return $role;
        });

        return response()->json(['success' => true, 'role' => $role]);
    }

    public function edit($id)
    {
        $role = Role::with('permissions')->where('deleted_at', null)->findOrFail($id);

        return response()->json([
            'role' => $role,
            'permissions' => $role->permissions->pluck('name')->values(),
            'all_permissions' => Permission::orderBy('name')->pluck('name')->values(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'permissions' => 'array',
        ]);

        $role = Role::where('deleted_at', null)->findOrFail($id);
        DB::transaction(function () use ($request, $role) {
            $role->update(['name' => $request->name]);
            $this->syncPermissions($role, $request->permissions ?: []);
        });

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->update(['deleted_at' => now()]);

        return response()->json(['success' => true]);
    }

    public function delete_by_selection(Request $request)
    {
        $ids = $request->selectedIds ?: [];
        Role::whereIn('id', $ids)->update(['deleted_at' => now()]);

        return response()->json(['success' => true]);
    }

    public function Check_Create_Page()
    {
        return response()->json([
            'permissions' => Permission::orderBy('name')->pluck('name')->values(),
        ]);
    }

    private function syncPermissions(Role $role, array $permissions): void
    {
        $ids = [];
        foreach ($permissions as $permissionName) {
            $permission = Permission::firstOrCreate(['name' => $permissionName]);
            $ids[] = $permission->id;
        }

        $role->permissions()->sync($ids);
    }
}
