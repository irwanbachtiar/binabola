<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PaketIuran;
use Illuminate\Http\Request;

class PaketIuranController extends Controller
{
    // List semua paket
    public function index()
    {
        $pakets = PaketIuran::orderBy('kelompok_umur')->orderBy('nama_paket')->get();
        return view('paket-iuran.index', compact('pakets'));
    }
    
    // Form tambah paket
    public function create()
    {
        return view('paket-iuran.create');
    }
    
    // Simpan paket baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_paket' => 'required|string|max:255',
            'kelompok_umur' => 'required|in:U-7,U-12',
            'nominal' => 'required|numeric|min:0',
            'durasi_bulan' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
        ]);
        
        $validated['aktif'] = $request->has('aktif') ? 1 : 0;
        
        PaketIuran::create($validated);
        
        return redirect()->route('paket-iuran.index')
            ->with('success', 'Paket iuran berhasil ditambahkan!');
    }
    
    // Form edit paket
    public function edit($id)
    {
        $paket = PaketIuran::findOrFail($id);
        return view('paket-iuran.edit', compact('paket'));
    }
    
    // Update paket
    public function update(Request $request, $id)
    {
        $paket = PaketIuran::findOrFail($id);
        
        $validated = $request->validate([
            'nama_paket' => 'required|string|max:255',
            'kelompok_umur' => 'required|in:U-7,U-12',
            'nominal' => 'required|numeric|min:0',
            'durasi_bulan' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
        ]);
        
        $validated['aktif'] = $request->has('aktif') ? 1 : 0;
        
        $paket->update($validated);
        
        return redirect()->route('paket-iuran.index')
            ->with('success', 'Paket iuran berhasil diupdate!');
    }
    
    // Hapus paket
    public function destroy($id)
    {
        $paket = PaketIuran::findOrFail($id);
        
        // Cek apakah paket sudah digunakan
        if ($paket->pembayarans()->count() > 0 || $paket->tagihans()->count() > 0) {
            return redirect()->route('paket-iuran.index')
                ->with('error', 'Paket tidak dapat dihapus karena sudah digunakan dalam pembayaran/tagihan!');
        }
        
        $paket->delete();
        
        return redirect()->route('paket-iuran.index')
            ->with('success', 'Paket iuran berhasil dihapus!');
    }
}

