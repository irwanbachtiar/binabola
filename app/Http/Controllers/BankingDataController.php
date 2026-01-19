<?php

namespace App\Http\Controllers;

use App\Models\BankingData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BankingDataController extends Controller
{
    public function index()
    {
        $data = BankingData::with('user')
            ->orderBy('tanggal', 'desc')
            ->paginate(20);
        
        return view('banking-data.index', compact('data'));
    }

    public function create()
    {
        return view('banking-data.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'osl_kca' => 'nullable|numeric|min:0',
            'osl_mikro' => 'nullable|numeric|min:0',
            'osl_emas' => 'nullable|numeric|min:0',
            'gte' => 'nullable|numeric|min:0',
            'nasabah_baru' => 'nullable|integer|min:0',
            'nasabah_baru_agen' => 'nullable|integer|min:0',
            'nasabah_existing' => 'nullable|integer|min:0',
            'nasabah_tabungan_emas' => 'nullable|integer|min:0',
            'deposito' => 'nullable|numeric|min:0',
            'tabungan_emas' => 'nullable|numeric|min:0',
            'g24' => 'nullable|numeric|min:0',
            'nasabah_tring' => 'nullable|integer|min:0',
            'osl_tring' => 'nullable|numeric|min:0',
            'frekuensi_trx_tring' => 'nullable|integer|min:0',
            'disbursement_bri' => 'nullable|numeric|min:0',
            'osl_sinergi_holding' => 'nullable|numeric|min:0',
            'te_sinergi_holding' => 'nullable|numeric|min:0',
        ]);

        $validated['user_id'] = Auth::id();

        BankingData::create($validated);

        return redirect()->route('banking-data.index')
            ->with('success', 'Data berhasil disimpan');
    }

    public function edit($id)
    {
        $data = BankingData::findOrFail($id);
        return view('banking-data.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'osl_kca' => 'nullable|numeric|min:0',
            'osl_mikro' => 'nullable|numeric|min:0',
            'osl_emas' => 'nullable|numeric|min:0',
            'gte' => 'nullable|numeric|min:0',
            'nasabah_baru' => 'nullable|integer|min:0',
            'nasabah_baru_agen' => 'nullable|integer|min:0',
            'nasabah_existing' => 'nullable|integer|min:0',
            'nasabah_tabungan_emas' => 'nullable|integer|min:0',
            'deposito' => 'nullable|numeric|min:0',
            'tabungan_emas' => 'nullable|numeric|min:0',
            'g24' => 'nullable|numeric|min:0',
            'nasabah_tring' => 'nullable|integer|min:0',
            'osl_tring' => 'nullable|numeric|min:0',
            'frekuensi_trx_tring' => 'nullable|integer|min:0',
            'disbursement_bri' => 'nullable|numeric|min:0',
            'osl_sinergi_holding' => 'nullable|numeric|min:0',
            'te_sinergi_holding' => 'nullable|numeric|min:0',
        ]);

        $data = BankingData::findOrFail($id);
        $data->update($validated);

        return redirect()->route('banking-data.index')
            ->with('success', 'Data berhasil diperbarui');
    }

    public function destroy($id)
    {
        $data = BankingData::findOrFail($id);
        $data->delete();

        return redirect()->route('banking-data.index')
            ->with('success', 'Data berhasil dihapus');
    }
}
