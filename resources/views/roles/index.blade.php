@extends('layouts.app')

@section('content')
<div class="container">

    <div class="header form-control d-flex justify-content-between align-items-center">
        <form method="GET" action="{{ route('roles.index') }}" class="d-flex">
            <input type="text" name="search" value="{{ old('search', $search) }}" placeholder="Search Roles" class="form-control mr-2">
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
        @can('create roles')
            <a href="{{ route('roles.create') }}" class="btn btn-secondary">Create</a>
        @endcan
    </div>
    <x-flash-messages />
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($roles as $index => $role)
                <tr>
                    <td>{{ $index + 1 }}
                    <td>{{ $role->name }}</td>
                    <td>
                    @can('edit roles')
                        <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    @endcan
                    @can('delete roles')
                        <form action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this role?');">Delete</button>
                            </form>
                        </td>
                    @endcan
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $roles->links() }}
</div>
@endsection
