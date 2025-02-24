@extends('admin.layouts.index')

@section('title', 'Ubah Page - Yayasan Scriptura Indonesia')

@section('styles')

@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Ubah Page</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.page.update', $data->id) }}" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <div class="mb-3">
                    <label class="form-label" for="status">Status</label>
                    <select class="form-select" id="status" name="status" required>
                        <option value="1" {{ $data->status == 1 ? 'selected' : '' }}>PUBLISHED</option>
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
                <div class="mb-3">
                    <label class="form-label" for="content">Konten</label>
                    <textarea class="form-control" rows="10" id="content" name="content" placeholder="Konten">{{ $data->content }}</textarea>
                </div>
                <div class="tw-flex">
                    <button type="submit" class="btn btn-primary waves-effect waves-light">
                        Simpan
                    </button>
                    <a href="{{ route('admin.page.index') }}">
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
        CKEDITOR.replace('content');
    })
</script>
@endpush