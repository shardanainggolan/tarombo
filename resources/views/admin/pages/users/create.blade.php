@extends('admin.layouts.index')

@section('title', 'Tambah Pengguna - Tarombo')

@section('styles')
<link rel="stylesheet" href="{{ asset('admin/css/select2.css') }}" />
@endsection

@section('content')
<div class="container">
    <h1>Create New User</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route("admin.users.store") }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old("name") }}" required>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ old("email") }}" required>
        </div>

        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirm Password:</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="current_family_id">Current Active Family (Optional):</label>
            <select name="current_family_id" id="current_family_id" class="form-control">
                <option value="">None</option>
                {{-- Assuming $families is passed from UserController@create --}}
                @if(isset($families))
                    @foreach($families as $family)
                        <option value="{{ $family->id }}" {{ old("current_family_id") == $family->id ? "selected" : "" }}>
                            {{ $family->family_name }} (ID: {{ $family->id }})
                        </option>
                    @endforeach
                @endif
            </select>
        </div>

        <button type="submit" class="btn btn-success">Create User</button>
        <a href="{{ route("admin.users.index") }}" class="btn">Cancel</a>
    </form>
</div>
@endsection

@push('scripts')

@endpush