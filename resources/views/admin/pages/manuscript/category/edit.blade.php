@extends('admin.layouts.index')

@section('title', 'Ubah Kategori Manuscript - Scriptura')

@section('styles')
<link rel="stylesheet" href="{{ asset('admin/css/select2.css') }}">
<link rel="stylesheet" href="{{ asset('css/fancybox.css') }}" />
<link rel="stylesheet" href="{{ asset('admin/css/sweetalert2.css') }}" />
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Ubah Kategori Manuscript</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.manuscript.category.update', $data->id) }}" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <div class="mb-3 col ecommerce-select2-dropdown">
                    <label class="form-label mb-1" for="parent_id">
                        <span>Status</span>
                    </label>
                    <select id="status" class="form-select" name="status">
                        <option value="1" {{ $data->status == 1 ? 'selected' : '' }}>PUBLISH</option>
                        <option value="0" {{ $data->status == 0 ? 'selected' : '' }}>TIDAK AKTIF</option>
                    </select>
                </div>
                <div class="mb-3 col ecommerce-select2-dropdown">
                    <label class="form-label mb-1" for="is_show_front">
                        <span>Tampilkan di halaman home?</span>
                    </label>
                    <div>
                        <label class="switch">
                            <input 
                                type="checkbox" 
                                class="switch-input" 
                                id="is_show_front" 
                                name="is_show_front" 
                                {{ $data->is_show_front == 1 ? 'checked' : '' }}
                            />
                            <span class="switch-toggle-slider">
                                <span class="switch-on"></span>
                                <span class="switch-off"></span>
                            </span>
                            {{-- <span class="switch-label">Default</span> --}}
                        </label>
                    </div>
                </div>
                <div class="mb-3 col ecommerce-select2-dropdown">
                    <label class="form-label mb-1" for="parent_id">
                        <span>Parent</span>
                    </label>
                    <select id="parent_id" class="select2 form-select" name="parent_id" data-placeholder="Pilih Kategori">
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $data->parent_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
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
                    <label class="form-label" for="year">Tahun</label>
                    <input 
                        type="number" 
                        class="form-control" 
                        id="year" 
                        name="year"
                        value="{{ $data->year }}"
                        placeholder="Tahun" 
                    />
                </div>
                <div class="mb-3">
                    <label class="form-label" for="image">Banner</label>
                    <div class="card mb-3">
                        <div class="card-body">
                            <a href="{{ $data->bannerImage }}" data-fancybox data-caption="{{ $data->name }}">
                                <img 
                                    src="{{ $data->bannerImage }}"
                                    alt="{{ $data->name }}"
                                    style="width: 15%;"
                                    class="rounded"
                                />
                            </a>
                        </div>
                    </div>
                    <input 
                        type="file" 
                        id="banner_image" 
                        name="banner_image"
                        class="form-control"
                    />
                </div>
                <div class="mb-3">
                    <label class="form-label" for="featured_image">Featured Image</label>
                    <div class="card mb-3">
                        <div class="card-body">
                            <a href="{{ $data->featuredImageOriginal }}" data-fancybox data-caption="{{ $data->name }}">
                                <img 
                                    src="{{ $data->featuredImageThumbnail }}"
                                    alt="{{ $data->name }}"
                                    style="width: 15%;"
                                    class="rounded"
                                />
                            </a>
                        </div>
                    </div>
                    <input 
                        type="file" 
                        id="featured_image" 
                        name="featured_image"
                        class="form-control"
                    />
                </div>
                <div class="mb-3">
                    <label class="form-label" for="excerpt">Deskripsi Singkat</label>
                    <textarea class="form-control" rows="3" id="excerpt" name="excerpt" placeholder="Deskripsi Singkat">{{ $data->excerpt }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="description">Deskripsi</label>
                    <textarea class="form-control" rows="10" id="description" name="description" placeholder="Deskripsi">{{ $data->excerpt }}</textarea>
                </div>
                <div class="tw-flex">
                    <button type="submit" class="btn btn-primary waves-effect waves-light">
                        Simpan
                    </button>
                    <a href="{{ route('admin.manuscript.category.index') }}">
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
<script src="{{ asset('admin/js/select2.js') }}"></script>
<script src="//cdn.ckeditor.com/4.22.1/full/ckeditor.js"></script>
<script src="{{ asset('js/fancybox.umd.js') }}"></script>
<script src="{{ asset('admin/js/sweetalert2.js') }}"></script>

<script>
    $(document).ready(function() {
        Fancybox.bind("[data-fancybox]", {
            
        });

        $(".select2.form-select").select2({
            placeholder: "Pilih Kategori"
        })

        CKEDITOR.replace('description');
    })
</script>
@endpush