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
    <h1>Sapaan Terms</h1>
    <a href="{{ route("admin.sapaan_terms.create") }}" class="btn btn-success" style="margin-bottom: 10px;">Create New Sapaan Term</a>

    {{-- Add filter by Marga if needed --}}
    {{-- <form method="GET" action="{{ route("sapaan_terms.index") }}">
        <select name="marga_id" onchange="this.form.submit()">
            <option value="">All Margas</option>
            @foreach(App\Models\Marga::all() as $marga_filter)
                <option value="{{ $marga_filter->id }}" {{ request("marga_id") == $marga_filter->id ? "selected" : "" }}>
                    {{ $marga_filter->name }}
                </option>
            @endforeach
        </select>
    </form> --}}

    @if($sapaanTerms->isEmpty())
        <p>No sapaan terms found.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Term</th>
                    <th>Description</th>
                    <th>Marga Specific</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sapaanTerms as $term)
                    <tr>
                        <td>{{ $term->id }}</td>
                        <td>{{ $term->term }}</td>
                        <td>{{ Str::limit($term->description, 70) }}</td>
                        <td>{{ $term->marga ? $term->marga->name : "General" }}</td>
                        <td>
                            <a href="{{ route("admin.sapaan_terms.show", $term) }}" class="btn">View</a>
                            <a href="{{ route("admin.sapaan_terms.edit", $term) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route("admin.sapaan_terms.destroy", $term) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method("DELETE")
                                <button type="submit" class="btn btn-danger" onclick="return confirm("Are you sure? This might affect sapaan rules.")">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{-- For pagination links --}}
        {{-- $sapaanTerms->appends(request()->query())->links() --}}
    @endif
</div>
@endsection
