@extends('admin.layouts.index')

@section('title', 'Tambah Marga - Scriptura')

@section('styles')

@endsection

@section('content')
<div class="container">
    <h1>Family Details: {{ $family->family_name }}</h1>

    <div class="card">
        <div class="card-body">
            <p><strong>ID:</strong> {{ $family->id }}</p>
            <p><strong>Family Name:</strong> {{ $family->family_name }}</p>
            <p><strong>Marga:</strong> {{ $family->marga ? $family->marga->name : "N/A" }}</p>
            <p><strong>Managed By:</strong> {{ $family->user->name }} ({{ $family->user->email }})</p>
            <p><strong>Description:</strong></p>
            <p>{{ nl2br(e($family->description)) }}</p>
            <p><strong>Is Public:</strong> {{ $family->is_public ? "Yes" : "No" }}</p>
            <p><strong>Total Members:</strong> {{ $family->family_members_count }}</p> {{-- Assuming withCount in controller --}}
            <p><strong>Created At:</strong> {{ $family->created_at->format("d M Y, H:i") }}</p>
            <p><strong>Updated At:</strong> {{ $family->updated_at->format("d M Y, H:i") }}</p>
        </div>
    </div>

    <div style="margin-top: 20px;">
        <a href="{{ route("admin.families.edit", $family) }}" class="btn btn-warning">Edit Family Details</a>
        <a href="{{ route("admin.families.family_members.index", $family) }}" class="btn btn-info">Manage Members ({{ $family->family_members_count }})</a>
        <a href="{{ route("admin.families.index") }}" class="btn">Back to Families List</a>
        <form action="{{ route("admin.families.destroy", $family) }}" method="POST" style="display:inline-block; margin-left: 10px;">
            @csrf
            @method("DELETE")
            <button type="submit" class="btn btn-danger" onclick="return confirm("Are you sure you want to delete this family and ALL its members? This action cannot be undone.")">Delete Family</button>
        </form>
    </div>

    <hr>
    <h2>Family Members</h2>
    {{-- Consider including a partial view for family members list here or linking to the dedicated members page --}}
    @if($family->familyMembers && $family->familyMembers->count() > 0)
        <ul>
            @foreach($family->familyMembers->take(10) as $member) {{-- Show a few members --}}
                <li>
                    <a href="{{ route("admin.families.family_members.show", [$family, $member]) }}">{{ $member->full_name }}</a>
                    ({{ $member->gender }})
                </li>
            @endforeach
            @if($family->familyMembers->count() > 10)
                <li>And {{ $family->familyMembers->count() - 10 }} more...</li>
            @endif
        </ul>
    @else
        <p>No members added to this family yet. <a href="{{ route("admin.families.family_members.create", $family) }}">Add a member now.</a></p>
    @endif

</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        
    })
</script>
@endpush