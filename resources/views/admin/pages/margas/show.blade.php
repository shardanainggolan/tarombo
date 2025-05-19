@extends('admin.layouts.index')

@section('title', 'Tambah Marga - Scriptura')

@section('styles')

@endsection

@section('content')
<div class="container">
    <h1>Marga Details: {{ $marga->name }}</h1>

    <div class="card">
        <div class="card-body">
            <p><strong>ID:</strong> {{ $marga->id }}</p>
            <p><strong>Name:</strong> {{ $marga->name }}</p>
            <p><strong>Description:</strong></p>
            <p>{{ nl2br(e($marga->description)) }}</p>
            <p><strong>Origin Story Link:</strong> 
                @if($marga->origin_story_link)
                    <a href="{{ $marga->origin_story_link }}" target="_blank">{{ $marga->origin_story_link }}</a>
                @else
                    N/A
                @endif
            </p>
            <p><strong>Created At:</strong> {{ $marga->created_at->format("d M Y, H:i") }}</p>
            <p><strong>Updated At:</strong> {{ $marga->updated_at->format("d M Y, H:i") }}</p>
        </div>
    </div>

    <div style="margin-top: 20px;">
        <a href="{{ route("admin.margas.edit", $marga) }}" class="btn btn-warning">Edit</a>
        <a href="{{ route("admin.margas.index") }}" class="btn">Back to List</a>
        <form action="{{ route("admin.margas.destroy", $marga) }}" method="POST" style="display:inline-block; margin-left: 10px;">
            @csrf
            @method("DELETE")
            <button type="submit" class="btn btn-danger" onclick="return confirm("Are you sure you want to delete this marga?")">Delete</button>
        </form>
    </div>

    {{-- You can add sections here to display related families, sapaan terms, or sapaan rules if needed --}}

</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        
    })
</script>
@endpush