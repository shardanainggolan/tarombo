@extends('admin.layouts.index')

@section('title', 'Tambah Marga - Scriptura')

@section('styles')

@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Tambah Marga</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.individual.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="first_name">Nama Depan</label>
                    <input type="text" name="first_name" id="first_name" class="form-control" required />
                </div>
                <div class="mb-3">
                    <label for="last_name">Nama Belakang</label>
                    <input type="text" name="last_name" id="last_name" class="form-control" required />
                </div>
                <div class="mb-3">
                    <label for="clan_id">Marga</label>
                    <select name="clan_id" id="clan_id" class="form-control" required>
                        @foreach ($clans as $clan)
                            <option value="{{ $clan->id }}">{{ $clan->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="gender">Jenis Kelamin</label>
                    <select name="gender" id="gender" class="form-control" required>
                        <option value="male">Laki-laki</option>
                        <option value="female">Perempuan</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="birth_date">Tanggal Lahir</label>
                    <input type="date" name="birth_date" id="birth_date" class="form-control" />
                </div>
                <div class="mb-3">
                    <label for="is_alive">Status Hidup</label>
                    <select name="is_alive" id="is_alive" class="form-control">
                        <option value="1">Hidup</option>
                        <option value="0">Meninggal</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="marriage_id">Pernikahan Orang Tua (opsional)</label>
                    <select name="marriage_id" id="marriage_id" class="form-control">
                        <option value="">Pilih Pernikahan</option>
                        @foreach ($marriages as $marriage)
                            <option value="{{ $marriage->id }}">{{ $marriage->husband->first_name }} & {{ $marriage->wife->first_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="tw-flex">
                    <button type="submit" class="btn btn-primary waves-effect waves-light">
                        Simpan
                    </button>
                    <a href="{{ route('admin.clans.index') }}">
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