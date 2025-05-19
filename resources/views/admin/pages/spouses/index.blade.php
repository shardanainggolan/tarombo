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
        <h1>Spouses of {{ $familyMember->full_name }}</h1>
        <div>
            <a href="{{ route("admin.families.family_members.show", [$family, $familyMember]) }}" class="btn">Back to {{ $familyMember->full_name }}'s Profile</a>
            <a href="{{ route("admin.families.show", $family) }}" class="btn">Back to {{ $family->family_name }}</a>
        </div>
    </div>
    <p>Family: <a href="{{ route("admin.families.show", $family) }}">{{ $family->family_name }}</a></p>

    <a href="{{ route("admin.families.family_members.spouses.create", [$family, $familyMember]) }}" class="btn btn-success" style="margin-bottom: 10px;">Add New Spouse Relationship</a>

    @if($familyMember->spouses->isEmpty())
        <p>{{ $familyMember->full_name }} has no recorded spouse relationships.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Spouse Name</th>
                    <th>Marriage Date</th>
                    <th>Marriage Location</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($familyMember->spouses as $spouseMember)
                    <tr>
                        <td><a href="{{ route("admin.families.family_members.show", [$family, $spouseMember]) }}">{{ $spouseMember->full_name }}</a></td>
                        <td>{{ $spouseMember->pivot->marriage_date ? Carbon\Carbon::parse($spouseMember->pivot->marriage_date)->format("d M Y") : "N/A" }}</td>
                        <td>{{ $spouseMember->pivot->marriage_location ?: "N/A" }}</td>
                        <td>{{ ucfirst($spouseMember->pivot->status) }}</td>
                        <td>
                            <a href="{{ route("admin.families.family_members.spouses.edit", [$family, $familyMember, $spouseMember]) }}" class="btn btn-warning">Edit Relationship</a>
                            <form action="{{ route("admin.families.family_members.spouses.destroy", [$family, $familyMember, $spouseMember]) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method("DELETE")
                                <button type="submit" class="btn btn-danger" onclick="return confirm("Are you sure you want to remove this spouse relationship?")">Remove</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
