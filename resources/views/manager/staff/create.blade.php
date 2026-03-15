<x-app-layout>

    <h1>Create Employee</h1>

    <a href="{{ route('manager.staff.index') }}">Back to Staff List</a>

    @if ($errors->any())
        <div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('manager.staff.store') }}">
        @csrf

        <div>
            <label for="name">Name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required>
        </div>

        <div>
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required>
        </div>

        <div>
            <label for="password">Password</label>
            <input id="password" type="password" name="password" required>
        </div>

        <div>
            <label for="password_confirmation">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required>
        </div>

        <button type="submit">Create Employee</button>
    </form>

</x-app-layout>