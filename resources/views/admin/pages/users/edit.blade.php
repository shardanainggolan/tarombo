@extends('admin.layouts.index')

@section('title', 'Edit Pengguna - Tarombo')

@section('styles')
<link rel="stylesheet" href="{{ asset('admin/css/select2.css') }}" />
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Edit Pengguna</h5>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                <i class="ti ti-arrow-left me-sm-1"></i> Kembali
            </a>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Password (kosongkan jika tidak diubah)</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   name="password">
                            <small class="text-muted">Biarkan kosong jika tidak ingin mengubah password.</small>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Konfirmasi Password</label>
                            <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                                   name="password_confirmation">
                            @error('password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label">Hubungkan ke Data Orang</label>
                            <select name="person_id" class="select2 form-select @error('person_id') is-invalid @enderror">
                                <option value="">Pilih Orang (Opsional)</option>
                                @foreach($availablePeople as $person)
                                <option value="{{ $person->id }}" {{ old('person_id', $user->person_id) == $person->id ? 'selected' : '' }}>
                                    {{ $person->first_name }} {{ $person->last_name }} ({{ $person->marga->name }})
                                </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Hubungkan pengguna dengan data orang yang ada di sistem.</small>
                            @error('person_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary waves-effect waves-light">
                            <i class="ti ti-save me-1"></i> Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary waves-effect waves-light">
                            <i class="ti ti-x me-1"></i> Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('admin/js/select2.js') }}"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Pilih Orang (Opsional)",
            allowClear: true
        });
    });
</script>
@endpush