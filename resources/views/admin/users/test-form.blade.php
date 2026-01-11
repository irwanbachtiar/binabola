<!DOCTYPE html>
<html>
<head>
    <title>Test Edit User Form</title>
</head>
<body>
    <h2>Test Form Edit User</h2>
    
    <h3>Route Check:</h3>
    <ul>
        <li>Route: {{ route('admin.users.edit', 1) }}</li>
        <li>Update Route: {{ route('admin.users.update', 1) }}</li>
    </ul>

    <h3>Test Form:</h3>
    <form action="{{ route('admin.users.update', 1) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div>
            <label>Nama: </label>
            <input type="text" name="name" value="Test User" required>
        </div>
        
        <div>
            <label>Email: </label>
            <input type="email" name="email" value="test@example.com" required>
        </div>
        
        <div>
            <label>Password (optional): </label>
            <input type="password" name="password">
        </div>
        
        <div>
            <label>Confirm Password: </label>
            <input type="password" name="password_confirmation">
        </div>
        
        <div>
            <label>Siswa IDs (checkbox):</label>
            <input type="checkbox" name="siswa_ids[]" value="1"> Siswa 1
            <input type="checkbox" name="siswa_ids[]" value="2"> Siswa 2
        </div>
        
        <button type="submit">Update</button>
    </form>
    
    <hr>
    
    <h3>Debug Info:</h3>
    <ul>
        <li>Current User: {{ auth()->check() ? auth()->user()->name : 'Not logged in' }}</li>
        <li>Role: {{ auth()->check() ? auth()->user()->role : 'N/A' }}</li>
        <li>Can Access: {{ auth()->check() && auth()->user()->role === 'admin' ? 'Yes' : 'No' }}</li>
    </ul>
</body>
</html>
