<?php

namespace App\Http\Controllers;

use App\Models\HariLibur;
use Illuminate\Http\Request;

class HariLiburController extends Controller
{
    public function index()
    {
        $hariLibur = HariLibur::orderBy('tanggal', 'desc')->get();
        return view('hari-libur.index', compact('hariLibur'));
    }

    public function create()
    {
        return view('hari-libur.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'keterangan' => 'required|string|max:255',
            'jenis' => 'required|in:Nasional,Keagamaan,Sekolah,Lainnya',
            'aktif' => 'boolean',
        ]);

        $validated['aktif'] = $request->has('aktif');

        HariLibur::create($validated);

        return redirect()->route('hari-libur.index')
            ->with('success', 'Hari libur berhasil ditambahkan');
    }

    public function edit(HariLibur $hariLibur)
    {
        return view('hari-libur.edit', compact('hariLibur'));
    }

    public function update(Request $request, HariLibur $hariLibur)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'keterangan' => 'required|string|max:255',
            'jenis' => 'required|in:Nasional,Keagamaan,Sekolah,Lainnya',
            'aktif' => 'boolean',
        ]);

        $validated['aktif'] = $request->has('aktif');

        $hariLibur->update($validated);

        return redirect()->route('hari-libur.index')
            ->with('success', 'Hari libur berhasil diupdate');
    }

    public function destroy(HariLibur $hariLibur)
    {
        $hariLibur->delete();

        return redirect()->route('hari-libur.index')
            ->with('success', 'Hari libur berhasil dihapus');
    }
}
