@extends('admin.layouts.index')

@section('title', 'Ubah Berita - Scriptura')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/fancybox.css') }}" />
<link rel="stylesheet" href="{{ asset('admin/css/sweetalert2.css') }}" />
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Ubah Berita</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.news.update', $data->id) }}" enctype="multipart/form-data">
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
                    <label class="form-label" for="title">Judul</label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="title" 
                        name="title"
                        value="{{ $data->title }}"
                        placeholder="Judul" 
                        required
                    />
                </div>
                <div class="mb-3">
                    <label class="form-label" for="image">Gambar</label>
                    <div class="card mb-3">
                        <div class="card-body">
                            <a href="{{ $data->originalImage }}" data-fancybox data-caption="{{ $data->title }}">
                                <img 
                                    src="{{ $data->thumbnail }}"
                                    alt="{{ $data->title }}"
                                    style="width: 15%;"
                                    class="rounded"
                                />
                            </a>
                        </div>
                    </div>
                    <input 
                        type="file" 
                        id="image" 
                        name="image"
                        class="form-control"
                    />
                </div>
                <div class="mb-3">
                    <label class="form-label" for="excerpt">Deskripsi Singkat</label>
                    <textarea class="form-control" rows="3" id="excerpt" name="excerpt" placeholder="Deskripsi Singkat">{{ $data->excerpt }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="content">Konten</label>
                    <x-c-k-editor class="form-control" name="content" id="editor" />
                    {{-- <textarea class="form-control" rows="10" id="content" name="content" placeholder="Konten">{{ $data->content }}</textarea> --}}
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
<script src="{{ asset('js/fancybox.umd.js') }}"></script>
<script src="{{ asset('admin/js/sweetalert2.js') }}"></script>

<script>
    window.Laravel = @json([
        'uploadUrl' => route('admin.ckeditor.upload'),
        'descriptionValue'  => $data->content
    ]);

    $(document).ready(function() {
        Fancybox.bind("[data-fancybox]", {
            
        });
    })
</script>
@endpush