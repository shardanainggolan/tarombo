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
    <h1>My Families</h1>
    <a href="{{ route("admin.families.create") }}" class="btn btn-success" style="margin-bottom: 10px;">Create New Family</a>

    @if($families->isEmpty())
        <p>You have not created any families yet.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Family Name</th>
                    <th>Marga</th>
                    <th>Description</th>
                    <th>Public</th>
                    <th>Members</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($families as $family)
                    <tr>
                        <td>{{ $family->id }}</td>
                        <td>{{ $family->family_name }}</td>
                        <td>{{ $family->marga ? $family->marga->name : "N/A" }}</td>
                        <td>{{ Str::limit($family->description, 50) }}</td>
                        <td>{{ $family->is_public ? "Yes" : "No" }}</td>
                        <td>{{ $family->family_members_count }}</td> {{-- Assuming you add withCount in controller --}}
                        <td>
                            <a href="{{ route("admin.families.show", $family) }}" class="btn">View</a>
                            <a href="{{ route("admin.families.edit", $family) }}" class="btn btn-warning">Edit</a>
                            <a href="{{ route("admin.families.family_members.index", $family) }}" class="btn btn-info">Members</a> {{-- Link to family members --}}
                            <form action="{{ route("admin.families.destroy", $family) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method("DELETE")
                                <button type="submit" class="btn btn-danger" onclick="return confirm("Are you sure? This will delete the family and all its members.")">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{-- For pagination links --}}
        {{-- $families->links() --}}
    @endif
</div>
@endsection
