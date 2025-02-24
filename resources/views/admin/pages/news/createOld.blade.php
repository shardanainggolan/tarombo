@extends('admin.layouts.index')

@section('title', 'Tambah Berita - Scriptura')

@section('styles')

@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Tambah Berita</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.news.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label" for="name">Judul</label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="title" 
                        name="title"
                        placeholder="Judul" 
                        required
                    />
                </div>
                <div class="mb-3">
                    <label class="form-label" for="image">Gambar</label>
                    <input 
                        type="file" 
                        id="image" 
                        name="image"
                        class="form-control"
                        required
                    />
                </div>
                <div class="mb-3">
                    <label class="form-label" for="excerpt">Deskripsi Singkat</label>
                    <textarea class="form-control" rows="3" id="excerpt" name="excerpt" placeholder="Deskripsi Singkat"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="content">Konten</label>
                    <x-c-k-editor class="form-control" name="content" id="editor" />
                    {{-- <textarea class="form-control" rows="10" id="content" name="content" placeholder="Konten"></textarea> --}}
                </div>
                <div class="tw-flex">
                    <button type="submit" class="btn btn-primary waves-effect waves-light">
                        Simpan
                    </button>
                    <a href="{{ route('admin.news.index') }}">
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
    window.Laravel = @json(['uploadUrl' => route('admin.ckeditor.upload')]);

    $(document).ready(function() {
    
    })
</script>
@endpush