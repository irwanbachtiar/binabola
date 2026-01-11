@extends('layouts.app')

@section('title', 'Manajemen User Orangtua')

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Manajemen User Orangtua</h2>
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Tambah User Orangtua
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Unit Kerja</th>
                                    <th>Siswa Terhubung</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @if($user->unit_kerja)
                                                <span class="text-muted">{{ $user->unit_kerja }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($user->siswas->count() > 0)
                                                <span class="badge bg-success">{{ $user->siswas->count() }} siswa</span>
                                                <br>
                                                <small class="text-muted">
                                                    {{ $user->siswas->pluck('nama')->join(', ') }}
                                                </small>
                                            @else
                                                <span class="badge bg-secondary">Belum ada</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-warning">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus user ini?')">
                                                    <i class="bi bi-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">Belum ada user orangtua</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
