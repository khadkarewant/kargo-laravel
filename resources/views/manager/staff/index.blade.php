<h1>Staff List</h1>

<a href="{{ route('manager.dashboard') }}">Back to Dashboard</a>

@if (session('success'))
    <div>{{ session('success') }}</div>
@endif

<div style="margin: 10px 0;">
    <a href="{{ route('manager.staff.create') }}">Create Employee</a>
</div>

<table border="1" cellpadding="10">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Created At</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($staff as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->role }}</td>
                <td>{{ $user->created_at }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="4">No staff found.</td>
            </tr>
        @endforelse
    </tbody>
</table>