@extends('admin.layouts.index')

@section('title', 'Tambah Hubungan - Tarombo')

@section('styles')

@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Tambah Hubungan</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.relationships.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="individual_id">Individu</label>
                    <select name="individual_id" id="individual_id" class="form-control" required>
                        @foreach ($individuals as $indi)
                            <option value="{{ $indi->id }}">{{ $indi->first_name }} {{ $indi->last_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="relationship_type">Tipe Hubungan</label>
                    <select name="relationship_type" id="relationship_type" class="form-control" required>
                        <option value="father">Ayah</option>
                        <option value="mother">Ibu</option>
                        <option value="husband">Suami</option>
                        <option value="wife">Istri</option>
                        <option value="son">Anak Laki-laki</option>
                        <option value="daughter">Anak Perempuan</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="related_individual_id">Individu Terkait</label>
                    <select name="related_individual_id" id="related_individual_id" class="form-control" required>
                        @foreach ($individuals as $indi)
                            <option value="{{ $indi->id }}">{{ $indi->first_name }} {{ $indi->last_name }}</option>
                        @endforeach
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