@extends('admin.layouts.index')

@section('title', 'Tambah Orang - Tarombo')

@section('styles')
<link rel="stylesheet" href="{{ asset('admin/css/flatpickr.min.css') }}" />
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Tambah Orang</h5>
            <a href="{{ route('admin.people.index') }}" class="btn btn-secondary">
                <i class="ti ti-arrow-left me-sm-1"></i> Kembali
            </a>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.people.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="first_name">Nama Depan</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" value="{{ old('first_name') }}" />
                        @error('first_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="last_name">Nama Belakang</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" value="{{ old('last_name') }}" />
                        @error('last_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="marga_id">Marga</label>
                    <div class="col-sm-10">
                        <select class="form-select @error('marga_id') is-invalid @enderror" id="marga_id" name="marga_id">
                            <option value="">Pilih Marga</option>
                            @foreach($margas as $marga)
                                <option value="{{ $marga->id }}" {{ old('marga_id') == $marga->id ? 'selected' : '' }}>
                                    {{ $marga->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('marga_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="gender">Jenis Kelamin</label>
                    <div class="col-sm-10">
                        <div class="form-check form-check-inline mt-2">
                            <input class="form-check-input @error('gender') is-invalid @enderror" type="radio" name="gender" id="male" value="male" {{ old('gender') == 'male' ? 'checked' : '' }}>
                            <label class="form-check-label" for="male">Laki-laki</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input @error('gender') is-invalid @enderror" type="radio" name="gender" id="female" value="female" {{ old('gender') == 'female' ? 'checked' : '' }}>
                            <label class="form-check-label" for="female">Perempuan</label>
                        </div>
                        @error('gender')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="birth_date">Tanggal Lahir</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control flatpickr-date @error('birth_date') is-invalid @enderror" id="birth_date" name="birth_date" value="{{ old('birth_date') }}" />
                        @error('birth_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="death_date">Tanggal Meninggal</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control flatpickr-date @error('death_date') is-invalid @enderror" id="death_date" name="death_date" value="{{ old('death_date') }}" />
                        <small class="text-muted">Kosongkan jika masih hidup</small>
                        @error('death_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="photo">Foto</label>
                    <div class="col-sm-10">
                        <input type="file" class="form-control @error('photo') is-invalid @enderror" id="photo" name="photo" />
                        <small class="text-muted">Format: JPG, PNG. Maks: 2MB</small>
                        @error('photo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="notes">Catatan</label>
                    <div class="col-sm-10">
                        <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row justify-content-end">
                    <div class="col-sm-10">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('admin/js/flatpickr.js') }}"></script>

<script>
    $(document).ready(function() {
        flatpickr('.flatpickr-date', {
            dateFormat: 'Y-m-d',
            allowInput: true
        });
    })
</script>
@endpush