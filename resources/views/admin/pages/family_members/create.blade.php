@extends('admin.layouts.index')

@section('title', 'Tambah Marga - Scriptura')

@section('styles')

@endsection

@section('content')
<div class="container">
    <h1>Add New Member to "{{ $family->family_name }}"</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route("admin.families.family_members.store", $family) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="full_name">Full Name:</label>
            <input type="text" name="full_name" id="full_name" class="form-control" value="{{ old("full_name") }}" required>
        </div>

        <div class="form-group">
            <label for="nickname">Nickname (Optional):</label>
            <input type="text" name="nickname" id="nickname" class="form-control" value="{{ old("nickname") }}">
        </div>

        <div class="form-group">
            <label for="gender">Gender:</label>
            <select name="gender" id="gender" class="form-control" required>
                <option value="male" {{ old("gender") == "male" ? "selected" : "" }}>Male</option>
                <option value="female" {{ old("gender") == "female" ? "selected" : "" }}>Female</option>
                <option value="other" {{ old("gender") == "other" ? "selected" : "" }}>Other</option>
            </select>
        </div>

        <div class="form-group">
            <label for="birth_date">Birth Date (Optional):</label>
            <input type="date" name="birth_date" id="birth_date" class="form-control" value="{{ old("birth_date") }}">
        </div>

        <div class="form-group">
            <label for="birth_place">Birth Place (Optional):</label>
            <input type="text" name="birth_place" id="birth_place" class="form-control" value="{{ old("birth_place") }}">
        </div>

        <div class="form-group">
            <label for="death_date">Death Date (Optional):</label>
            <input type="date" name="death_date" id="death_date" class="form-control" value="{{ old("death_date") }}">
        </div>

        <div class="form-group">
            <label for="death_place">Death Place (Optional):</label>
            <input type="text" name="death_place" id="death_place" class="form-control" value="{{ old("death_place") }}">
        </div>

        <div class="form-group">
            <label for="father_id">Father (Optional, must be in this family):</label>
            <select name="father_id" id="father_id" class="form-control">
                <option value="">Select Father (if known)</option>
                @foreach($potentialParents->where("gender", "male") as $father)
                    <option value="{{ $father->id }}" {{ old("father_id") == $father->id ? "selected" : "" }}>
                        {{ $father->full_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="mother_id">Mother (Optional, must be in this family):</label>
            <select name="mother_id" id="mother_id" class="form-control">
                <option value="">Select Mother (if known)</option>
                @foreach($potentialParents->where("gender", "female") as $mother)
                    <option value="{{ $mother->id }}" {{ old("mother_id") == $mother->id ? "selected" : "" }}>
                        {{ $mother->full_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="order_in_siblings">Order in Siblings (e.g., 1 for eldest, Optional):</label>
            <input type="number" name="order_in_siblings" id="order_in_siblings" class="form-control" value="{{ old("order_in_siblings") }}" min="1">
        </div>

        <div class="form-group">
            <label for="bio">Bio (Optional):</label>
            <textarea name="bio" id="bio" class="form-control">{{ old("bio") }}</textarea>
        </div>
        
        <div class="form-group">
            <label for="user_account_id">Link to User Account (Optional):</label>
            <select name="user_account_id" id="user_account_id" class="form-control">
                <option value="">None</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ old("user_account_id") == $user->id ? "selected" : "" }}>
                        {{ $user->name }} ({{ $user->email }})
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Profile picture upload can be added later --}}

        <button type="submit" class="btn btn-success">Add Member</button>
        <a href="{{ route("admin.families.family_members.index", $family) }}" class="btn">Cancel</a>
    </form>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        
    })
</script>
@endpush