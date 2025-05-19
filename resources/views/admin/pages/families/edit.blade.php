@extends('admin.layouts.index')

@section('title', 'Edit Marga - Tarombo')

@section('styles')

@endsection

@section('content')
<div class="container">
    <h1>Edit Family: {{ $family->family_name }}</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route("admin.families.update", $family) }}" method="POST">
        @csrf
        @method("PUT")

        <div class="form-group">
            <label for="family_name">Family Name:</label>
            <input type="text" name="family_name" id="family_name" class="form-control" value="{{ old("family_name", $family->family_name) }}" required>
        </div>

        <div class="form-group">
            <label for="marga_id">Marga (Optional):</label>
            <select name="marga_id" id="marga_id" class="form-control">
                <option value="">Select Marga (if applicable)</option>
                @foreach($margas as $marga_option) {{-- Renamed variable to avoid conflict --}}
                    <option value="{{ $marga_option->id }}" {{ old("marga_id", $family->marga_id) == $marga_option->id ? "selected" : "" }}>
                        {{ $marga_option->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="description">Description:</label>
            <textarea name="description" id="description" class="form-control">{{ old("description", $family->description) }}</textarea>
        </div>

        <div class="form-group">
            <label for="is_public">Is Publicly Viewable?</label>
            <select name="is_public" id="is_public" class="form-control">
                <option value="0" {{ old("is_public", $family->is_public ? 1 : 0) == 0 ? "selected" : "" }}>No</option>
                <option value="1" {{ old("is_public", $family->is_public ? 1 : 0) == 1 ? "selected" : "" }}>Yes</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Update Family</button>
        <a href="{{ route("admin.families.show", $family) }}" class="btn">Cancel</a>
    </form>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        
    })
</script>
@endpush