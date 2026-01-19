@extends('layouts.app')

@section('title', isset($data) ? 'Edit Data Perbankan' : 'Tambah Data Perbankan')

@section('page-title', isset($data) ? 'Edit Data Perbankan' : 'Tambah Data Perbankan')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="bi bi-{{ isset($data) ? 'pencil' : 'plus-circle' }}"></i> 
                        Form {{ isset($data) ? 'Edit' : 'Tambah' }} Data Perbankan
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ isset($data) ? route('banking-data.update', $data->id) : route('banking-data.store') }}" 
                          method="POST">
                        @csrf
                        @if(isset($data))
                            @method('PUT')
                        @endif
                        
                        <!-- Tanggal -->
                        <div class="mb-3">
                            <label for="tanggal" class="form-label">
                                Tanggal <span class="text-danger">*</span>
                            </label>
                            <input type="date" 
                                   class="form-control @error('tanggal') is-invalid @enderror" 
                                   id="tanggal" 
                                   name="tanggal" 
                                   value="{{ old('tanggal', isset($data) ? $data->tanggal->format('Y-m-d') : date('Y-m-d')) }}" 
                                   required>
                            @error('tanggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <!-- OSL KCA -->
                            <div class="col-md-6 mb-3">
                                <label for="osl_kca" class="form-label">1. OSL KCA</label>
                                <input type="number" 
                                       step="0.01"
                                       class="form-control @error('osl_kca') is-invalid @enderror" 
                                       id="osl_kca" 
                                       name="osl_kca" 
                                       value="{{ old('osl_kca', isset($data) ? $data->osl_kca : 0) }}" 
                                       placeholder="0.00">
                                @error('osl_kca')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- OSL Mikro -->
                            <div class="col-md-6 mb-3">
                                <label for="osl_mikro" class="form-label">2. OSL Mikro</label>
                                <input type="number" 
                                       step="0.01"
                                       class="form-control @error('osl_mikro') is-invalid @enderror" 
                                       id="osl_mikro" 
                                       name="osl_mikro" 
                                       value="{{ old('osl_mikro', isset($data) ? $data->osl_mikro : 0) }}" 
                                       placeholder="0.00">
                                @error('osl_mikro')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- OSL EMAS -->
                            <div class="col-md-6 mb-3">
                                <label for="osl_emas" class="form-label">3. OSL EMAS</label>
                                <input type="number" 
                                       step="0.01"
                                       class="form-control @error('osl_emas') is-invalid @enderror" 
                                       id="osl_emas" 
                                       name="osl_emas" 
                                       value="{{ old('osl_emas', isset($data) ? $data->osl_emas : 0) }}" 
                                       placeholder="0.00">
                                @error('osl_emas')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- GTE -->
                            <div class="col-md-6 mb-3">
                                <label for="gte" class="form-label">4. GTE</label>
                                <input type="number" 
                                       step="0.01"
                                       class="form-control @error('gte') is-invalid @enderror" 
                                       id="gte" 
                                       name="gte" 
                                       value="{{ old('gte', isset($data) ? $data->gte : 0) }}" 
                                       placeholder="0.00">
                                @error('gte')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Nasabah Baru -->
                            <div class="col-md-6 mb-3">
                                <label for="nasabah_baru" class="form-label">5. Nasabah Baru (jumlah)</label>
                                <input type="number" 
                                       class="form-control @error('nasabah_baru') is-invalid @enderror" 
                                       id="nasabah_baru" 
                                       name="nasabah_baru" 
                                       value="{{ old('nasabah_baru', isset($data) ? $data->nasabah_baru : 0) }}" 
                                       placeholder="0">
                                @error('nasabah_baru')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Nasabah Baru Agen -->
                            <div class="col-md-6 mb-3">
                                <label for="nasabah_baru_agen" class="form-label">6. Nasabah Baru Agen</label>
                                <input type="number" 
                                       class="form-control @error('nasabah_baru_agen') is-invalid @enderror" 
                                       id="nasabah_baru_agen" 
                                       name="nasabah_baru_agen" 
                                       value="{{ old('nasabah_baru_agen', isset($data) ? $data->nasabah_baru_agen : 0) }}" 
                                       placeholder="0">
                                @error('nasabah_baru_agen')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Nasabah Existing -->
                            <div class="col-md-6 mb-3">
                                <label for="nasabah_existing" class="form-label">7. Nasabah Existing (jumlah)</label>
                                <input type="number" 
                                       class="form-control @error('nasabah_existing') is-invalid @enderror" 
                                       id="nasabah_existing" 
                                       name="nasabah_existing" 
                                       value="{{ old('nasabah_existing', isset($data) ? $data->nasabah_existing : 0) }}" 
                                       placeholder="0">
                                @error('nasabah_existing')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Nasabah Tabungan Emas -->
                            <div class="col-md-6 mb-3">
                                <label for="nasabah_tabungan_emas" class="form-label">8. Nasabah Tabungan Emas</label>
                                <input type="number" 
                                       class="form-control @error('nasabah_tabungan_emas') is-invalid @enderror" 
                                       id="nasabah_tabungan_emas" 
                                       name="nasabah_tabungan_emas" 
                                       value="{{ old('nasabah_tabungan_emas', isset($data) ? $data->nasabah_tabungan_emas : 0) }}" 
                                       placeholder="0">
                                @error('nasabah_tabungan_emas')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Deposito -->
                            <div class="col-md-6 mb-3">
                                <label for="deposito" class="form-label">9. Deposito (gramasi)</label>
                                <input type="number" 
                                       step="0.001"
                                       class="form-control @error('deposito') is-invalid @enderror" 
                                       id="deposito" 
                                       name="deposito" 
                                       value="{{ old('deposito', isset($data) ? $data->deposito : 0) }}" 
                                       placeholder="0.000">
                                @error('deposito')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tabungan Emas -->
                            <div class="col-md-6 mb-3">
                                <label for="tabungan_emas" class="form-label">10. Tabungan Emas (gramasi)</label>
                                <input type="number" 
                                       step="0.001"
                                       class="form-control @error('tabungan_emas') is-invalid @enderror" 
                                       id="tabungan_emas" 
                                       name="tabungan_emas" 
                                       value="{{ old('tabungan_emas', isset($data) ? $data->tabungan_emas : 0) }}" 
                                       placeholder="0.000">
                                @error('tabungan_emas')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- G24 -->
                            <div class="col-md-6 mb-3">
                                <label for="g24" class="form-label">11. G24 (gram)</label>
                                <input type="number" 
                                       step="0.001"
                                       class="form-control @error('g24') is-invalid @enderror" 
                                       id="g24" 
                                       name="g24" 
                                       value="{{ old('g24', isset($data) ? $data->g24 : 0) }}" 
                                       placeholder="0.000">
                                @error('g24')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Nasabah Tring -->
                            <div class="col-md-6 mb-3">
                                <label for="nasabah_tring" class="form-label">12. Nasabah Tring</label>
                                <input type="number" 
                                       class="form-control @error('nasabah_tring') is-invalid @enderror" 
                                       id="nasabah_tring" 
                                       name="nasabah_tring" 
                                       value="{{ old('nasabah_tring', isset($data) ? $data->nasabah_tring : 0) }}" 
                                       placeholder="0">
                                @error('nasabah_tring')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- OSL Tring -->
                            <div class="col-md-6 mb-3">
                                <label for="osl_tring" class="form-label">13. OSL Tring</label>
                                <input type="number" 
                                       step="0.01"
                                       class="form-control @error('osl_tring') is-invalid @enderror" 
                                       id="osl_tring" 
                                       name="osl_tring" 
                                       value="{{ old('osl_tring', isset($data) ? $data->osl_tring : 0) }}" 
                                       placeholder="0.00">
                                @error('osl_tring')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Frekuensi Trx Tring -->
                            <div class="col-md-6 mb-3">
                                <label for="frekuensi_trx_tring" class="form-label">14. Frekuensi Trx Tring</label>
                                <input type="number" 
                                       class="form-control @error('frekuensi_trx_tring') is-invalid @enderror" 
                                       id="frekuensi_trx_tring" 
                                       name="frekuensi_trx_tring" 
                                       value="{{ old('frekuensi_trx_tring', isset($data) ? $data->frekuensi_trx_tring : 0) }}" 
                                       placeholder="0">
                                @error('frekuensi_trx_tring')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Disbursement BRI -->
                            <div class="col-md-6 mb-3">
                                <label for="disbursement_bri" class="form-label">15. Disbursement BRI</label>
                                <input type="number" 
                                       step="0.01"
                                       class="form-control @error('disbursement_bri') is-invalid @enderror" 
                                       id="disbursement_bri" 
                                       name="disbursement_bri" 
                                       value="{{ old('disbursement_bri', isset($data) ? $data->disbursement_bri : 0) }}" 
                                       placeholder="0.00">
                                @error('disbursement_bri')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- OSL Sinergi Holding -->
                            <div class="col-md-6 mb-3">
                                <label for="osl_sinergi_holding" class="form-label">16. OSL Sinergi Holding</label>
                                <input type="number" 
                                       step="0.01"
                                       class="form-control @error('osl_sinergi_holding') is-invalid @enderror" 
                                       id="osl_sinergi_holding" 
                                       name="osl_sinergi_holding" 
                                       value="{{ old('osl_sinergi_holding', isset($data) ? $data->osl_sinergi_holding : 0) }}" 
                                       placeholder="0.00">
                                @error('osl_sinergi_holding')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- TE Sinergi Holding -->
                            <div class="col-md-6 mb-3">
                                <label for="te_sinergi_holding" class="form-label">17. TE Sinergi Holding (gramasi)</label>
                                <input type="number" 
                                       step="0.001"
                                       class="form-control @error('te_sinergi_holding') is-invalid @enderror" 
                                       id="te_sinergi_holding" 
                                       name="te_sinergi_holding" 
                                       value="{{ old('te_sinergi_holding', isset($data) ? $data->te_sinergi_holding : 0) }}" 
                                       placeholder="0.000">
                                @error('te_sinergi_holding')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('banking-data.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> {{ isset($data) ? 'Update' : 'Simpan' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
