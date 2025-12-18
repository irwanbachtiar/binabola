<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    // Menampilkan daftar siswa
    public function index()
    {
        $siswas = Siswa::all();
        return view('siswa.index', compact('siswas'));
    }

    // Menampilkan form untuk menambahkan siswa baru
    public function create()
    {
        return view('siswa.create');
    }

    // Menyimpan siswa baru ke database
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'tanggal_lahir' => 'required|date',
            'minat_posisi' => 'required|array|min:1',
            'minat_posisi.*' => 'required|string',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'alamat' => 'nullable|string',
            'tinggi_badan' => 'nullable|integer|min:50|max:250',
            'berat_badan' => 'nullable|integer|min:10|max:200',
            'status' => 'required|in:Aktif,Non-Aktif',
        ]);

        // Handle upload foto
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $fotoName = time() . '_' . $foto->getClientOriginalName();
            $foto->move(public_path('uploads/siswa'), $fotoName);
            $validated['foto'] = 'uploads/siswa/' . $fotoName;
        }

        Siswa::create($validated);
        return redirect()->route('siswa.index')->with('success', 'Siswa berhasil ditambahkan!');
    }

    // Menampilkan form edit untuk siswa tertentu
    public function edit(Siswa $siswa)
    {
        return view('siswa.edit', compact('siswa'));
    }

    // Memperbarui data siswa di database
    public function update(Request $request, Siswa $siswa)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'tanggal_lahir' => 'required|date',
            'minat_posisi' => 'required|array|min:1',
            'minat_posisi.*' => 'required|string',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'alamat' => 'nullable|string',
            'tinggi_badan' => 'nullable|integer|min:50|max:250',
            'berat_badan' => 'nullable|integer|min:10|max:200',
            'status' => 'required|in:Aktif,Non-Aktif',
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
        return redirect()->route('siswa.index')->with('success', 'Siswa berhasil diperbarui!');
    }

    // Menghapus siswa dari database
    public function destroy(Siswa $siswa)
    {
        $siswa->delete();
        return redirect()->route('siswa.index')->with('success', 'Siswa berhasil dihapus!');
    }
}