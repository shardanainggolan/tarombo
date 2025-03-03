@extends('admin.layouts.index')

@section('title', 'Edit Orang - Tarombo')

@section('styles')
<link rel="stylesheet" href="{{ asset('admin/css/flatpickr.css') }}" />
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Edit Orang</h5>
            <a href="{{ route('admin.people.index') }}" class="btn btn-secondary">
                <i class="ti ti-arrow-left me-sm-1"></i> Kembali
            </a>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.people.update', $person->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="first_name">Nama Depan</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" value="{{ old('first_name', $person->first_name) }}" />
                        @error('first_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="last_name">Nama Belakang</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" value="{{ old('last_name', $person->last_name) }}" />
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
                                <option value="{{ $marga->id }}" {{ old('marga_id', $person->marga_id) == $marga->id ? 'selected' : '' }}>
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
                            <input class="form-check-input @error('gender') is-invalid @enderror" type="radio" name="gender" id="male" value="male" {{ old('gender', $person->gender) == 'male' ? 'checked' : '' }}>
                            <label class="form-check-label" for="male">Laki-laki</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input @error('gender') is-invalid @enderror" type="radio" name="gender" id="female" value="female" {{ old('gender', $person->gender) == 'female' ? 'checked' : '' }}>
                            <label class="form-check-label" for="female">Perempuan</label>
                        </div>
                        @error('gender')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        
                        @if($person->children->count() > 0 || $person->marriagesAsHusband->count() > 0 || $person->marriagesAsWife->count() > 0)
                            <div class="alert alert-warning mt-2">
                                <div class="d-flex">
                                    <i class="ti ti-alert-triangle me-2"></i>
                                    <div>
                                        Perubahan jenis kelamin dapat mempengaruhi struktur relasi keluarga (pernikahan dan anak).
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="birth_date">Tanggal Lahir</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control flatpickr-date @error('birth_date') is-invalid @enderror" id="birth_date" name="birth_date" value="{{ old('birth_date', $person->birth_date ? $person->birth_date->format('Y-m-d') : '') }}" />
                        @error('birth_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="death_date">Tanggal Meninggal</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control flatpickr-date @error('death_date') is-invalid @enderror" id="death_date" name="death_date" value="{{ old('death_date', $person->death_date ? $person->death_date->format('Y-m-d') : '') }}" />
                        <small class="text-muted">Kosongkan jika masih hidup</small>
                        @error('death_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="photo">Foto</label>
                    <div class="col-sm-10">
                        @if($person->photo_url)
                            <div class="mb-3">
                                <img src="{{ Storage::url($person->photo_url) }}" alt="{{ $person->first_name }}" style="max-width: 200px; max-height: 200px; object-fit: cover;" class="border rounded">
                            </div>
                        @endif
                        <input type="file" class="form-control @error('photo') is-invalid @enderror" id="photo" name="photo" />
                        <small class="text-muted">Format: JPG, PNG. Maks: 2MB. Kosongkan jika tidak ingin mengubah foto.</small>
                        @error('photo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="notes">Catatan</label>
                    <div class="col-sm-10">
                        <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes', $person->notes) }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row justify-content-end">
                    <div class="col-sm-10">
                        <button type="submit" class="btn btn-primary">Perbarui</button>
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