@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Header Section -->
    <div class="header form-control d-flex justify-content-between align-items-center mb-3">
        <h2>{{ isset($role) ? 'Edit Role' : 'Create Role' }}</h2>
    </div>

    <div class="mb-4">
        <form method="POST" action="{{ isset($role) ? route('roles.update', $role->id) : route('roles.store') }}">
            @csrf
            @if(isset($role))
                @method('PUT')
            @endif

            <x-flash-messages />
            <!-- Role Name Field -->
            <div class="form-group">
                <label for="name">Role Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $role->name ?? '') }}" required>
                @error('name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- Permissions Field -->
            <br>
            <div class="form-group">
                <label for="permissions">Assign Permissions</label>
                <div class="row">
                    @foreach($permissions as $permission)
                        <div class="col-md-3">
                            <div class="form-check">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" 
                                    id="permission-{{ $permission->id }}" class="form-check-input"
                                    {{ isset($role) && $role->permissions->contains($permission->id) ? 'checked' : '' }}>
                                <label class="form-check-label" for="permission-{{ $permission->id }}">
                                    {{ ucfirst($permission->name) }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
                @error('permissions')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <br>
            <button type="submit" class="btn btn-success">{{ isset($role) ? 'Update Role' : 'Create Role' }}</button>
        </form>
    </div>
</div>
@endsection
