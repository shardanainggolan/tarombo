@extends('admin.layouts.index')

@section('title', 'Tambah Produk - Yayasan Scriptura Indonesia')

@section('styles')
<link rel="stylesheet" href="{{ asset('admin/css/select2.css') }}">
<link rel="stylesheet" href="{{ asset('admin/css/dropzone.css') }}">
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <form method="POST" action="{{ route('admin.product.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="app-ecommerce">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
                <div class="d-flex flex-column justify-content-center">
                    <h4 class="mb-1 mt-3">Tambah Produk Baru</h4>
                    {{-- <div class="form-text">
                        Kolom bertanda ( <span class="text-danger">*</span> ) wajib diisi.
                    </div> --}}
                    
                </div>
                <div class="d-flex align-content-center flex-wrap gap-3">
                    <div class="d-flex gap-3">
                        <a href="{{ route('admin.product.index') }}">
                            <button type="button" class="btn btn-label-danger">Batal</button>
                        </a>
                    </div>
                    <button type="submit" class="btn btn-primary">Publish Produk</button>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="alert alert-info" role="alert">
                        Kolom bertanda ( <span class="text-danger">*</span> ) wajib diisi.
                    </div>
                </div>
            </div>
    
            <div class="row">
                <div class="col-12 col-lg-8">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-tile mb-0">Informasi Produk</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label" for="name">
                                    Nama
                                    <span class="text-danger">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    id="name"
                                    placeholder="Nama Produk" 
                                    name="name"
                                    aria-label="Nama Produk"
                                    required
                                />
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="author">
                                    Pengarang / Penerjemah
                                </label>
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    id="author"
                                    name="author"
                                    placeholder="Pengarang / Penerjemah" 
                                    aria-label="Pengarang / Penerjemah"
                                />
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label class="form-label" for="isbn">ISBN</label>
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        id="isbn"
                                        placeholder="ISBN" 
                                        name="isbn" 
                                        aria-label="ISBN"
                                    />
                                </div>
                                <div class="col">
                                    <label class="form-label" for="year">Tahun</label>
                                    <input 
                                        type="text" 
                                        class="form-control"
                                        id="year" 
                                        placeholder="Tahun"
                                        name="year" 
                                        aria-label="Tahun"
                                    />
                                </div>
                                <div class="col">
                                    <label class="form-label" for="total_page">Total Halaman</label>
                                    <input 
                                        type="number" 
                                        class="form-control"
                                        id="total_page" 
                                        placeholder="Total Halaman"
                                        name="total_page" 
                                        aria-label="Total Halaman"
                                    />
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="link_shopee">
                                    Link Shopee
                                </label>
                                <input 
                                    type="text" 
                                    class="form-control"
                                    id="link_shopee" 
                                    placeholder="Link Shopee"
                                    name="link_shopee" 
                                    aria-label="Link Shopee"
                                />
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="link_bpk">
                                    Link BPK
                                </label>
                                <input 
                                    type="text" 
                                    class="form-control"
                                    id="link_bpk" 
                                    placeholder="Link BPK"
                                    name="link_bpk" 
                                    aria-label="Link BPK"
                                />
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="short_desc">
                                    Deskripsi Singkat
                                </label>
                                <textarea class="form-control" rows="3" id="short_desc" name="short_desc" placeholder="Deskripsi Singkat"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="editor">
                                    Deskripsi
                                </label>
                                <x-c-k-editor class="form-control" name="description" id="editor" />
                                {{-- <textarea class="form-control" id="editor" name="description" placeholder="Deskripsi"></textarea> --}}
                            </div>
                            {{-- <div
                                class="editor-container editor-container_classic-editor editor-container_include-style editor-container_include-block-toolbar"
                                id="editor-container"
                            >
                                <div class="editor-container__editor"><div id="editor"></div></div>
                            </div> --}}
                            {{-- <div>
                                <label class="form-label">
                                    Deskripsi
                                </label>
                                <textarea class="form-control" rows="10" id="description" name="description" placeholder="Deskripsi"></textarea>
                            </div> --}}
                        </div>
                    </div>
                    {{-- <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 card-title">Media</h5>
                            <a href="javascript:void(0);" class="fw-medium">Add media from URL</a>
                        </div>
                        <div class="card-body">
                            <div class="dropzone" id="file-dropzone"></div>
                        </div>
                    </div> --}}
                </div>
                <div class="col-12 col-lg-4">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Pengaturan</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3 col ecommerce-select2-dropdown">
                                <label class="form-label mb-1" for="featured_image">
                                    Foto Utama
                                </label>
                                <input 
                                    type="file"
                                    class="form-control"
                                    id="featured_image"
                                    name="featured_image"
                                />
                            </div>
                            <div class="mb-3 col ecommerce-select2-dropdown">
                                <label class="form-label mb-1" for="category_id">
                                    Kategori
                                </label>
                                <select id="category_id" class="select2 form-select" name="category_id[]" multiple>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3 col ecommerce-select2-dropdown">
                                <label class="form-label mb-1" for="status-org">Koleksi</label>
                                <select id="collection_id" name="collection_id" class="select2 form-select" data-placeholder="Pilih Koleksi">
                                    <option value=""></option>
                                    @foreach($collections as $collection)
                                    <option value="{{ $collection->id }}">{{ $collection->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Harga</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label" for="price">
                                    Harga Produk
                                </label>
                                <input 
                                    type="number" 
                                    class="form-control" 
                                    id="price"
                                    placeholder="Harga Produk" 
                                    name="price" 
                                    aria-label="Harga Produk"
                                />
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="stock">Stok</label>
                                <input 
                                    type="number" 
                                    class="form-control"
                                    id="stock" 
                                    placeholder="Stok"
                                    name="stock" 
                                    aria-label="Stok"
                                />
                            </div>
                            <div class="d-flex justify-content-between align-items-center border-top pt-3">
                                <h6 class="mb-0">In stock</h6>
                                <div class="w-25 d-flex justify-content-end">
                                    <label class="switch switch-primary switch-sm me-4 pe-2">
                                        <input type="checkbox" name="in_stock" class="switch-input" checked="" />
                                        <span class="switch-toggle-slider">
                                            <span class="switch-on">
                                                <span class="switch-off"></span>
                                            </span>
                                        </span>
                                    </label>
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
<script src="{{ asset('admin/js/select2.js') }}"></script>
<script src="{{ asset('admin/js/dropzone.js') }}"></script>

<script>
    window.Laravel = @json(['uploadUrl' => route('admin.ckeditor.upload')]);

    $(document).ready(function() {
        $(".select2.form-select").select2({
            placeholder: "Pilih Kategori"
        })
    })
</script>
@endpush