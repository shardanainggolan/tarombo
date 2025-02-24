@extends('admin.layouts.index')

@section('title', 'Tambah Manuscript - Scriptura')

@section('styles')
<link rel="stylesheet" href="{{ asset('admin/css/select2.css') }}">
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Tambah Manuscript</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.manuscript.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-3 col ecommerce-select2-dropdown">
                    <label class="form-label mb-1" for="category_id">
                        <span>Kategori</span>
                    </label>
                    <select id="category_id" class="select2 form-select" name="category_id" data-placeholder="Pilih Kategori">
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="title">Judul</label>
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
                    <label class="form-label" for="featured_image">Featured Image</label>
                    <input 
                        type="file" 
                        id="featured_image" 
                        name="featured_image"
                        class="form-control"
                    />
                </div>
                <div class="mb-3">
                    <label class="form-label" for="banner_image">Banner Image</label>
                    <input 
                        type="file" 
                        id="banner_image" 
                        name="banner_image"
                        class="form-control"
                    />
                </div>
                <div class="mb-3">
                    <label class="form-label" for="excerpt">Deskripsi Singkat</label>
                    <textarea class="form-control" rows="3" id="excerpt" name="excerpt" placeholder="Deskripsi Singkat"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="content">Deskripsi</label>
                    <textarea class="form-control" rows="10" id="content" name="content" placeholder="Deskripsi"></textarea>
                </div>
                <div class="tw-flex">
                    <button type="submit" class="btn btn-primary waves-effect waves-light">
                        Simpan
                    </button>
                    <a href="{{ route('admin.manuscript.index') }}">
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

<script>
    $(document).ready(function() {
        $(".select2.form-select").select2({
            placeholder: "Pilih Kategori"
        })

        CKEDITOR.replace('content');
    })
</script>
@endpush