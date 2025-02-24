@extends('admin.layouts.index')

@section('title', 'Ubah Manuscript - Scriptura')

@section('styles')
<link rel="stylesheet" href="{{ asset('admin/css/select2.css') }}">
<link rel="stylesheet" href="{{ asset('admin/css/sweetalert2.css') }}" />
<link rel="stylesheet" href="{{ asset('css/fancybox.css') }}" />
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    {{-- <h5 class="mb-0">Ubah Manuskrip</h5> --}}
    <h5 class="py-4 my-6">Ubah Manuskrip</h5>
    <div class="nav-tabs-shadow nav-align-top">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <button 
                    type="button" 
                    class="nav-link active" 
                    role="tab" 
                    data-bs-toggle="tab"
                    data-bs-target="#navs-top-general" 
                    aria-controls="navs-top-general" 
                    aria-selected="true">
                    General
                </button>
            </li>
            <li class="nav-item">
                <button 
                    type="button" 
                    class="nav-link" 
                    role="tab" 
                    data-bs-toggle="tab"
                    data-bs-target="#navs-top-viewer" 
                    aria-controls="navs-top-viewer" 
                    aria-selected="true">
                    Viewer
                </button>
            </li>
            <li class="nav-item">
                <button 
                    type="button" 
                    class="nav-link" 
                    role="tab" 
                    data-bs-toggle="tab"
                    data-bs-target="#navs-top-column" 
                    aria-controls="navs-top-column"
                    aria-selected="false">
                    Column
                </button>
            </li>
            <li class="nav-item">
                <button 
                    type="button" 
                    class="nav-link" 
                    role="tab" 
                    data-bs-toggle="tab"
                    data-bs-target="#navs-top-chapter-verse" 
                    aria-controls="navs-top-chapter-verse"
                    aria-selected="false">
                    Chapter & Verse
                </button>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="navs-top-general" role="tabpanel">
                <form method="POST" action="{{ route('admin.manuscript.update', $data->id) }}"
                    enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <div class="mb-3 col ecommerce-select2-dropdown">
                        <label class="form-label mb-1" for="parent_id">
                            <span>Status</span>
                        </label>
                        <select id="status" class="form-select" name="status">
                            <option value="1" {{ $data->status == 1 ? 'selected' : '' }}>AKTIF</option>
                            <option value="0" {{ $data->status == 0 ? 'selected' : '' }}>TIDAK AKTIF</option>
                        </select>
                    </div>
                    <div class="mb-3 col ecommerce-select2-dropdown">
                        <label class="form-label mb-1" for="category_id">
                            <span>Kategori</span>
                        </label>
                        <select id="category_id" class="select2 form-select" name="category_id"
                            data-placeholder="Pilih Kategori">
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $data->category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="title">Judul</label>
                        <input type="text" class="form-control" id="title" name="title" value="{{ $data->title }}"
                            placeholder="Judul" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="featured_image">Featured Image</label>
                        <div class="card mb-3">
                            <div class="card-body">
                                <a href="{{ $data->featuredImageOriginal }}" data-fancybox
                                    data-caption="{{ $data->title }}">
                                    <img src="{{ $data->featuredImageThumbnail }}" alt="{{ $data->title }}"
                                        style="width: 15%;" class="rounded" />
                                </a>
                            </div>
                        </div>
                        <input type="file" id="featured_image" name="featured_image" class="form-control" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="banner_image">Banner Image</label>
                        <div class="card mb-3">
                            <div class="card-body">
                                <a href="{{ $data->bannerImage }}" data-fancybox data-caption="{{ $data->title }}">
                                    <img src="{{ $data->bannerImage }}" alt="{{ $data->title }}" style="width: 15%;"
                                        class="rounded" />
                                </a>
                            </div>
                        </div>
                        <input type="file" id="banner_image" name="banner_image" class="form-control" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="excerpt">Deskripsi Singkat</label>
                        <textarea class="form-control" rows="3" id="excerpt" name="excerpt"
                            placeholder="Deskripsi Singkat">{{ $data->excerpt }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="content">Deskripsi</label>
                        <textarea class="form-control" rows="10" id="content" name="content"
                            placeholder="Deskripsi">{{ $data->content }}</textarea>
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
            <div class="tab-pane fade" id="navs-top-viewer" role="tabpanel">
                <form method="POST" action="{{ route('admin.manuscript.scroll.update', $data->scroll->id) }}">
                    @method('PUT')
                    @csrf
                    <div class="mb-3">
                        <div class="text-black small fw-medium mb-2">Show Rollers?</div>
                        <label class="switch">
                            <input 
                                type="checkbox" 
                                class="switch-input" 
                                name="show_rollers"
                                {{ $data->scroll->show_rollers == 1 ? 'checked' : '' }} 
                            />
                            <span class="switch-toggle-slider">
                                <span class="switch-on"></span>
                                <span class="switch-off"></span>
                            </span>
                            {{-- <span class="switch-label">Default</span> --}}
                        </label>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="scroll_code">Code</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="scroll_code"
                            name="scroll_code"
                            value="{{ $data->scroll->scroll_code }}" 
                            placeholder="Code"
                        />
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="width">Width</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="width"
                            name="width"
                            value="{{ $data->scroll->width }}" 
                            placeholder="Width"
                        />
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="height">Height</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="height"
                            name="height" 
                            value="{{ $data->scroll->height }}"
                            placeholder="Height"
                        />
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="legend_url">Legend URL</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="legend_url"
                            name="legend_url" 
                            value="{{ $data->scroll->legend_url }}"
                            placeholder="Legend URL"
                        />
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="legend_scale">Legend Scale</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="legend_scale"
                            name="legend_scale" 
                            value="{{ $data->scroll->legend_scale }}"
                            placeholder="Legend Scale"
                        />
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="tile_prefix">Tile Prefix</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="tile_prefix"
                            name="tile_prefix" 
                            value="{{ $data->scroll->tile_prefix }}"
                            placeholder="Tile Prefix"
                        />
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="black_pixel_url">Black Pixel URL</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="black_pixel_url"
                            name="black_pixel_url"
                            value="{{ $data->scroll->black_pixel_url }}" 
                            placeholder="Black Pixel URL"
                        />
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="tile_size">Tile Size</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="tile_size"
                            name="tile_size"
                            value="{{ $data->scroll->tile_size }}" 
                            placeholder="Tile Size"
                        />
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="max_zoom">Max Zoom</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="max_zoom"
                            name="max_zoom" 
                            value="{{ $data->scroll->max_zoom }}"
                            placeholder="Max Zoom"
                        />
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="min_zoom">Min Zoom</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="min_zoom"
                            name="min_zoom" 
                            value="{{ $data->scroll->min_zoom }}"
                            placeholder="Min Zoom"
                        />
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="full_size_w">Full Size Width</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="full_size_w"
                            name="full_size_w"
                            value="{{ $data->scroll->full_size_w }}" 
                            placeholder="Full Size Width"
                        />
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="full_size_h">Full Size Height</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="full_size_h"
                            name="full_size_h"
                            value="{{ $data->scroll->full_size_h }}" 
                            placeholder="Full Size Height"
                        />
                    </div>
                    <button type="submit" class="btn btn-primary waves-effect waves-light">
                        Simpan
                    </button>
                </form>
            </div>
            <div class="tab-pane fade" id="navs-top-column" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered" id="tableColumn"
                        style="width: 100%; font-size: 12px;">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center" style="width: 5%;">ID</th>
                                <th class="text-center">Width</th>
                                <th class="text-center">X Position</th>
                                <th class="text-center">Image URL</th>
                                <th class="text-center">Range</th>
                                <th class="text-center" style="width: 10%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data->scroll->columns as $column)
                            <tr>
                                <td class="text-center">{{ $column->column }}</td>
                                <td class="text-center">{{ $column->width }}</td>
                                <td class="text-center">{{ $column->x_position }}</td>
                                <td>{{ $column->image_url }}</td>
                                <td class="text-center">{{ $column->range }}</td>
                                <td class="text-center">
                                    <div class="tw-flex">
                                        <a href="javascript:void(0);" id="{{ $column->id }}" class="editColumn">
                                            <i class="text-primary ti ti-edit" style="font-size: 20px;"></i>
                                        </a>
                                        <a href="javascript:void(0);" id="{{ $column->id }}" class="deleteColumn">
                                            <i class="text-danger ti ti-trash" style="font-size: 20px;"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="tab-pane fade" id="navs-top-chapter-verse" role="tabpanel">
                <div class="accordion" id="accordionChapterVerse">
                    @foreach($data->scroll->chapters as $chapter)
                    <div class="card accordion-item">
                        <h2 class="accordion-header" id="heading{{ $chapter->id }}">
                            <button 
                                type="button" 
                                class="accordion-button collapsed" 
                                data-bs-toggle="collapse"
                                data-bs-target="#accordion{{ $chapter->id }}" 
                                aria-expanded="false" 
                                aria-controls="accordion{{ $chapter->id }}">
                                Chapter {{ $chapter->chapter }}
                            </button>
                        </h2>
                        <div id="accordion{{ $chapter->id }}" class="accordion-collapse collapse" data-bs-parent="#accordionChapterVerse" style="">
                            <div class="accordion-body">
                                <div class="d-flex flex-wrap gap-3">
                                    @foreach($chapter->verses as $verse)
                                    <a href="javascript:void()" class="editVerse" id="{{ $verse->id }}">
                                        <div class="border border-1 rounded-3 shadow p-2 text-center text-black" style="width: 50px;">
                                            {{ $verse->verse }}
                                        </div>
                                    </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<form method="POST" action="{{ route('admin.manuscript.image.store') }}" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="manuscript_id" value="{{ $data->id }}" />
    <div class="modal fade" id="modalAddManuscriptImage" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAddManuscriptImageTitle">Tambah Gambar Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="image" class="form-label">Gambar</label>
                            <input type="file" id="image" name="image" class="form-control" required />
                        </div>
                        <div class="col-12 mb-3">
                            <label for="title" class="form-label">Judul</label>
                            <input type="text" id="title" name="title" class="form-control" placeholder="Judul"
                                required />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="description">Deskripsi</label>
                            <textarea class="form-control" rows="10" id="description" name="description"
                                placeholder="Deskripsi"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</form>

<form id="formEditManuscriptImage">
    <input type="hidden" id="manuscriptImageId" />
    <div class="modal fade" id="modalEditManuscriptImage" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditManuscriptImageTitle">Ubah Gambar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="title" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="1">AKTIF</option>
                                <option value="0">TIDAK AKTIF</option>
                            </select>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="image" class="form-label">Gambar</label>
                            <div id="uploadedImageSection"></div>
                            <input type="file" id="image" name="image" class="form-control" />
                        </div>
                        <div class="col-12 mb-3">
                            <label for="title" class="form-label">Judul</label>
                            <input type="text" id="title" name="title" class="form-control" placeholder="Judul"
                                required />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="description">Deskripsi</label>
                            <textarea class="form-control" rows="10" id="editDescription" name="description"
                                placeholder="Deskripsi"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</form>

<form id="formColumn">
    <input type="hidden" id="manuscriptScrollColumnId" />
    <div class="modal fade" id="modalColumn" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalColumnTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-4">
                        <div class="col-6 mb-0">
                            <label for="width" class="form-label">Width</label>
                            <input 
                                type="text" 
                                id="width"
                                name="width"
                                class="form-control" 
                                placeholder="Width"
                                required 
                            />
                        </div>
                        <div class="col-6 mb-0">
                            <label for="x_position" class="form-label">X Position</label>
                            <input 
                                type="text" 
                                id="x_position"
                                name="x_position"
                                class="form-control" 
                                placeholder="X Position"
                                required 
                            />
                        </div>
                        <div class="col-6 mb-0">
                            <label for="image_url" class="form-label">Image</label>
                            <input 
                                type="file" 
                                id="image_url"
                                name="image_url"
                                class="form-control" 
                            />
                        </div>
                        <div class="col-6 mb-0">
                            <label for="range" class="form-label">Range</label>
                            <input 
                                type="text" 
                                id="range"
                                name="range"
                                class="form-control" 
                                placeholder="Range"
                                required 
                            />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</form>

<form id="formVerse">
    <input type="hidden" id="manuscriptScrollVerseId" />
    <div class="modal fade" id="modalVerse" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalVerseTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-4">
                            <label for="verse" class="form-label">Verse</label>
                            <input 
                                type="text" 
                                id="verse"
                                name="verse"
                                class="form-control" 
                                placeholder="Verse"
                                required 
                            />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-2">
                            <label for="shape_full_json" class="form-label">Shape</label>
                            <input 
                                type="text" 
                                id="shape_full_json"
                                name="shape_full_json"
                                class="form-control" 
                                placeholder="Shape" 
                            />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="divider text-start">
                                <div class="divider-text fw-bold">Text</div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-4">
                        <div class="col-6 mb-0">
                            <label for="text_en" class="form-label">English</label>
                            <textarea class="form-control" id="text_en" name="text_en" rows="4" placeholder="English"></textarea>
                        </div>
                        <div class="col-6 mb-0">
                            <label for="text_he" class="form-label">Hebrew</label>
                            <textarea class="form-control" id="text_he" name="text_he" rows="4" placeholder="Hebrew"></textarea>
                        </div>
                        <div class="col-6 mb-0">
                            <label for="text_id" class="form-label">Indonesia</label>
                            <textarea class="form-control" id="text_id" name="text_id" rows="4" placeholder="Indonesia"></textarea>
                        </div>
                        <div class="col-6 mb-0">
                            <label for="text_el" class="form-label">Greek</label>
                            <textarea class="form-control" id="text_el" name="text_el" rows="4" placeholder="Greek"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script src="{{ asset('admin/js/select2.js') }}"></script>
<script src="//cdn.ckeditor.com/4.22.1/full/ckeditor.js"></script>
<script src="{{ asset('admin/js/sweetalert2.js') }}"></script>
<script src="{{ asset('js/fancybox.umd.js') }}"></script>
<script src="{{ asset('js/axios.min.js') }}"></script>

<script>
    const modalEditManuscriptImage = new bootstrap.Modal(document.getElementById('modalEditManuscriptImage'))
    const modalEditManuscriptImageEl = document.getElementById('modalEditManuscriptImage')
    modalEditManuscriptImageEl.addEventListener('hidden.bs.modal', event => {
        $('#formEditManuscriptImage #manuscriptImageId').val('')
        $('#formEditManuscriptImage #title').val('')
        CKEDITOR.instances['editDescription'].setData('');
    })

    const modalVerse = new bootstrap.Modal(document.getElementById('modalVerse'))
    const modalColumn = new bootstrap.Modal(document.getElementById('modalColumn'))

    $(document).ready(function () {
        $(".select2.form-select").select2({
            placeholder: "Pilih Kategori"
        })

        Fancybox.bind("[data-fancybox]", {

        });

        CKEDITOR.replace('content');
        CKEDITOR.replace('description');
        CKEDITOR.replace('editDescription');

        $('#tableImages').on('click', '.edit-record', function () {
            const id = $(this).attr('id')

            const url = "{{ route('api.manuscript.image.show', '') }}/" + id
            axios.get(url)
                .then(res => {
                    const data = res.data.data
                    // return console.log(data)

                    if (data) {
                        $('#formEditManuscriptImage #manuscriptImageId').val(id)
                        $('#formEditManuscriptImage #title').val(data.title)
                        $('#formEditManuscriptImage #status').val(data.status)
                        CKEDITOR.instances['editDescription'].setData(data.description);

                        if (data.image) {
                            $('#formEditManuscriptImage #uploadedImageSection').html(`
                            <div class="card mb-3">
                                <div class="card-body">
                                    <a href="${data.imageUrl}" data-fancybox data-caption="${data.title}">
                                        <img 
                                            src="${data.thumbnailUrl}"
                                            alt="${data.title}"
                                            style="width: 15%;"
                                            class="rounded"
                                        />
                                    </a>
                                </div>
                            </div>
                        `)
                        }

                        modalEditManuscriptImage.show()
                    }
                })
        })

        $('#formEditManuscriptImage').on('submit', function (e) {
            e.preventDefault()

            const id = $('#formEditManuscriptImage #manuscriptImageId').val()

            // return console.log(id)

            const formData = new FormData()
            formData.append('_method', 'put');
            formData.append('status', $('#formEditManuscriptImage #status').val())
            formData.append('title', $('#formEditManuscriptImage #title').val())
            formData.append('description', CKEDITOR.instances['editDescription'].getData())

            const image = $('#formEditManuscriptImage #image').prop('files')
            if (image.length == 1) {
                formData.append('image', $('#formEditManuscriptImage #image').prop('files')[0])
            }

            const url = "{{ route('api.manuscript.image.update', '') }}/" + id

            axios({
                    method: 'post',
                    url: url,
                    data: formData,
                    header: {
                        'Content-Type': 'multipart/form-data',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(res => {
                    const msg = res.data.msg

                    modalEditManuscriptImage.hide()

                    Swal.fire({
                            title: "Sukses",
                            text: msg,
                            icon: "success",
                            customClass: {
                                confirmButton: "btn btn-primary waves-effect waves-light"
                            },
                            buttonsStyling: !1,
                            timer: 3500
                        })
                        .then(res => {
                            window.location.reload()
                        })
                })
        })

        $('#tableImages').on('click', '.delete-record', function () {
            const id = $(this).attr('id')

            Swal.fire({
                    title: "Apakah Anda yakin menghapus data ini?",
                    icon: "warning",
                    showCancelButton: !0,
                    confirmButtonText: "Ya, hapus!",
                    cancelButtonText: 'Batal',
                    customClass: {
                        confirmButton: "btn btn-primary me-3 waves-effect waves-light",
                        cancelButton: "btn btn-label-secondary waves-effect waves-light"
                    },
                    buttonsStyling: !1
                })
                .then(res => {
                    if (res.isConfirmed) {
                        axios({
                                method: 'delete',
                                url: '{{ route('api.manuscript.image.destroy', '') }}/' + id,
                                responseType: 'json'
                            })
                            .then(function (response) {
                                const data = response.data

                                Swal.fire({
                                        icon: "success",
                                        title: data.message,
                                        customClass: {
                                            confirmButton: "btn btn-success waves-effect waves-light"
                                        },
                                        timer: 3500
                                    })
                                    .then(res => {
                                        window.location.reload()
                                    })
                            });
                    }
                })
        })

        $('#accordionChapterVerse').on('click', '.editVerse', function () {
            const id = $(this).attr('id')

            // return console.log(id)

            const url = "{{ route('api.manuscriptScroll.verse.show', '') }}/" + id
            axios.get(url)
                .then(res => {
                    const data = res.data.data
                    console.log(data)

                    if (data) {
                        $('#formVerse #manuscriptScrollVerseId').val(data.id)
                        $('#formVerse #verse').val(data.verse)
                        $('#formVerse #shape_full_json').val(data.shape_full_json)
                        $('#formVerse #text_en').val(data.text_en)
                        $('#formVerse #text_he').val(data.text_he)
                        $('#formVerse #text_id').val(data.text_id)
                        $('#formVerse #text_el').val(data.text_el)

                        $('#modalVerseTitle').text(`Verse ${data.verse}`)
                        modalVerse.show()
                    }
                })
        })

        $('#formVerse').on('submit', function(e) {
            e.preventDefault();

            const id = $('#formVerse #manuscriptScrollVerseId').val()

            const url = "{{ route('api.manuscriptScroll.verse.update', '') }}/" + id

            axios({
                method: 'put',
                url: url,
                data: {
                    verse: $('#formVerse #verse').val(),
                    text_en: $('#formVerse #text_en').val(),
                    text_he: $('#formVerse #text_he').val(),
                    text_id: $('#formVerse #text_id').val(),
                    text_el: $('#formVerse #text_el').val()
                },
                header: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(res => {
                const msg = res.data.msg

                modalVerse.hide()

                Swal.fire({
                    title: "Sukses",
                    text: msg,
                    icon: "success",
                    customClass: {
                        confirmButton: "btn btn-primary waves-effect waves-light"
                    },
                    buttonsStyling: !1,
                    timer: 3500
                })
            })
        })

        $('#tableColumn').on('click', '.editColumn', function () {
            const id = $(this).attr('id')

            // return console.log(id)

            const url = "{{ route('api.manuscriptScroll.column.show', '') }}/" + id
            axios.get(url)
                .then(res => {
                    const data = res.data.data
                    // return console.log(data)

                    if (data) {
                        $('#formColumn #manuscriptScrollColumnId').val(data.id)
                        $('#formColumn #width').val(data.width)
                        $('#formColumn #x_position').val(data.x_position)
                        $('#formColumn #range').val(data.range)

                        $('#modalColumnTitle').text(`Column ${data.column}`)
                        modalColumn.show()
                    }
                })
        })

        $('#formColumn').on('submit', function(e) {
            e.preventDefault();

            const id = $('#formColumn #manuscriptScrollColumnId').val()

            const url = "{{ route('api.manuscriptScroll.column.update', '') }}/" + id

            axios({
                method: 'put',
                url: url,
                data: {
                    width: $('#formColumn #width').val(),
                    x_position: $('#formColumn #x_position').val(),
                    range: $('#formColumn #range').val(),
                },
                header: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(res => {
                const msg = res.data.msg

                modalColumn.hide()

                Swal.fire({
                    title: "Sukses",
                    text: msg,
                    icon: "success",
                    customClass: {
                        confirmButton: "btn btn-primary waves-effect waves-light"
                    },
                    buttonsStyling: !1,
                    timer: 3500
                })
            })
        })
    })
</script>
@endpush