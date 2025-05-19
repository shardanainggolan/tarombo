@extends('admin.layouts.index')

@section('title', 'Tambah Marga - Scriptura')

@section('styles')

@endsection

@section('content')
<div class="container">
    <h1>Sapaan Term Details: {{ $sapaanTerm->term }}</h1>

    <div class="card">
        <div class="card-body">
            <p><strong>ID:</strong> {{ $sapaanTerm->id }}</p>
            <p><strong>Term:</strong> {{ $sapaanTerm->term }}</p>
            <p><strong>Description:</strong></p>
            <p>{{ nl2br(e($sapaanTerm->description)) }}</p>
            <p><strong>Specific to Marga:</strong> {{ $sapaanTerm->marga ? $sapaanTerm->marga->name : "General" }}</p>
            <p><strong>Created At:</strong> {{ $sapaanTerm->created_at->format("d M Y, H:i") }}</p>
            <p><strong>Updated At:</strong> {{ $sapaanTerm->updated_at->format("d M Y, H:i") }}</p>
        </div>
    </div>

    <div style="margin-top: 20px;">
        <a href="{{ route("admin.sapaan_terms.edit", $sapaanTerm) }}" class="btn btn-warning">Edit</a>
        <a href="{{ route("admin.sapaan_terms.index") }}" class="btn">Back to List</a>
        <form action="{{ route("admin.sapaan_terms.destroy", $sapaanTerm) }}" method="POST" style="display:inline-block; margin-left: 10px;">
            @csrf
            @method("DELETE")
            <button type="submit" class="btn btn-danger" onclick="return confirm("Are you sure you want to delete this term? This might affect existing sapaan rules.")">Delete</button>
        </form>
    </div>

    {{-- Section to display Sapaan Rules using this term --}}
    <h3>Sapaan Rules Using This Term ({{ $sapaanTerm->sapaanRules->count() }})</h3>
    @if($sapaanTerm->sapaanRules && $sapaanTerm->sapaanRules->count() > 0)
        <ul>
            @foreach($sapaanTerm->sapaanRules->take(10) as $rule)
                <li>
                    <a href="{{ route("admin.sapaan_rules.show", $rule) }}">
                        Rule ID {{ $rule->id }}: {{ $rule->relationship_type }} 
                        (Marga: {{ $rule->marga ? $rule->marga->name : "General" }})
                    </a>
                </li>
            @endforeach
            @if($sapaanTerm->sapaanRules->count() > 10)
                <li>And {{ $sapaanTerm->sapaanRules->count() - 10 }} more...</li>
            @endif
        </ul>
    @else
        <p>No sapaan rules are currently using this term.</p>
    @endif

</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        
    })
</script>
@endpush