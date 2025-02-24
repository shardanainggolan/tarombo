@extends('admin.layouts.index')

@section('title', 'Ubah Berita - Scriptura')


@section('styles')
<link rel="stylesheet" href="{{ asset('css/fancybox.css') }}" />
<link rel="stylesheet" href="{{ asset('admin/css/sweetalert2.css') }}" />
<link rel="stylesheet" href="{{ asset('vendors/flatpickr/flatpickr.min.css') }}" />
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <form method="POST" action="{{ route('admin.news.update', $data->id) }}" enctype="multipart/form-data">
        @method('PUT')
        @csrf
        <div class="card mb-4">
            <div class="sticky-wrapper" class="sticky-wrapper">
                <div class="card-header sticky-element bg-label-secondary d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row">
                    <h5 class="card-title mb-sm-0 me-2">
                        Ubah Berita
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
                                    value="{{ $data->title }}"
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
                                        <div class="card mb-3"">
                                            <div class="card-body">
                                                <a href="{{ $data->originalImage }}" data-fancybox data-caption="{{ $data->title }}">
                                                    <img 
                                                        src="{{ $data->thumbnail }}"
                                                        alt="{{ $data->title }}"
                                                        style="width: 100%; height: 10%;"
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
                                        <label class="form-label" for="status">Status</label>
                                        <select class="form-select" name="status" id="status">
                                            <option value="">Pilih Status</option>
                                            <option value="1" {{ $data->status == 1 ? 'selected' : '' }}>Published</option>
                                            <option value="0" {{ $data->status == 0 ? 'selected' : '' }}>Draft</option>
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
                                            value="{{ $data->publish_date }}"
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
<script src="{{ asset('js/fancybox.umd.js') }}"></script>
<script src="{{ asset('admin/js/sweetalert2.js') }}"></script>
<script src="{{ asset('admin/js/jquery-sticky.js') }}"></script>
<script src="{{ asset('admin/js/form-layouts.js') }}"></script>
<script src="{{ asset('vendors/flatpickr/flatpickr.js') }}"></script>

<script>
    window.Laravel = @json([
        'uploadUrl' => route('admin.ckeditor.upload'),
        'descriptionValue'  => $data->content
    ]);

    $(document).ready(function() {
        Fancybox.bind("[data-fancybox]", {
            
        });

        flatpickr("#flatpickr-datetime", {
            enableTime: true,
            time_24hr: true,
            altInput: true,
            altFormat: "d-m-Y H:i",
            dateFormat: "Y-m-d H:i"
        });
    })
</script>
@endpush