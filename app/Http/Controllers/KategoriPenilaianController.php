<?php

namespace App\Http\Controllers;

use App\Models\KategoriPenilaian;
use Illuminate\Http\Request;

class KategoriPenilaianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategoris = KategoriPenilaian::with('parent', 'children')
            ->orderBy('kelompok_umur')
            ->orderBy('urutan')
            ->get();
        
        return view('kategori-penilaian.index', compact('kategoris'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $parents = KategoriPenilaian::whereNull('parent_id')
            ->orderBy('kelompok_umur')
            ->orderBy('urutan')
            ->get();
        
        return view('kategori-penilaian.create', compact('parents'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        \Log::info('KategoriPenilaian store method called', [
            'request_data' => $request->all(),
            'user_id' => auth()->id(),
            'user_role' => auth()->user()->role ?? 'unknown'
        ]);
        
        try {
            $validated = $request->validate([
                'nama' => 'required|string|max:255',
                'deskripsi' => 'nullable|string',
                'parent_id' => 'nullable|exists:kategori_penilaians,id',
                'kelompok_umur' => 'required|in:U-7,U-12',
                'urutan' => 'required|integer|min:1',
            ]);
            
            // Handle checkboxes separately
            $validated['aktif'] = $request->has('aktif') ? 1 : 0;
            $validated['minggu_terakhir'] = $request->has('minggu_terakhir') ? 1 : 0;

            \Log::info('Validation passed', ['validated' => $validated]);
            
            $kategori = KategoriPenilaian::create($validated);
            
            \Log::info('KategoriPenilaian created successfully', ['id' => $kategori->id]);

            return redirect()->route('kategori-penilaian.index')
                ->with('success', 'Kategori penilaian berhasil ditambahkan!');
        
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed', [
                'errors' => $e->errors(),
                'message' => $e->getMessage()
            ]);
            throw $e;
        } catch (\Exception $e) {
            \Log::error('Error creating kategori', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KategoriPenilaian $kategoriPenilaian)
    {
        $parents = KategoriPenilaian::whereNull('parent_id')
            ->where('id', '!=', $kategoriPenilaian->id)
            ->orderBy('kelompok_umur')
            ->orderBy('urutan')
            ->get();
        
        return view('kategori-penilaian.edit', compact('kategoriPenilaian', 'parents'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KategoriPenilaian $kategoriPenilaian)
    {
        try {
            $validated = $request->validate([
                'nama' => 'required|string|max:255',
                'deskripsi' => 'nullable|string',
                'parent_id' => 'nullable|exists:kategori_penilaians,id',
                'kelompok_umur' => 'required|in:U-7,U-12',
                'urutan' => 'required|integer|min:1',
            ]);
            
            // Handle checkboxes separately
            $validated['aktif'] = $request->has('aktif') ? 1 : 0;
            $validated['minggu_terakhir'] = $request->has('minggu_terakhir') ? 1 : 0;
            
            // Prevent self-reference
            if ($validated['parent_id'] == $kategoriPenilaian->id) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Kategori tidak bisa menjadi parent dari dirinya sendiri.');
            }
            
            $kategoriPenilaian->update($validated);

            return redirect()->route('kategori-penilaian.index')
                ->with('success', 'Kategori penilaian berhasil diperbarui!');
                
        } catch (\Exception $e) {
            \Log::error('Error updating kategori', [
                'message' => $e->getMessage(),
                'kategori_id' => $kategoriPenilaian->id
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KategoriPenilaian $kategoriPenilaian)
    {
        // Cek apakah kategori ini sudah digunakan di evaluasi
        $digunakan = $kategoriPenilaian->evaluasi()->count();
        
        if ($digunakan > 0) {
            return redirect()->back()
                ->with('error', "Kategori tidak dapat dihapus karena sudah digunakan dalam {$digunakan} evaluasi.");
        }

        $kategoriPenilaian->delete();

        return redirect()->route('kategori-penilaian.index')
            ->with('success', 'Kategori penilaian berhasil dihapus!');
    }
}
