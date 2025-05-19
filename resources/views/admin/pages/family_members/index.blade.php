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
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h1>Members of "{{ $family->family_name }}"</h1>
        <a href="{{ route("admin.families.show", $family) }}" class="btn">Back to Family Details</a>
    </div>
    
    <a href="{{ route("admin.families.family_members.create", $family) }}" class="btn btn-success" style="margin-bottom: 10px;">Add New Member</a>

    @if($familyMembers->isEmpty())
        <p>No members found in this family yet.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Gender</th>
                    <th>Birth Date</th>
                    <th>Father</th>
                    <th>Mother</th>
                    <th>Spouses</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($familyMembers as $member)
                    <tr>
                        <td>{{ $member->id }}</td>
                        <td>{{ $member->full_name }}</td>
                        <td>{{ ucfirst($member->gender) }}</td>
                        <td>{{ $member->birth_date ? $member->birth_date->format("d M Y") : "N/A" }}</td>
                        <td>{{ $member->father ? $member->father->full_name : "N/A" }}</td>
                        <td>{{ $member->mother ? $member->mother->full_name : "N/A" }}</td>
                        <td>
                            @if($member->currentSpouses && $member->currentSpouses->count() > 0)
                                @foreach($member->currentSpouses as $spouse)
                                    {{ $spouse->full_name }}@if(!$loop->last), @endif
                                @endforeach
                            @else
                                N/A
                            @endif
                        </td>
                        <td>
                            <a href="{{ route("admin.families.family_members.show", [$family, $member]) }}" class="btn">View</a>
                            <a href="{{ route("admin.families.family_members.edit", [$family, $member]) }}" class="btn btn-warning">Edit</a>
                            {{-- Link to manage spouses for this member --}}
                            <a href="{{ route("admin.families.family_members.spouses.index", [$family, $member]) }}" class="btn btn-info">Spouses</a> 
                            <form action="{{ route("families.family_members.destroy", [$family, $member]) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method("DELETE")
                                <button type="submit" class="btn btn-danger" onclick="return confirm("Are you sure you want to delete this family member? This might affect other relationships.")">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{-- For pagination links --}}
        {{-- $familyMembers->links() --}}
    @endif
</div>
@endsection
