<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    /**
     * Display a listing of all administrators.
     */
    public function index(): View
    {
        $admins = User::orderByDesc('is_main_admin')
            ->orderBy('name')
            ->paginate(15);

        $totalAdmins = User::count();
        $totalMainAdmins = User::where('is_main_admin', true)->count();

        return view('admin.users.index', compact('admins', 'totalAdmins', 'totalMainAdmins'));
    }

    /**
     * Show the form for creating a new admin.
     */
    public function create(): View
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created admin in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'is_main_admin' => ['nullable', 'boolean'],
        ]);

        $admin = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'is_main_admin' => $request->boolean('is_main_admin'),
            'email_verified_at' => now(),
        ]);

        $role = $admin->isMainAdmin() ? 'Main Admin' : 'Admin';

        return redirect()->route('admin.users.index')
            ->with('success', "Akun {$role} \"{$admin->name}\" ({$admin->email}) berhasil ditambahkan!");
    }

    /**
     * Show the form for editing an existing admin.
     */
    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified admin in storage.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'is_main_admin' => ['nullable', 'boolean'],
        ]);

        // Safeguard: Ensure at least one Main Admin remains
        $newIsMainAdmin = $request->boolean('is_main_admin');
        if ($user->isMainAdmin() && !$newIsMainAdmin) {
            $otherMainAdmins = User::where('is_main_admin', true)->where('id', '!=', $user->id)->count();
            if ($otherMainAdmins < 1) {
                return back()->withInput()->with('error', 'Tidak dapat mengubah status: Minimal harus ada 1 Main Admin dalam sistem.');
            }
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->is_main_admin = $newIsMainAdmin;
        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', "Data admin \"{$user->name}\" berhasil diperbarui!");
    }

    /**
     * Remove the specified admin from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        // Safeguard: cannot delete oneself
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Operasi dibatalkan: Anda tidak dapat menghapus akun Anda sendiri.');
        }

        // Safeguard: cannot delete the last main admin
        if ($user->isMainAdmin()) {
            $otherMainAdmins = User::where('is_main_admin', true)->where('id', '!=', $user->id)->count();
            if ($otherMainAdmins < 1) {
                return back()->with('error', 'Operasi dibatalkan: Tidak dapat menghapus Main Admin terakhir.');
            }
        }

        $name = $user->name;
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "Akun admin \"{$name}\" berhasil dihapus.");
    }
}

