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
            <form method="POST" action="{{ route('admin.family_members.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="user_id" class="form-label">Nama Pengguna</label>
                    <select name="user_id" id="user_id" class="form-control" required>
                        <option value="">Pilih Pengguna</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="marga" class="form-label">Marga</label>
                    <input type="text" class="form-control" name="marga" id="marga" required>
                </div>
                <div class="mb-3">
                    <label for="father_id" class="form-label">Ayah</label>
                    <select name="father_id" id="father_id" class="form-control">
                        <option value="">Pilih Ayah (Opsional)</option>
                        @foreach ($familyMembers as $familyMember)
                            <option value="{{ $familyMember->id }}">{{ $familyMember->user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="mother_id" class="form-label">Ibu</label>
                    <select name="mother_id" id="mother_id" class="form-control">
                        <option value="">Pilih Ibu (Opsional)</option>
                        @foreach ($familyMembers as $familyMember)
                            <option value="{{ $familyMember->id }}">{{ $familyMember->user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="gender" class="form-label">Jenis Kelamin</label>
                    <select name="gender" id="gender" class="form-control" required>
                        <option value="male">Laki-laki</option>
                        <option value="female">Perempuan</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="birth_date" class="form-label">Tanggal Lahir</label>
                    <input type="date" class="form-control" name="birth_date" id="birth_date" required>
                </div>
                <div class="tw-flex">
                    <button type="submit" class="btn btn-primary waves-effect waves-light">
                        Simpan
                    </button>
                    <a href="{{ route('admin.users.index') }}">
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