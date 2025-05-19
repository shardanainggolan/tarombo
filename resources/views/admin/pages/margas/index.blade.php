@extends('admin.layouts.index')

@section('title', 'Daftar Marga - Tarombo')

@section('styles')
<link rel="stylesheet" href="{{ asset('admin/css/datatables.bootstrap5.css') }}" />
<link rel="stylesheet" href="{{ asset('admin/css/responsive.bootstrap5.css') }}" />
<link rel="stylesheet" href="{{ asset('admin/css/buttons.bootstrap5.css') }}" />
<link rel="stylesheet" href="{{ asset('admin/css/sweetalert2.css') }}" />
<link rel="stylesheet" href="{{ asset('css/fancybox.css') }}" />
@endsection

@section('content')
<div class="container">
    <h1>Marga List</h1>
    <a href="{{ route("admin.margas.create") }}" class="btn btn-success" style="margin-bottom: 10px;">Create New Marga</a>

    @if($margas->isEmpty())
        <p>No margas found.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($margas as $marga)
                    <tr>
                        <td>{{ $marga->id }}</td>
                        <td>{{ $marga->name }}</td>
                        <td>{{ Str::limit($marga->description, 50) }}</td>
                        <td>
                            <a href="{{ route("admin.margas.show", $marga) }}" class="btn">View</a>
                            <a href="{{ route("admin.margas.edit", $marga) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route("admin.margas.destroy", $marga) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method("DELETE")
                                <button type="submit" class="btn btn-danger" onclick="return confirm("Are you sure?")">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{-- For pagination links --}}
        {{-- $margas->links() --}}
    @endif
</div>
@endsection
