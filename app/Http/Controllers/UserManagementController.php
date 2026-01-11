<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Siswa;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    /**
     * Show list of parent users
     */
    public function index()
    {
        $users = User::where('role', 'orangtua')->with('siswas')->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show create parent form
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store new parent user
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'unit_kerja' => 'nullable|string|max:255',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'orangtua',
            'unit_kerja' => $validated['unit_kerja'] ?? null,
        ]);

        return redirect()->route('admin.users.edit', $user->id)
            ->with('success', 'User orangtua berhasil dibuat. Silakan assign siswa.');
    }

    /**
     * Show edit form with siswa assignment
     */
    public function edit($id)
    {
        $user = User::with('siswas')->findOrFail($id);
        
        // Only allow editing orangtua role
        if ($user->role !== 'orangtua') {
            return redirect()->route('admin.users.index')
                ->with('error', 'Hanya bisa edit user dengan role orangtua.');
        }
        
        $allSiswas = Siswa::all();
        $assignedSiswaIds = $user->siswas->pluck('id')->toArray();
        
        return view('admin.users.edit', compact('user', 'allSiswas', 'assignedSiswaIds'));
    }

    /**
     * Show user detail (not used, required by resource)
     */
    public function show($id)
    {
        return redirect()->route('admin.users.edit', $id);
    }

    /**
     * Update user and siswa assignments
     */
    public function update(Request $request, $id)
    {
        \Log::info('UserManagementController@update called', [
            'id' => $id,
            'request_data' => $request->except(['password', 'password_confirmation'])
        ]);
        
        try {
            $user = User::findOrFail($id);
            
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,'.$id,
                'password' => 'nullable|min:6|confirmed',
                'unit_kerja' => 'nullable|string|max:255',
                'siswa_ids' => 'nullable|array',
                'siswa_ids.*' => 'exists:siswas,id',
            ]);

            $user->name = $validated['name'];
            $user->email = $validated['email'];
            $user->unit_kerja = $validated['unit_kerja'] ?? null;
            
            if (!empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }
            
            $user->save();

            // Sync siswa assignments
            $user->siswas()->sync($request->siswa_ids ?? []);
            
            \Log::info('User updated successfully', ['user_id' => $user->id]);

            return redirect()->route('admin.users.index')
                ->with('success', 'User orangtua berhasil diupdate.');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed', ['errors' => $e->errors()]);
            return redirect()->back()
                ->withInput()
                ->withErrors($e->errors())
                ->with('error', 'Validasi gagal. Periksa kembali inputan Anda.');
                
        } catch (\Exception $e) {
            \Log::error('Update failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal update user: ' . $e->getMessage());
        }
    }

    /**
     * Delete user
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->siswas()->detach(); // Remove all siswa assignments
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User orangtua berhasil dihapus.');
    }
}
