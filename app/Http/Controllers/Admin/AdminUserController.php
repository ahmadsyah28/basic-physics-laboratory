<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class AdminUserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index()
    {
        // Super admin can see all users, regular admin can only see non-super-admin users
        if (Auth::user()->role === 'super_admin') {
            $users = User::all();
        } else {
            $users = User::where('role', '!=', 'super_admin')->get();
        }

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        // Only super admin can create admin users
        if (Auth::user()->role !== 'super_admin') {
            return redirect()->route('admin.users.index')
                ->with('error', 'Anda tidak memiliki izin untuk membuat pengguna admin');
        }

        return view('admin.users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        // Only super admin can create admin users
        if (Auth::user()->role !== 'super_admin') {
            return redirect()->route('admin.users.index')
                ->with('error', 'Anda tidak memiliki izin untuk membuat pengguna admin');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => [
                'required',
                Rule::in(['admin', 'super_admin']),
            ],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna admin berhasil dibuat');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        // Regular admin cannot edit super admin
        if (Auth::user()->role !== 'super_admin' && $user->role === 'super_admin') {
            return redirect()->route('admin.users.index')
                ->with('error', 'Anda tidak memiliki izin untuk mengedit super admin');
        }

        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        // Regular admin cannot update super admin
        if (Auth::user()->role !== 'super_admin' && $user->role === 'super_admin') {
            return redirect()->route('admin.users.index')
                ->with('error', 'Anda tidak memiliki izin untuk memperbarui super admin');
        }

        $rules = [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
        ];

        // Only super admin can change roles
        if (Auth::user()->role === 'super_admin') {
            $rules['role'] = [
                'required',
                Rule::in(['admin', 'super_admin']),
            ];
        }

        // Password is optional on update
        if ($request->filled('password')) {
            $rules['password'] = 'string|min:8|confirmed';
        }

        $request->validate($rules);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        // Only super admin can change roles
        if (Auth::user()->role === 'super_admin' && $request->has('role')) {
            $data['role'] = $request->role;
        }

        // Update password if provided
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna admin berhasil diperbarui');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Cannot delete yourself
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri');
        }

        // Regular admin cannot delete super admin
        if (Auth::user()->role !== 'super_admin' && $user->role === 'super_admin') {
            return redirect()->route('admin.users.index')
                ->with('error', 'Anda tidak memiliki izin untuk menghapus super admin');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna admin berhasil dihapus');
    }
}
