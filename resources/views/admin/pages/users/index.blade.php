@extends('admin.layouts.index')

@section('title', 'Daftar Pengguna - Tarombo')

@section('styles')
<link rel="stylesheet" href="{{ asset('admin/css/datatables.bootstrap5.css') }}" />
<link rel="stylesheet" href="{{ asset('admin/css/responsive.bootstrap5.css') }}" />
<link rel="stylesheet" href="{{ asset('admin/css/buttons.bootstrap5.css') }}" />
<link rel="stylesheet" href="{{ asset('admin/css/sweetalert2.css') }}" />
<link rel="stylesheet" href="{{ asset('css/fancybox.css') }}" />
@endsection

@section('content')
<div class="container">
    <h1>Users Management</h1>
    <a href="{{ route("admin.users.create") }}" class="btn btn-success" style="margin-bottom: 10px;">Create New User</a>

    @if($users->isEmpty())
        <p>No users found.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Current Family</th>
                    <th>Registered At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user_item) {{-- Renamed to avoid conflict with $user in auth --}}
                    <tr>
                        <td>{{ $user_item->id }}</td>
                        <td>{{ $user_item->name }}</td>
                        <td>{{ $user_item->email }}</td>
                        <td>{{ $user_item->currentFamily ? $user_item->currentFamily->family_name : "N/A" }}</td>
                        <td>{{ $user_item->created_at->format("d M Y") }}</td>
                        <td>
                            <a href="{{ route("admin.users.show", $user_item) }}" class="btn">View</a>
                            <a href="{{ route("admin.users.edit", $user_item) }}" class="btn btn-warning">Edit</a>
                            @if(Auth::id() !== $user_item->id) {{-- Prevent deleting self --}}
                                <form action="{{ route("admin.users.destroy", $user_item) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method("DELETE")
                                    <button type="submit" class="btn btn-danger" onclick="return confirm("Are you sure you want to delete this user? This might affect families they manage.")">Delete</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{-- For pagination links --}}
        {{-- $users->links() --}}
    @endif
</div>
@endsection

@push('scripts')

@endpush