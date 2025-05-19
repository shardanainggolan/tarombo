@extends('admin.layouts.index')

@section('title', 'Edit Pengguna - Tarombo')

@section('styles')
<link rel="stylesheet" href="{{ asset('admin/css/select2.css') }}" />
@endsection

@section('content')
<div class="container">
    <h1>Edit User: {{ $user_item->name }}</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route("admin.users.update", $user_item) }}" method="POST">
        @csrf
        @method("PUT")

        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old("name", $user_item->name) }}" required>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ old("email", $user_item->email) }}" required>
        </div>

        <div class="form-group">
            <label for="password">New Password (leave blank to keep current password):</label>
            <input type="password" name="password" id="password" class="form-control">
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirm New Password:</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
        </div>

        <div class="form-group">
            <label for="current_family_id">Current Active Family (Optional):</label>
            <select name="current_family_id" id="current_family_id" class="form-control">
                <option value="">None</option>
                {{-- Assuming $families is passed from UserController@edit --}}
                @if(isset($families))
                    @foreach($families as $family)
                        <option value="{{ $family->id }}" {{ old("current_family_id", $user_item->current_family_id) == $family->id ? "selected" : "" }}>
                            {{ $family->family_name }} (ID: {{ $family->id }})
                        </option>
                    @endforeach
                @endif
            </select>
        </div>

        <button type="submit" class="btn btn-success">Update User</button>
        <a href="{{ route("admin.users.show", $user_item) }}" class="btn">Cancel</a>
    </form>
</div>
@endsection

@push('scripts')

@endpush