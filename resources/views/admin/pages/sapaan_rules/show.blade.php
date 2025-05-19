@extends('admin.layouts.index')

@section('title', 'Tambah Marga - Scriptura')

@section('styles')

@endsection

@section('content')
<div class="container">
    <h1>Sapaan Rule Details: ID {{ $sapaanRule->id }}</h1>

    <div class="card">
        <div class="card-body">
            <p><strong>ID:</strong> {{ $sapaanRule->id }}</p>
            <p><strong>Specific to Marga:</strong> {{ $sapaanRule->marga ? $sapaanRule->marga->name : "General" }}</p>
            <p><strong>Relationship Type:</strong> {{ ucfirst(str_replace("_", " ", $sapaanRule->relationship_type)) }}</p>
            <p><strong>Gender of Person Calling:</strong> {{ $sapaanRule->gender_from ? ucfirst($sapaanRule->gender_from) : "Any" }}</p>
            <p><strong>Gender of Person Being Called:</strong> {{ $sapaanRule->gender_to ? ucfirst($sapaanRule->gender_to) : "Any" }}</p>
            <p><strong>Sapaan Term Used:</strong> <a href="{{ route("admin.sapaan_terms.show", $sapaanRule->sapaanTerm) }}">{{ $sapaanRule->sapaanTerm->term }}</a></p>
            <p><strong>Priority:</strong> {{ $sapaanRule->priority }}</p>
            <p><strong>Description/Notes:</strong></p>
            <p>{{ nl2br(e($sapaanRule->description)) ?: "N/A" }}</p>
            <p><strong>Created At:</strong> {{ $sapaanRule->created_at->format("d M Y, H:i") }}</p>
            <p><strong>Updated At:</strong> {{ $sapaanRule->updated_at->format("d M Y, H:i") }}</p>
        </div>
    </div>

    <div style="margin-top: 20px;">
        <a href="{{ route("admin.sapaan_rules.edit", $sapaanRule) }}" class="btn btn-warning">Edit</a>
        <a href="{{ route("admin.sapaan_rules.index") }}" class="btn">Back to List</a>
        <form action="{{ route("admin.sapaan_rules.destroy", $sapaanRule) }}" method="POST" style="display:inline-block; margin-left: 10px;">
            @csrf
            @method("DELETE")
            <button type="submit" class="btn btn-danger" onclick="return confirm("Are you sure you want to delete this rule?")">Delete</button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        
    })
</script>
@endpush