@extends('admin.layouts.index')

@section('title', 'Tambah Berita - Scriptura')

@section('styles')
<link rel="stylesheet" href="{{ asset('vendors/flatpickr/flatpickr.min.css') }}" />
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <form method="POST" action="{{ route('admin.news.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="card mb-4">
            <div class="sticky-wrapper" class="sticky-wrapper">
                <div class="card-header sticky-element bg-label-secondary d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row">
                    <h5 class="card-title mb-sm-0 me-2">
                        Tambah Berita
                    </h5>
                    <div class="action-btns">
                        <a href="{{ route('admin.news.index') }}">
                            <button type="button" class="btn btn-label-primary me-4">
                                <span class="align-middle">
                                    Kembali
                                </span>
                            </button>
                        </a>
                        <button type="submit" class="btn btn-primary waves-effect waves-light">
                            Simpan
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-8">
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
                                <label class="form-label" for="content">Konten</label>
                                <x-c-k-editor class="form-control" name="content" id="editor" />
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="card sticky-top z-0" style="top: 160px;">
                                <div class="card-body">
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
                                        <label class="form-label" for="status">Status</label>
                                        <select class="form-select" name="status" id="status">
                                            <option value="">Pilih Status</option>
                                            <option value="1">Published</option>
                                            <option value="0">Draft</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="status">Tanggal Publish</label>
                                        <input 
                                            type="text" 
                                            class="form-control flatpickr-input active" 
                                            placeholder="Tanggal Publish" 
                                            name="publish_date"
                                            id="flatpickr-datetime" 
                                            readonly="readonly"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="{{ asset('admin/js/jquery-sticky.js') }}"></script>
<script src="{{ asset('admin/js/form-layouts.js') }}"></script>
<script src="{{ asset('vendors/flatpickr/flatpickr.js') }}"></script>

<script>
    window.Laravel = @json(['uploadUrl' => route('admin.ckeditor.upload')]);

    $(document).ready(function() {
        flatpickr("#flatpickr-datetime", {
            enableTime: true,
            time_24hr: true,
            dateFormat: "d-m-Y H:i",
            altFormat: "Y-m-d H:i"
        });
    })
</script>
@endpush