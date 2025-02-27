@extends('admin.layouts.index')

@section('title', 'Tambah Anggota Keluarga - Scriptura')

@section('styles')

@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Tambah Anggota Keluarga</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.people.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Pengguna</label>
                            <select name="user_id" class="form-select" required>
                                <option value="">Pilih Pengguna</option>
                                @foreach($users as $user)
                                <option value="{{ $user->id }}" 
                                    {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Jenis Kelamin</label>
                            <select name="gender" class="form-select" required>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>
                                    Laki-laki
                                </option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>
                                    Perempuan
                                </option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Ayah</label>
                            <select name="father_id" class="form-select" id="fatherSelect">
                                <option value="">Pilih Ayah</option>
                                @foreach($fathers as $father)
                                <option value="{{ $father->id }}" 
                                    data-marga="{{ $father->marga }}"
                                    {{ old('father_id') == $father->id ? 'selected' : '' }}>
                                    {{ $father->user->name }} ({{ $father->marga }})
                                </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Ibu</label>
                            <select name="mother_id" class="form-select">
                                <option value="">Pilih Ibu</option>
                                @foreach($mothers as $mother)
                                <option value="{{ $mother->id }}" 
                                    {{ old('mother_id') == $mother->id ? 'selected' : '' }}>
                                    {{ $mother->user->name }} ({{ $mother->marga }})
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Tanggal Lahir</label>
                            <input type="date" name="birth_date" 
                                   class="form-control" 
                                   value="{{ old('birth_date') }}" 
                                   required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Marga</label>
                            <input type="text" class="form-control" 
                                   id="margaDisplay" 
                                   value="{{ old('father_id') ? 
                                       $fathers->firstWhere('id', old('father_id'))?->marga : 
                                       'Nainggolan' }}" 
                                   disabled>
                            <input type="hidden" name="marga" 
                                   id="margaHidden" 
                                   value="{{ old('father_id') ? 
                                       $fathers->firstWhere('id', old('father_id'))?->marga : 
                                       'Nainggolan' }}">
                        </div>
                    </div>
                </div>
                <div class="tw-flex">
                    <button type="submit" class="btn btn-primary waves-effect waves-light">
                        Simpan
                    </button>
                    <a href="{{ route('admin.people.index') }}">
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
        $('#fatherSelect').change(function() {
            const selected = $(this).find('option:selected');
            const marga = selected.data('marga') || 'Nainggolan';
            
            $('#margaDisplay').val(marga);
            $('#margaHidden').val(marga);
        });
    })
</script>
@endpush