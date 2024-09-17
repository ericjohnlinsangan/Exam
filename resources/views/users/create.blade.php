@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Header Section -->
    <div class="header form-control d-flex justify-content-between align-items-center mb-3">
        <h2>{{ isset($user) ? 'Edit User' : 'Create User' }}</h2>
    </div>

    <div class="mb-4">
        <form method="POST" action="{{ isset($user) ? route('users.update', $user->id) : route('users.store') }}">
            @csrf
            @if(isset($user))
                @method('PUT')
            @endif

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Form Fields -->
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name ?? '') }}" required>
                    @error('name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email ?? '') }}" required>
                    @error('email')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="role">Role</label>
                    <select name="role" id="role" class="form-control" required>
                        <option value="">Select a role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" {{ isset($user) && $user->roles->contains($role->id) ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('role')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            @if(isset($user))
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" class="form-control">
                        @error('password')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="password_confirmation">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                    </div>
                </div>
            @endif
            <br>
            <button type="submit" class="btn btn-success">{{ isset($user) ? 'Update User' : 'Create User' }}</button>
        </form>
    </div>
</div>
@endsection
