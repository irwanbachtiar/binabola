<!DOCTYPE html>
<html>
<head>
    <title>Debug Auth</title>
</head>
<body>
    <h1>Debug Information</h1>
    @auth
        <p><strong>Authenticated:</strong> Yes</p>
        <p><strong>User ID:</strong> {{ auth()->id() }}</p>
        <p><strong>User Name:</strong> {{ auth()->user()->name }}</p>
        <p><strong>User Email:</strong> {{ auth()->user()->email }}</p>
        <p><strong>User Role:</strong> {{ auth()->user()->role }}</p>
        <p><strong>Role (ucfirst):</strong> {{ ucfirst(auth()->user()->role) }}</p>
    @else
        <p><strong>Authenticated:</strong> No</p>
    @endauth
    
    <hr>
    <p><a href="{{ route('dashboard') }}">Go to Dashboard</a></p>
    <p><a href="{{ route('siswa.index') }}">Go to Data Siswa</a></p>
</body>
</html>
