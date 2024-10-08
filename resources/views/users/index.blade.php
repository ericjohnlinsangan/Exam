@extends('layouts.app')

@section('content')
<div class="container">
    <div class="header form-control d-flex justify-content-between align-items-center">
        <form method="GET" action="{{ route('users.index') }}" class="d-flex">
            <input type="text" name="search" value="{{ old('search', $search) }}" placeholder="Search users" class="form-control mr-2">
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
        @can('create users')
        <a href="{{ route('users.create') }}" class="btn btn-secondary">Create</a>
        @endcan()
    </div>
    <x-flash-messages />
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->getRoleNames()->first() }}</td>
                    <td>
                        @can('edit users')
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        @endcan
                        @can('delete users')
                            @if ($user->id !== auth()->id())
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?');">Delete</button>
                                </form>
                            @endif
                        @endcan
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $users->links() }}
</div>
@endsection
