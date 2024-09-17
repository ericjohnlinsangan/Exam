@extends('layouts.app')

@section('content')
<div class="container">
<div class="header form-control d-flex justify-content-between align-items-center">
        <form method="GET" action="{{ route('permissions.index') }}" class="d-flex">
            <input type="text" name="search" value="{{ old('search', $search) }}" placeholder="Search Permissions" class="form-control mr-2">
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
            </tr>
        </thead>
        <tbody>
            @foreach($permissions as $index => $permission)
                <tr>
                    <td>{{ $index + 1 }}
                    <td>{{ ucfirst($permission->name) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $permissions->links() }}
</div>
@endsection
