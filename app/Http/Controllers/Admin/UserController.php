<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Display a listing of all users (Staff and Clients).
     */
    public function index()
    {
        // We eager load roles to avoid N+1 query issues
        $users = User::with(['roles', 'permissions'])->latest()->paginate(10);
        
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new staff member or guide.
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created user and assign their Safari role.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:User'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'exists:roles,name'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->role);

        return redirect()->route('admin.users.index')
            ->with('success', "User created and assigned as {$request->role} successfully.");
    }

    /**
     * Show user details and their assigned Safaris.
     */
    public function show(User $user)
    {
        // If the user is a guide, you might want to load their bookings here
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing a user's role or details.
     */
public function edit(User $user)
{
    // Fetch all roles and permissions from the database
    $roles = Role::all();
    $permissions = \Spatie\Permission\Models\Permission::all(); 

    // Get the first role name (if you only allow one role per user)
    $userRole = $user->roles->pluck('name')->first();
    
    // Pass everything to the view
    return view('admin.users.edit', compact('user', 'roles', 'permissions', 'userRole'));
}

    /**
     * Update the user and sync their roles.
     */
   public function update(Request $request, User $user)
{
    // 1. Validation
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
        'roles' => ['nullable', 'array'], // Changed to 'roles' to match the multiple-select
        'roles.*' => ['exists:roles,name'],
        'password' => ['nullable', 'string', 'min:8', 'confirmed'], // Handle optional password
        'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'], // Handle photo
    ]);

    // 2. Prepare Data
    $data = [
        'name'  => $request->name,
        'email' => $request->email,
        'status' => $request->has('status') ? 1 : 0, // Matches the switch in the blade
    ];

    // 3. Only update password if user typed one in
    if ($request->filled('password')) {
        $data['password'] = Hash::make($request->password);
    }

    // 4. Handle Profile Photo Upload
    if ($request->hasFile('photo')) {
        // Optional: Delete old photo from storage if it exists
        if ($user->photo && file_exists(public_path($user->photo))) {
            unlink(public_path($user->photo));
        }
        
        $path = $request->file('photo')->store('users/avatars', 'public');
        $data['photo'] = 'storage/' . $path;
    }

    // 5. Update the User
    $user->update($data);

    // 6. Sync Roles & Permissions
    // We only let Super Admins change roles to prevent "privilege escalation"
    if (auth()->user()->hasRole('super-admin')) {
        $user->syncRoles($request->roles ?? []);
        $user->syncPermissions($request->permissions ?? []);
    }

    return redirect()->route('admin.users.index')
        ->with('success', "User '{$user->name}' updated successfully.");
}

    /**
     * Remove a user (e.g., a guide who left the company).
     */
    public function destroy(User $user)
    {
        // Prevent accidental self-deletion
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete yourself.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }
}