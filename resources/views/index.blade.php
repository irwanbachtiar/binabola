<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Siswa</title>
</head>
<body>
    <h1>Daftar Siswa</h1>
    <a href="{{ route('siswa.create') }}">Tambah Siswa</a>
    <ul>
        @foreach($siswas as $siswa)
            <li>
                {{ $siswa->nama }} ({{ $siswa->posisi }})
                <a href="{{ route('siswa.edit', $siswa->id) }}">Edit</a>
                <form action="{{ route('siswa.destroy', $siswa->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Hapus</button>
                </form>
            </li>
        @endforeach
    </ul>
</body>
</html>