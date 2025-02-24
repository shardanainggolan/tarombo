@extends('admin.layouts.index')

@section('title', 'Ubah Koleksi Produk - Scriptura')

@section('styles')

@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Ubah Koleksi Produk</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.product.collection.update', $data->id) }}">
                @method('PUT')
                @csrf
                <div class="mb-3">
                    <label class="form-label" for="name">Status</label>
                    <select class="form-control" id="status" name="status" required>
                        <option value="1" {{ $data->status == 1 ? 'selected' : '' }}>AKTIF</option>
                        <option value="0" {{ $data->status == 0 ? 'selected' : '' }}>TIDAK AKTIF</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="title">Nama Koleksi</label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="title" 
                        name="title"
                        value="{{ $data->title }}"
                        placeholder="Nama Koleksi" 
                        required
                    />
                </div>
                <div class="mb-3">
                    <label class="form-label" for="short_desc">Deskripsi Singkat</label>
                    <textarea class="form-control" rows="3" id="short_desc" name="short_desc" placeholder="Deskripsi Singkat">{{ $data->short_desc }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="description">Deskripsi</label>
                    <textarea class="form-control" rows="10" id="description" name="description" placeholder="Deskripsi">{{ $data->description }}</textarea>
                </div>
                <div class="tw-flex">
                    <button type="submit" class="btn btn-primary waves-effect waves-light">
                        Simpan
                    </button>
                    <a href="{{ route('admin.product.collection.index') }}">
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
<script src="//cdn.ckeditor.com/4.22.1/full/ckeditor.js"></script>

<script>
    $(document).ready(function() {
        CKEDITOR.replace('description');
    })
</script>
@endpush