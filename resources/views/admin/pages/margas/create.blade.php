@extends('admin.layouts.index')

@section('title', 'Tambah Marga - Scriptura')

@section('styles')

@endsection

@section('content')
<div class="container">
    <h1>Create New Marga</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route("admin.margas.store") }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old("name") }}" required>
        </div>

        <div class="form-group">
            <label for="description">Description:</label>
            <textarea name="description" id="description" class="form-control">{{ old("description") }}</textarea>
        </div>

        <div class="form-group">
            <label for="origin_story_link">Origin Story Link (URL):</label>
            <input type="url" name="origin_story_link" id="origin_story_link" class="form-control" value="{{ old("origin_story_link") }}">
        </div>

        <button type="submit" class="btn btn-success">Create Marga</button>
        <a href="{{ route("admin.margas.index") }}" class="btn">Cancel</a>
    </form>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        
    })
</script>
@endpush