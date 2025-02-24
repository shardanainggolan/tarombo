@extends('admin.layouts.index')

@section('title', 'Ubah Kategori Galeri - Scriptura')

@section('styles')
<link rel="stylesheet" href="{{ asset('admin/css/sweetalert2.css') }}" />
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Ubah Kategori Galeri</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.gallery.category.update', $data->id) }}">
                @method('PUT')
                @csrf
                <div class="mb-3">
                    <label class="form-label" for="status">Status</label>
                    <select class="form-control" id="status" name="status" required>
                        <option value="1" {{ $data->status == 1 ? 'selected' : '' }}>AKTIF</option>
                        <option value="0" {{ $data->status == 0 ? 'selected' : '' }}>TIDAK AKTIF</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="name">Nama</label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="name" 
                        name="name"
                        value="{{ $data->name }}"
                        placeholder="Nama" 
                        required
                    />
                </div>
                <div class="tw-flex">
                    <button type="submit" class="btn btn-primary waves-effect waves-light">
                        Simpan
                    </button>
                    <a href="{{ route('admin.gallery.category.index') }}">
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
<script src="{{ asset('admin/js/sweetalert2.js') }}"></script>

<script>
    $(document).ready(function() {

    })
</script>
@endpush