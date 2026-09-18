<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $users = User::with('role')->whereHas('role', fn ($query) => $query->whereIn('slug', ['admin', 'cashier', 'management']))->when($request->query('search'), function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', '%'.$search.'%')->orWhere('email', 'like', '%'.$search.'%');
            });
        })->latest()->paginate(20)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function create(): View
    {
        return view('admin.users.form', ['user' => new User, 'roles' => Role::whereIn('slug', ['admin', 'cashier', 'management'])->orderBy('name')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        User::create($data);

        return redirect()->route('admin.users.index')->with('success', 'User created.');
    }

    public function edit(User $user): View
    {
        abort_unless($user->hasAnyRole(['admin', 'cashier', 'management']), 404);

        return view('admin.users.form', ['user' => $user, 'roles' => Role::whereIn('slug', ['admin', 'cashier', 'management'])->orderBy('name')->get()]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        abort_unless($user->hasAnyRole(['admin', 'cashier', 'management']), 404);
        $data = $this->validated($request, $user);
        if ($user->is($request->user()) && ($data['status'] !== 'ACTIVE' || (int) $data['role_id'] !== (int) $user->role_id)) {
            return back()->withErrors(['role_id' => 'You cannot change your own role or deactivate your own account.'])->withInput();
        }
        if (! isset($data['password'])) {
            unset($data['password']);
        }
        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'User updated.');
    }

    private function validated(Request $request, ?User $user = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user?->id)],
            'phone' => ['nullable', 'string', 'max:30'],
            'role_id' => ['required', Rule::exists('roles', 'id')->whereIn('slug', ['admin', 'cashier', 'management'])],
            'status' => ['required', Rule::in(['ACTIVE', 'INACTIVE'])],
            'password' => [$user ? 'nullable' : 'required', 'confirmed', Password::defaults()],
        ]);
    }
}
