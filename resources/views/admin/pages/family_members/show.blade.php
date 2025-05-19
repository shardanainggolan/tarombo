@extends('admin.layouts.index')

@section('title', 'Tambah Marga - Scriptura')

@section('styles')

@endsection

@section('content')
<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h1>Member Details: {{ $familyMember->full_name }}</h1>
        <a href="{{ route("admin.families.family_members.index", $family) }}" class="btn">Back to Members List of "{{$family->family_name}}"</a>
    </div>

    <div class="card">
        <div class="card-body">
            <p><strong>ID:</strong> {{ $familyMember->id }}</p>
            <p><strong>Full Name:</strong> {{ $familyMember->full_name }}</p>
            <p><strong>Nickname:</strong> {{ $familyMember->nickname ?: "N/A" }}</p>
            <p><strong>Gender:</strong> {{ ucfirst($familyMember->gender) }}</p>
            <p><strong>Birth Date:</strong> {{ $familyMember->birth_date ? $familyMember->birth_date->format("d M Y") : "N/A" }}</p>
            <p><strong>Birth Place:</strong> {{ $familyMember->birth_place ?: "N/A" }}</p>
            <p><strong>Death Date:</strong> {{ $familyMember->death_date ? $familyMember->death_date->format("d M Y") : "N/A" }}</p>
            <p><strong>Death Place:</strong> {{ $familyMember->death_place ?: "N/A" }}</p>
            
            <p><strong>Father:</strong> 
                @if($familyMember->father)
                    <a href="{{ route("admin.families.family_members.show", [$family, $familyMember->father]) }}">{{ $familyMember->father->full_name }}</a>
                @else
                    N/A
                @endif
            </p>
            <p><strong>Mother:</strong> 
                @if($familyMember->mother)
                    <a href="{{ route("admin.families.family_members.show", [$family, $familyMember->mother]) }}">{{ $familyMember->mother->full_name }}</a>
                @else
                    N/A
                @endif
            </p>
            <p><strong>Order in Siblings:</strong> {{ $familyMember->order_in_siblings ?: "N/A" }}</p>
            
            <p><strong>Current Spouses:</strong>
                @if($familyMember->currentSpouses && $familyMember->currentSpouses->count() > 0)
                    @foreach($familyMember->currentSpouses as $spouse)
                        <a href="{{ route("admin.families.family_members.show", [$family, $spouse]) }}">{{ $spouse->full_name }}</a> (Married{{ $spouse->pivot->marriage_date ? " on " . 
                        Carbon\Carbon::parse($spouse->pivot->marriage_date)->format("d M Y") : "" }})
                        @if(!$loop->last), @endif
                    @endforeach
                @else
                    N/A
                @endif
            </p>

            <p><strong>Bio:</strong></p>
            <p>{{ nl2br(e($familyMember->bio)) ?: "N/A" }}</p>
            
            <p><strong>Linked User Account:</strong> 
                @if($familyMember->userAccount)
                    {{ $familyMember->userAccount->name }} ({{ $familyMember->userAccount->email }})
                @else
                    Not linked
                @endif
            </p>
            {{-- Profile picture display can be added later --}}
            <p><strong>Created At:</strong> {{ $familyMember->created_at->format("d M Y, H:i") }}</p>
            <p><strong>Updated At:</strong> {{ $familyMember->updated_at->format("d M Y, H:i") }}</p>
        </div>
    </div>

    <div style="margin-top: 20px;">
        <a href="{{ route("admin.families.family_members.edit", [$family, $familyMember]) }}" class="btn btn-warning">Edit Member</a>
        <a href="{{ route("admin.families.family_members.spouses.index", [$family, $familyMember]) }}" class="btn btn-info">Manage Spouses</a>
        <form action="{{ route("admin.families.family_members.destroy", [$family, $familyMember]) }}" method="POST" style="display:inline-block; margin-left: 10px;">
            @csrf
            @method("DELETE")
            <button type="submit" class="btn btn-danger" onclick="return confirm("Are you sure you want to delete this family member?")">Delete Member</button>
        </form>
    </div>

    {{-- Section for Children --}}
    <h3>Children</h3>
    @if($familyMember->children && $familyMember->children->count() > 0)
        <ul>
            @foreach($familyMember->children as $child)
                <li><a href="{{ route("admin.families.family_members.show", [$family, $child]) }}">{{ $child->full_name }}</a> ({{ $child->gender }})</li>
            @endforeach
        </ul>
    @else
        <p>No children recorded for this member.</p>
    @endif

</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        
    })
</script>
@endpush