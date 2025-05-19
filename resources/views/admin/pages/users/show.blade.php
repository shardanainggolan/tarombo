@extends('admin.layouts.index')

@section('title', 'Tambah Pengguna - Tarombo')

@section('styles')
<link rel="stylesheet" href="{{ asset('admin/css/select2.css') }}" />
@endsection

@section('content')
<div class="container">
    <h1>User Details: {{ $user_item->name }}</h1>

    <div class="card">
        <div class="card-body">
            <p><strong>ID:</strong> {{ $user_item->id }}</p>
            <p><strong>Name:</strong> {{ $user_item->name }}</p>
            <p><strong>Email:</strong> {{ $user_item->email }}</p>
            <p><strong>Email Verified At:</strong> {{ $user_item->email_verified_at ? $user_item->email_verified_at->format("d M Y, H:i") : "Not verified" }}</p>
            
            <p><strong>Current Active Family:</strong> 
                @if($user_item->currentFamily)
                    <a href="{{ route("admin.families.show", $user_item->currentFamily) }}">{{ $user_item->currentFamily->family_name }}</a>
                @else
                    N/A
                @endif
            </p>
            
            <p><strong>Family Member Profile:</strong> 
                @if($user_item->familyMemberProfile)
                    <a href="{{ route("admin.families.family_members.show", [$user_item->familyMemberProfile->family, $user_item->familyMemberProfile]) }}">
                        {{ $user_item->familyMemberProfile->full_name }} (in {{ $user_item->familyMemberProfile->family->family_name }})
                    </a>
                @else
                    Not linked to a family member profile.
                @endif
            </p>

            <p><strong>Registered At:</strong> {{ $user_item->created_at->format("d M Y, H:i") }}</p>
            <p><strong>Updated At:</strong> {{ $user_item->updated_at->format("d M Y, H:i") }}</p>
        </div>
    </div>

    <div style="margin-top: 20px;">
        <a href="{{ route("admin.users.edit", $user_item) }}" class="btn btn-warning">Edit User</a>
        <a href="{{ route("admin.users.index") }}" class="btn">Back to Users List</a>
        @if(Auth::id() !== $user_item->id)
            <form action="{{ route("admin.users.destroy", $user_item) }}" method="POST" style="display:inline-block; margin-left: 10px;">
                @csrf
                @method("DELETE")
                <button type="submit" class="btn btn-danger" onclick="return confirm("Are you sure you want to delete this user?")">Delete User</button>
            </form>
        @endif
    </div>

    <h3>Families Managed by {{ $user_item->name }} ({{ $user_item->families->count() }})</h3>
    @if($user_item->families && $user_item->families->count() > 0)
        <ul>
            @foreach($user_item->families as $family)
                <li>
                    <a href="{{ route("admin.families.show", $family) }}">{{ $family->family_name }}</a>
                </li>
            @endforeach
        </ul>
    @else
        <p>{{ $user_item->name }} is not managing any families.</p>
    @endif

</div>
@endsection

@push('scripts')

@endpush