@extends('admin.layouts.index')

@section('title', 'Edit Marga - Tarombo')

@section('styles')

@endsection

@section('content')
<div class="container">
    <h1>Edit Sapaan Term: {{ $sapaanTerm->term }}</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route("admin.sapaan_terms.update", $sapaanTerm) }}" method="POST">
        @csrf
        @method("PUT")

        <div class="form-group">
            <label for="term">Term:</label>
            <input type="text" name="term" id="term" class="form-control" value="{{ old("term", $sapaanTerm->term) }}" required>
        </div>

        <div class="form-group">
            <label for="description">Description (Optional):</label>
            <textarea name="description" id="description" class="form-control">{{ old("description", $sapaanTerm->description) }}</textarea>
        </div>

        <div class="form-group">
            <label for="marga_id">Specific to Marga (Optional):</label>
            <select name="marga_id" id="marga_id" class="form-control">
                <option value="">General (Not Marga Specific)</option>
                @foreach($margas as $marga)
                    <option value="{{ $marga->id }}" {{ old("marga_id", $sapaanTerm->marga_id) == $marga->id ? "selected" : "" }}>
                        {{ $marga->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success">Update Term</button>
        <a href="{{ route("admin.sapaan_terms.index") }}" class="btn">Cancel</a>
    </form>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        
    })
</script>
@endpush