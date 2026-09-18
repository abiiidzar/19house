<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Services\System\ActivityLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class RoleController extends Controller
{
    public function index(Request $request): View
    {
        $roles = Role::withCount(['users', 'permissions'])
            ->when($request->query('search'), fn ($query, $search) => $query->where(fn ($query) => $query
                ->where('name', 'like', '%'.$search.'%')
                ->orWhere('slug', 'like', '%'.$search.'%')))
            ->orderBy('name')->get();

        return view('admin.roles.index', compact('roles'));
    }

    public function edit(Role $role): View
    {
        abort_unless(in_array($role->slug, ['cashier', 'management'], true), 403);
        $role->load('permissions');
        $permissions = Permission::orderBy('slug')->get();

        return view('admin.roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role, ActivityLogService $logs): RedirectResponse
    {
        abort_unless(in_array($role->slug, ['cashier', 'management'], true), 403);
        $data = $request->validate([
            'permissions' => ['array'],
            'permissions.*' => ['integer', 'distinct', 'exists:permissions,id'],
        ]);
        $selected = $data['permissions'] ?? [];
        DB::transaction(function () use ($role, $selected, $logs, $request) {
            $old = $role->permissions()->orderBy('permissions.slug')->pluck('permissions.slug')->all();
            $role->permissions()->sync($selected);
            $new = $role->permissions()->orderBy('permissions.slug')->pluck('permissions.slug')->all();
            if ($old !== $new) {
                $logs->log('user.role_permissions_changed', $role, ['permissions' => $old], ['permissions' => $new], $request->user());
            }
        });

        return redirect()->route('admin.roles.index')->with('success', 'Role permissions updated.');
    }
}
