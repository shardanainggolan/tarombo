@extends('admin.layouts.index')

@section('title', 'Daftar Marga - Tarombo')

@section('styles')
<link rel="stylesheet" href="{{ asset('admin/css/datatables.bootstrap5.css') }}" />
<link rel="stylesheet" href="{{ asset('admin/css/responsive.bootstrap5.css') }}" />
<link rel="stylesheet" href="{{ asset('admin/css/buttons.bootstrap5.css') }}" />
<link rel="stylesheet" href="{{ asset('admin/css/sweetalert2.css') }}" />
<link rel="stylesheet" href="{{ asset('css/fancybox.css') }}" />
@endsection

@section('content')
<div class="container">
    <h1>Sapaan Rules</h1>
    <a href="{{ route("admin.sapaan_rules.create") }}" class="btn btn-success" style="margin-bottom: 10px;">Create New Sapaan Rule</a>

    {{-- Filters can be added here --}}
    {{-- Example: Filter by Marga, Relationship Type --}}

    @if($sapaanRules->isEmpty())
        <p>No sapaan rules found.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Marga</th>
                    <th>Relationship Type</th>
                    <th>Gender From</th>
                    <th>Gender To</th>
                    <th>Sapaan Term</th>
                    <th>Priority</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sapaanRules as $rule)
                    <tr>
                        <td>{{ $rule->id }}</td>
                        <td>{{ $rule->marga ? $rule->marga->name : "General" }}</td>
                        <td>{{ $rule->relationship_type }}</td>
                        <td>{{ $rule->gender_from ?: "Any" }}</td>
                        <td>{{ $rule->gender_to ?: "Any" }}</td>
                        <td>{{ $rule->sapaanTerm ? $rule->sapaanTerm->term : "N/A" }}</td>
                        <td>{{ $rule->priority }}</td>
                        <td>
                            <a href="{{ route("admin.sapaan_rules.show", $rule) }}" class="btn">View</a>
                            <a href="{{ route("admin.sapaan_rules.edit", $rule) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route("admin.sapaan_rules.destroy", $rule) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method("DELETE")
                                <button type="submit" class="btn btn-danger" onclick="return confirm("Are you sure?")">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{-- For pagination links --}}
        {{-- $sapaanRules->appends(request()->query())->links() --}}
    @endif
</div>
@endsection
