<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    // Menampilkan daftar siswa
    public function index(Request $request)
    {
        $kelompok = $request->get('kelompok', 'semua');
        
        $query = Siswa::query();
        
        if ($kelompok === 'u7') {
            // Filter siswa umur 3-7 tahun
            $query->whereRaw('TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) BETWEEN 3 AND 7');
        } elseif ($kelompok === 'u12') {
            // Filter siswa umur 8-12 tahun
            $query->whereRaw('TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) BETWEEN 8 AND 12');
        }
        
        $siswas = $query->get();
        
        return view('siswa.index', compact('siswas', 'kelompok'));
    }

    // Menampilkan form untuk menambahkan siswa baru
    public function create()
    {
        $orangtuaUsers = User::where('role', 'orangtua')->get();
        $pakets = \App\Models\PaketIuran::aktif()->orderBy('kelompok_umur')->orderBy('nama_paket')->get();
        return view('siswa.create', compact('orangtuaUsers', 'pakets'));
    }

    // Menyimpan siswa baru ke database
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'tanggal_lahir' => 'required|date',
            'tanggal_masuk' => 'required|date',
            'minat_posisi' => 'required|array|min:1',
            'minat_posisi.*' => 'required|string',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'alamat' => 'nullable|string',
            'tinggi_badan' => 'nullable|integer|min:50|max:250',
            'berat_badan' => 'nullable|integer|min:10|max:150',
            'status' => 'required|in:Aktif,Tidak Aktif',
            'paket_iuran_id' => 'nullable|exists:paket_iurans,id',
            'berat_badan' => 'nullable|integer|min:10|max:200',
            'status' => 'required|in:Aktif,Non-Aktif',
            'orangtua_ids' => 'nullable|array',
            'orangtua_ids.*' => 'exists:users,id',
        ]);

        // Handle upload foto
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $fotoName = time() . '_' . $foto->getClientOriginalName();
            $foto->move(public_path('uploads/siswa'), $fotoName);
            $validated['foto'] = 'uploads/siswa/' . $fotoName;
        }

        $siswa = Siswa::create($validated);
        
        // Sync orangtua relationships
        if ($request->has('orangtua_ids')) {
            $siswa->users()->sync($request->orangtua_ids);
        }
        
        return redirect()->route('siswa.index')->with('success', 'Siswa berhasil ditambahkan!');
    }

    // Menampilkan form edit untuk siswa tertentu
    public function edit(Siswa $siswa)
    {
        $orangtuaUsers = User::where('role', 'orangtua')->get();
        $assignedOrangtuaIds = $siswa->users->pluck('id')->toArray();
        $pakets = \App\Models\PaketIuran::aktif()->orderBy('kelompok_umur')->orderBy('nama_paket')->get();
        return view('siswa.edit', compact('siswa', 'orangtuaUsers', 'assignedOrangtuaIds', 'pakets'));
    }

    // Memperbarui data siswa di database
    public function update(Request $request, Siswa $siswa)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'tanggal_lahir' => 'required|date',
            'tanggal_masuk' => 'required|date',
            'minat_posisi' => 'required|array|min:1',
            'minat_posisi.*' => 'required|string',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'alamat' => 'nullable|string',
            'tinggi_badan' => 'nullable|integer|min:50|max:250',
            'berat_badan' => 'nullable|integer|min:10|max:200',
            'status' => 'required|in:Aktif,Non-Aktif',
            'paket_iuran_id' => 'nullable|exists:paket_iurans,id',
            'orangtua_ids' => 'nullable|array',
            'orangtua_ids.*' => 'exists:users,id',
        ]);

        // Handle upload foto baru
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($siswa->foto && file_exists(public_path($siswa->foto))) {
                unlink(public_path($siswa->foto));
            }
            
            $foto = $request->file('foto');
            $fotoName = time() . '_' . $foto->getClientOriginalName();
            $foto->move(public_path('uploads/siswa'), $fotoName);
            $validated['foto'] = 'uploads/siswa/' . $fotoName;
        }

        $siswa->update($validated);
        
        // Sync orangtua relationships
        $siswa->users()->sync($request->orangtua_ids ?? []);
        
        return redirect()->route('siswa.index')->with('success', 'Siswa berhasil diperbarui!');
    }

    // Menghapus siswa dari database
    public function destroy(Siswa $siswa)
    {
        // Hapus foto jika ada
        if ($siswa->foto && file_exists(public_path($siswa->foto))) {
            unlink(public_path($siswa->foto));
        }
        
        // Hapus relasi dengan orangtua
        $siswa->users()->detach();
        
        $siswa->delete();
        return redirect()->route('siswa.index')->with('success', 'Siswa berhasil dihapus!');
    }
}