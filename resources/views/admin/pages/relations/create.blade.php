@extends('admin.layouts.index')

@section('title', 'Tambah Hubungan Adat Baru - Scriptura')

@section('styles')

@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Tambah Hubungan Adat Baru</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.relations.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="user_id" class="form-label">Anggota Keluarga</label>
                    <select name="user_id" id="user_id" class="form-control" required>
                        <option value="">Pilih Anggota Keluarga</option>
                        @foreach ($familyMembers as $familyMember)
                            <option value="{{ $familyMember->id }}">{{ $familyMember->user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="relation_type_id" class="form-label">Jenis Hubungan</label>
                    <select name="relation_type_id" id="relation_type_id" class="form-control" required>
                        <option value="">Pilih Jenis Hubungan</option>
                        @foreach ($relationTypes as $relationType)
                            <option value="{{ $relationType->id }}">{{ $relationType->relation_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="relation_description" class="form-label">Deskripsi Hubungan</label>
                    <textarea class="form-control" name="relation_description" id="relation_description"></textarea>
                </div>
                <div class="tw-flex">
                    <button type="submit" class="btn btn-primary waves-effect waves-light">
                        Simpan
                    </button>
                    <a href="{{ route('admin.relations.index') }}">
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