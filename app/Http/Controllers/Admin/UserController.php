<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{

    public function index()
    {
        // Fetch all users with their roles preloaded to make it run fast
        $users = User::with('roles')->get();
        
        return view('admin.users.index', compact('users'));
    }

    // 1. Show the creation form
    public function create()
    {
        // Fetch all available roles (system admin, accountant, etc.) to show in a dropdown
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    // 2. Process the form submission
    public function store(Request $request)
    {
        // Validate form data
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'exists:roles,name'], // Ensures the selected role exists
        ]);

        // Create the user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Assign the selected role (Spatie method)
        $user->assignRole($request->role);

        // Redirect back with a success message
        return redirect()->route('admin.users.create')->with('status', 'User created successfully with the assigned role!');
    }
}
