@extends('admin.layouts.index')

@section('title', 'Edit Marga - Tarombo')

@section('styles')

@endsection

@section('content')
<div class="container">
    <h1>Edit Spouse Relationship</h1>
    <p>Member: <a href="{{ route("admin.families.family_members.show", [$family, $familyMember]) }}">{{ $familyMember->full_name }}</a></p>
    <p>Spouse: <a href="{{ route("admin.families.family_members.show", [$family, $spouse]) }}">{{ $spouse->full_name }}</a></p>
    <p>Family: <a href="{{ route("admin.families.show", $family) }}">{{ $family->family_name }}</a></p>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route("admin.families.family_members.spouses.update", [$family, $familyMember, $spouse]) }}" method="POST">
        @csrf
        @method("PUT")

        <div class="form-group">
            <label for="marriage_date">Marriage Date (Optional):</label>
            <input type="date" name="marriage_date" id="marriage_date" class="form-control" value="{{ old("marriage_date", $relationship_pivot_data->marriage_date ? Carbon\Carbon::parse($relationship_pivot_data->marriage_date)->format("Y-m-d") : "") }}">
        </div>

        <div class="form-group">
            <label for="marriage_location">Marriage Location (Optional):</label>
            <input type="text" name="marriage_location" id="marriage_location" class="form-control" value="{{ old("marriage_location", $relationship_pivot_data->marriage_location) }}">
        </div>

        <div class="form-group">
            <label for="status">Relationship Status:</label>
            <select name="status" id="status" class="form-control" required>
                <option value="married" {{ old("status", $relationship_pivot_data->status) == "married" ? "selected" : "" }}>Married</option>
                <option value="divorced" {{ old("status", $relationship_pivot_data->status) == "divorced" ? "selected" : "" }}>Divorced</option>
                <option value="widowed" {{ old("status", $relationship_pivot_data->status) == "widowed" ? "selected" : "" }}>Widowed</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Update Relationship</button>
        <a href="{{ route("admin.families.family_members.spouses.index", [$family, $familyMember]) }}" class="btn">Cancel</a>
    </form>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        
    })
</script>
@endpush