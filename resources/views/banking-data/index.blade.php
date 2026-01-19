@extends('layouts.app')

@section('title', 'Data Perbankan - Sekolah Sepak Bola')

@section('page-title', 'Data Perbankan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-bank"></i> Daftar Data Perbankan</h5>
                    <a href="{{ route('banking-data.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Tambah Data
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>OSL KCA</th>
                                    <th>OSL Mikro</th>
                                    <th>OSL EMAS</th>
                                    <th>GTE</th>
                                    <th>Nasabah Baru</th>
                                    <th>Input Oleh</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                    <tr>
                                        <td>{{ $item->tanggal->format('d/m/Y') }}</td>
                                        <td>{{ number_format($item->osl_kca, 0, ',', '.') }}</td>
                                        <td>{{ number_format($item->osl_mikro, 0, ',', '.') }}</td>
                                        <td>{{ number_format($item->osl_emas, 0, ',', '.') }}</td>
                                        <td>{{ number_format($item->gte, 0, ',', '.') }}</td>
                                        <td>{{ $item->nasabah_baru }}</td>
                                        <td>{{ $item->user->name ?? '-' }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('banking-data.edit', $item->id) }}" 
                                                   class="btn btn-sm btn-warning">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('banking-data.destroy', $item->id) }}" 
                                                      method="POST" 
                                                      style="display: inline;"
                                                      onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Tidak ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $data->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
