<?php

namespace App\Http\Controllers;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rules;
use Illuminate\Auth\Events\Registered;
use Illuminate\View\View;




class UserController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        $usersWithRoles = User::with('roles')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($user) {
                $user->role_names = $user->roles->pluck('name')->implode(', ');
                return $user;
            });
        return view('users.index', compact('usersWithRoles'));
    }
    
    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'roles' => ['required', 'array'],
            'roles.*' => ['exists:roles,id'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        
        $roles = Role::whereIn('id', $request->roles)->get();
        $user->assignRole($roles);
        event(new Registered($user));

        $type = $user ? 'success' : 'error';
        $message = $user ? 'User successfully added.' : 'Failed to add user. Please try again.';
        return redirect()->route('users.index')->with($type, $message);
    }

    public function edit($id): View
    {
        $roles = Role::all();
        $user = User::findOrFail($id);
        return view('users.edit', [
            'user' => $user,
            'roles' => $roles
        ]);
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $user = User::findOrFail($id);
        
        $data = $request->except(['password', 'roles']);
        
        if ($request->has('email') && $request->email !== $user->email) {
            $existingUser = User::where('email', $request->email)->first();
            if ($existingUser) {
                return redirect()->back()->withErrors(['email' => 'Email is already in use by another user.'])->withInput();
            }
        }
        
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        
        $user->update($data);
        
        if ($request->has('roles')) {
            $roles = Role::whereIn('id', $request->roles)->get();
            $user->syncRoles($roles);
        }
        
        return redirect()->route('users.index')->with('success', 'User successfully updated.');
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return response()->json(['success' => 'User successfully deleted.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while deleting the user.'], 500);
        }
    }
}
