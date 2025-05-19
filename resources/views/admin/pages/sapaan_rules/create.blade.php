@extends('admin.layouts.index')

@section('title', 'Tambah Marga - Scriptura')

@section('styles')

@endsection

@section('content')
<div class="container">
    <h1>Create New Sapaan Rule</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route("admin.sapaan_rules.store") }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="marga_id">Specific to Marga (Optional):</label>
            <select name="marga_id" id="marga_id" class="form-control">
                <option value="">General (Not Marga Specific)</option>
                @foreach($margas as $marga)
                    <option value="{{ $marga->id }}" {{ old("marga_id") == $marga->id ? "selected" : "" }}>
                        {{ $marga->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="relationship_type">Relationship Type:</label>
            <select name="relationship_type" id="relationship_type" class="form-control" required>
                <option value="">Select Relationship Type</option>
                @foreach($relationshipTypes as $type)
                    <option value="{{ $type }}" {{ old("relationship_type") == $type ? "selected" : "" }}>
                        {{ ucfirst(str_replace("_", " ", $type)) }}
                    </option>
                @endforeach
            </select>
            {{-- <input type="text" name="relationship_type" id="relationship_type" class="form-control" value="{{ old("relationship_type") }}" required> --}}
        </div>

        <div class="form-group">
            <label for="gender_from">Gender of Person Calling (Optional):</label>
            <select name="gender_from" id="gender_from" class="form-control">
                <option value="">Any</option>
                <option value="male" {{ old("gender_from") == "male" ? "selected" : "" }}>Male</option>
                <option value="female" {{ old("gender_from") == "female" ? "selected" : "" }}>Female</option>
                <option value="other" {{ old("gender_from") == "other" ? "selected" : "" }}>Other</option>
            </select>
        </div>

        <div class="form-group">
            <label for="gender_to">Gender of Person Being Called (Optional):</label>
            <select name="gender_to" id="gender_to" class="form-control">
                <option value="">Any</option>
                <option value="male" {{ old("gender_to") == "male" ? "selected" : "" }}>Male</option>
                <option value="female" {{ old("gender_to") == "female" ? "selected" : "" }}>Female</option>
                <option value="other" {{ old("gender_to") == "other" ? "selected" : "" }}>Other</option>
            </select>
        </div>

        <div class="form-group">
            <label for="sapaan_term_id">Sapaan Term to Use:</label>
            <select name="sapaan_term_id" id="sapaan_term_id" class="form-control" required>
                <option value="">Select Sapaan Term</option>
                @foreach($sapaanTerms as $term)
                    <option value="{{ $term->id }}" {{ old("sapaan_term_id") == $term->id ? "selected" : "" }}>
                        {{ $term->term }} ({{ $term->marga ? $term->marga->name : "General" }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="priority">Priority (Higher numbers are checked first, Optional, Default: 0):</label>
            <input type="number" name="priority" id="priority" class="form-control" value="{{ old("priority", 0) }}">
        </div>

        <div class="form-group">
            <label for="description">Description/Notes (Optional):</label>
            <textarea name="description" id="description" class="form-control">{{ old("description") }}</textarea>
        </div>

        <button type="submit" class="btn btn-success">Create Rule</button>
        <a href="{{ route("admin.sapaan_rules.index") }}" class="btn">Cancel</a>
    </form>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        
    })
</script>
@endpush