<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $query = User::with('department')
            ->orderByRaw("CASE WHEN role = 'admin' THEN 1 WHEN role = 'it' THEN 2 ELSE 3 END")
            ->orderBy('id');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('call_ext', 'like', "%{$search}%");
            });
        }

        $users = $query->get();
        $departments = \App\Models\Department::where('is_active', true)->orderBy('name')->get();

        return view('users', compact('users', 'departments'));
    }

    /**
     * Toggle active/inactive status of a user.
     */
    public function toggleStatus(User $user)
    {
        // Prevent self deactivation
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'You cannot change your own active status.');
        }

        $user->is_active = !$user->is_active;
        $user->save();

        $status = $user->is_active ? 'activated' : 'deactivated';
        return redirect()->back()->with('success', "User '{$user->name}' has been successfully {$status}.");
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Prevent self deletion
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->back()->with('success', "User '{$userName}' has been successfully deleted.");
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['nullable', 'string', 'min:6'],
            'role' => ['required', 'string', 'in:admin,it,user'],
            'call_ext' => ['nullable', 'string', 'max:50'],
            'department_id' => ['nullable', 'exists:departments,id'],
        ]);

        $isUser = $validated['role'] === 'user';

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => \Illuminate\Support\Facades\Hash::make($validated['password'] ?? 'password'),
            'role' => $validated['role'],
            'is_active' => true,
            'call_ext' => $isUser ? ($validated['call_ext'] ?? null) : null,
            'department_id' => $isUser ? ($validated['department_id'] ?? null) : null,
        ]);

        return redirect()->back()->with('success', "User '{$user->name}' has been successfully created.");
    }
}
