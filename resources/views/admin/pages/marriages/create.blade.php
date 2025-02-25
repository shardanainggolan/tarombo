@extends('admin.layouts.index')

@section('title', 'Tambah Pernikahan - Tarombo')

@section('styles')

@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Tambah Pernikahan</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.marriages.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="husband_id">Suami</label>
                    <select name="husband_id" id="husband_id" class="form-control" required>
                        @foreach ($males as $male)
                            <option value="{{ $male->id }}">{{ $male->first_name }} {{ $male->last_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="wife_id">Istri</label>
                    <select name="wife_id" id="wife_id" class="form-control" required>
                        @foreach ($females as $female)
                            <option value="{{ $female->id }}">{{ $female->first_name }} {{ $female->last_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="marriage_date">Tanggal Pernikahan</label>
                    <input type="date" name="marriage_date" id="marriage_date" class="form-control" />
                </div>
                <div class="mb-3">
                    <label for="status">Status Pernikahan</label>
                    <select name="status" id="status" class="form-control" required>
                        <option value="active">Aktif</option>
                        <option value="divorced">Cerai</option>
                        <option value="widowed">Duda/Janda</option>
                    </select>
                </div>
                <div class="tw-flex">
                    <button type="submit" class="btn btn-primary waves-effect waves-light">
                        Simpan
                    </button>
                    <a href="{{ route('admin.relationships.index') }}">
                        <button type="button" class="btn btn-danger waves-effect waves-light">
                            Kembali
                        </button>
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        
    })
</script>
@endpush