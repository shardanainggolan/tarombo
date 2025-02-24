@extends('admin.layouts.index')

@section('title', 'Ubah Produk - Yayasan Scriptura Indonesia')

@section('styles')
<link rel="stylesheet" href="{{ asset('admin/css/select2.css') }}">
<link rel="stylesheet" href="{{ asset('admin/css/dropzone.css') }}">
<link rel="stylesheet" href="{{ asset('css/fancybox.css') }}" />
<link rel="stylesheet" href="{{ asset('admin/css/sweetalert2.css') }}" />
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <form method="POST" action="{{ route('admin.product.update', $data->id) }}" enctype="multipart/form-data">
        @method('PUT')
        @csrf
        <div class="app-ecommerce">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
                <div class="d-flex flex-column justify-content-center">
                    <h4 class="mb-1 mt-3">Tambah Produk Baru</h4>
                </div>
                <div class="d-flex align-content-center flex-wrap gap-3">
                    <div class="d-flex gap-3">
                        <a href="{{ route('admin.product.index') }}">
                            <button type="button" class="btn btn-label-danger">Batal</button>
                        </a>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
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
                                    name="name"
                                    value="{{ $data->name }}"
                                    aria-label="Nama Produk"
                                    placeholder="Nama Produk" 
                                    required
                                />
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="author">
                                    Pengarang / Penerjemah
                                </label>
                                <select id="author" name="author[]" class="select2 form-select" data-placeholder="Pilih Pengarang / Penerjemah" multiple>
                                    <option value=""></option>
                                    @foreach($authors as  $author)
                                    <option value="{{ $author->id }}">{{ $author->name }}</option>
                                    @endforeach
                                </select>
                                {{-- <input 
                                    type="text" 
                                    class="form-control" 
                                    id="author"
                                    name="author"
                                    value="{{ $data->author }}"
                                    placeholder="Pengarang / Penerjemah" 
                                    aria-label="Pengarang / Penerjemah"
                                /> --}}
                            </div>
                            <div class="card my-3">
                                <h5 class="card-header">Format yang tersedia</h5>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md mb-md-0 mb-5">
                                            <div class="form-check custom-option custom-option-icon checked">
                                                <label class="form-check-label custom-option-content" for="is_available_print">
                                                    <span class="custom-option-body">
                                                        <img 
                                                            src="{{ asset('images/icons/printed-book.png') }}"
                                                            class="mb-2"
                                                            style="width: 40px; height: 40px;"
                                                        />
                                                        <span class="custom-option-title">Buku Cetak</span>
                                                    </span>
                                                    <input 
                                                        class="form-check-input" 
                                                        type="checkbox" 
                                                        id="is_available_print" 
                                                        name="is_available_print" 
                                                        {{ $data->is_available_print == 1 ? 'checked' : '' }}
                                                    />
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md mb-md-0 mb-5">
                                            <div class="form-check custom-option custom-option-icon checked">
                                                <label class="form-check-label custom-option-content" for="is_available_ebook">
                                                    <span class="custom-option-body">
                                                        <img 
                                                            src="{{ asset('images/icons/ebook.png') }}"
                                                            class="mb-2"
                                                            style="width: 40px; height: 40px;"
                                                        />
                                                        <span class="custom-option-title">eBook</span>
                                                    </span>
                                                    <input 
                                                        class="form-check-input" 
                                                        type="checkbox" 
                                                        id="is_available_ebook"
                                                        name="is_available_ebook" 
                                                        {{ $data->is_available_ebook == 1 ? 'checked' : '' }}
                                                    />
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label class="form-label" for="isbn">ISBN</label>
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        id="isbn"
                                        name="isbn" 
                                        value="{{ $data->isbn }}"
                                        placeholder="ISBN" 
                                        aria-label="ISBN"
                                    />
                                </div>
                                <div class="col">
                                    <label class="form-label" for="year">Tahun</label>
                                    <input 
                                        type="text" 
                                        class="form-control"
                                        id="year" 
                                        name="year" 
                                        value="{{ $data->year }}"
                                        placeholder="Tahun"
                                        aria-label="Tahun"
                                    />
                                </div>
                                <div class="col">
                                    <label class="form-label" for="total_page">Total Halaman</label>
                                    <input 
                                        type="number" 
                                        class="form-control"
                                        id="total_page" 
                                        name="total_page" 
                                        value="{{ $data->total_page }}"
                                        placeholder="Total Halaman"
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
                                    value="{{ $data->link_shopee }}"
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
                                    value="{{ $data->link_bpk }}" 
                                    aria-label="Link BPK"
                                />
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="short_desc">
                                    Deskripsi Singkat
                                    <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control" rows="3" id="short_desc" name="short_desc" placeholder="Deskripsi Singkat">{{ $data->short_desc }}</textarea>
                            </div>
                            <div>
                                <label class="form-label">
                                    Deskripsi
                                </label>
                                <x-c-k-editor class="form-control" name="description" id="editor" :value="$data->description" />
                                {{-- <textarea class="form-control" rows="10" id="description" name="description" placeholder="Deskripsi">{{ $data->description }}</textarea> --}}
                            </div>
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
                                <label class="form-label mb-1" for="category_id">
                                    Foto Utama
                                </label>
                                <div class="card mt-2 mb-3">
                                    <div class="card-body p-2 text-center">
                                        @if($data->featured_image)
                                            <a href="{{ $data->originalImage }}" data-fancybox="" data-caption="{{ $data->name }}">
                                                <img 
                                                    src="{{ $data->thumbnail }}"
                                                    class="img-responsive"
                                                    style="width: 70%;"
                                                    alt="{{ $data->name }}"
                                                />
                                            </a>
                                        @else
                                        <a href="{{ asset('images/product-placeholder.webp') }}" data-fancybox="" data-caption="{{ $data->name }}">
                                            <img 
                                                src="{{ asset('images/product-placeholder.webp') }}"
                                                class="img-responsive"
                                                style="width: 70%;"
                                                alt="{{ $data->name }}"
                                            />
                                        </a>
                                        @endif
                                    </div>
                                </div>
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
                                <label class="form-label mb-1" for="collection_id">Koleksi</label>
                                <select id="collection_id" name="collection_id" class="select2 form-select" data-placeholder="Pilih Koleksi">
                                    <option value=""></option>
                                    @foreach($collections as $collection)
                                    <option value="{{ $collection->id }}" {{ $data->collection_id == $collection->id ? 'selected' : '' }}>{{ $collection->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3 col ecommerce-select2-dropdown">
                                <label class="form-label mb-1" for="product_status_id">
                                    Status
                                    <span class="text-danger">*</span>
                                </label>
                                <select id="product_status_id" class="select2 form-select" name="product_status_id" data-placeholder="Pilih Status" required>
                                    <option value=""></option>
                                    @foreach($productStatus as $status)
                                    <option value="{{ $status->id }}" {{ $data->product_status_id == $status->id ? 'selected' : '' }}>{{ $status->name }}</option>
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
                                    name="price" 
                                    value="{{ $data->price }}"
                                    placeholder="Harga Produk" 
                                    aria-label="Harga Produk"
                                />
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="stock">Stok</label>
                                <input 
                                    type="number" 
                                    class="form-control"
                                    id="stock" 
                                    name="stock" 
                                    value="{{ $data->stock }}"
                                    placeholder="Stok"
                                    aria-label="Stok"
                                />
                            </div>
                            <div class="d-flex justify-content-between align-items-center border-top pt-3">
                                <h6 class="mb-0">In stock</h6>
                                <div class="w-25 d-flex justify-content-end">
                                    <label class="switch switch-primary switch-sm me-4 pe-2">
                                        <input type="checkbox" name="in_stock" class="switch-input" {{ $data->in_stock == 1 ? 'checked' : '' }} />
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
<script src="{{ asset('js/fancybox.umd.js') }}"></script>
<script src="{{ asset('admin/js/sweetalert2.js') }}"></script>

<script>
    window.Laravel = @json([
        'uploadUrl' => route('admin.ckeditor.upload'),
        'descriptionValue'  => $data->description
    ]);

    $(document).ready(function() {
        var selectedAuthors = @json($selectedAuthors);
        var selectedCategories = @json($selectedCategories);

        $(".select2.form-select").select2()

        $('#author').val(selectedAuthors).trigger('change');
        $('#category_id').val(selectedCategories).trigger('change');

        Fancybox.bind("[data-fancybox]", {
            
        });
    })
</script>
@endpush