@extends('admin.layouts.index')

@section('title', 'Tambah Marga - Scriptura')

@section('styles')

@endsection

@section('content')
<div class="container">
    <h1>Add Spouse Relationship for {{ $familyMember->full_name }}</h1>
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

    <form action="{{ route("admin.families.family_members.spouses.store", [$family, $familyMember]) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="spouse_id">Select Spouse (must be in the same family, not self, not already a spouse):</label>
            <select name="spouse_id" id="spouse_id" class="form-control" required>
                <option value="">Select a Family Member</option>
                @foreach($potentialSpouses as $potentialSpouse)
                    <option value="{{ $potentialSpouse->id }}" {{ old("spouse_id") == $potentialSpouse->id ? "selected" : "" }}>
                        {{ $potentialSpouse->full_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="marriage_date">Marriage Date (Optional):</label>
            <input type="date" name="marriage_date" id="marriage_date" class="form-control" value="{{ old("marriage_date") }}">
        </div>

        <div class="form-group">
            <label for="marriage_location">Marriage Location (Optional):</label>
            <input type="text" name="marriage_location" id="marriage_location" class="form-control" value="{{ old("marriage_location") }}">
        </div>

        <div class="form-group">
            <label for="status">Relationship Status:</label>
            <select name="status" id="status" class="form-control" required>
                <option value="married" {{ old("status") == "married" ? "selected" : "" }}>Married</option>
                <option value="divorced" {{ old("status") == "divorced" ? "selected" : "" }}>Divorced</option>
                <option value="widowed" {{ old("status") == "widowed" ? "selected" : "" }}>Widowed</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Add Spouse Relationship</button>
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