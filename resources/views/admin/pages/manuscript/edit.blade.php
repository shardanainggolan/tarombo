@extends('admin.layouts.index')

@section('title', 'Ubah Manuscript - Scriptura')

@section('styles')
<link rel="stylesheet" href="{{ asset('admin/css/select2.css') }}">
<link rel="stylesheet" href="{{ asset('admin/css/sweetalert2.css') }}" />
<link rel="stylesheet" href="{{ asset('css/fancybox.css') }}" />
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Ubah Manuskrip</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.manuscript.update', $data->id) }}" enctype="multipart/form-data">
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
                    <select id="category_id" class="select2 form-select" name="category_id" data-placeholder="Pilih Kategori">
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $data->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
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
                        value="{{ $data->title }}"
                        placeholder="Judul" 
                        required
                    />
                </div>
                <div class="mb-3">
                    <label class="form-label" for="featured_image">Featured Image</label>
                    <div class="card mb-3">
                        <div class="card-body">
                            <a href="{{ $data->featuredImageOriginal }}" data-fancybox data-caption="{{ $data->title }}">
                                <img 
                                    src="{{ $data->featuredImageThumbnail }}"
                                    alt="{{ $data->title }}"
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
                    <label class="form-label" for="banner_image">Banner Image</label>
                    <div class="card mb-3">
                        <div class="card-body">
                            <a href="{{ $data->bannerImage }}" data-fancybox data-caption="{{ $data->title }}">
                                <img 
                                    src="{{ $data->bannerImage }}"
                                    alt="{{ $data->title }}"
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
                    <label class="form-label" for="excerpt">Deskripsi Singkat</label>
                    <textarea class="form-control" rows="3" id="excerpt" name="excerpt" placeholder="Deskripsi Singkat">{{ $data->excerpt }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="content">Deskripsi</label>
                    <textarea class="form-control" rows="10" id="content" name="content" placeholder="Deskripsi">{{ $data->content }}</textarea>
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

            <hr class="mb-5" />

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Images</h5>
                    <button type="button" class="btn btn-success waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#modalAddManuscriptImage">
                        Tambah
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered" id="tableImages" style="width: 100%;">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-center" style="width: 20%;">Image</th>
                                    <th>Judul</th>
                                    <th>Deskripsi</th>
                                    <th class="text-center" style="width: 5%;">Status</th>
                                    <th class="text-center" style="width: 10%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data->images as $image)
                                <tr>
                                    <td class="text-center">
                                        <a href="{{ $image->imageUrl }}" data-fancybox="{{ $data->title }}" data-caption="{{ $image->title }}">
                                            <img 
                                                src="{{ $image->thumbnailUrl }}"
                                                alt="{{ $image->title }}"
                                                class="rounded"
                                                style="width: 50%;"
                                            />
                                        </a>
                                    </td>
                                    <td>{{ $image->title }}</td>
                                    <td>{!! $image->description !!}</td>
                                    <td class="text-center">
                                        @if($image->status == 1)
                                        <span class="badge  bg-label-success">AKTIF</span>
                                        @else
                                        <span class="badge  bg-label-danger">TIDAK AKTIF</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="tw-flex">
                                            <a href="javascript:void(0);"  id="{{ $image->id }}" class="edit-record">
                                                <i class="text-primary ti ti-edit" style="font-size: 20px;"></i>
                                            </a>
                                            <a href="javascript:void(0);" id="{{ $image->id }}" class="delete-record">
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
                            <input 
                                type="file" 
                                id="image" 
                                name="image" 
                                class="form-control" 
                                required
                            />
                        </div>
                        <div class="col-12 mb-3">
                            <label for="title" class="form-label">Judul</label>
                            <input 
                                type="text" 
                                id="title" 
                                name="title"
                                class="form-control" 
                                placeholder="Judul"
                                required
                            />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="description">Deskripsi</label>
                            <textarea class="form-control" rows="10" id="description" name="description" placeholder="Deskripsi"></textarea>
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
                            <input 
                                type="file" 
                                id="image" 
                                name="image" 
                                class="form-control" 
                            />
                        </div>
                        <div class="col-12 mb-3">
                            <label for="title" class="form-label">Judul</label>
                            <input 
                                type="text" 
                                id="title" 
                                name="title"
                                class="form-control" 
                                placeholder="Judul"
                                required
                            />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="description">Deskripsi</label>
                            <textarea class="form-control" rows="10" id="editDescription" name="description" placeholder="Deskripsi"></textarea>
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

    $(document).ready(function() {
        $(".select2.form-select").select2({
            placeholder: "Pilih Kategori"
        })

        Fancybox.bind("[data-fancybox]", {
            
        });

        CKEDITOR.replace('content');
        CKEDITOR.replace('description');
        CKEDITOR.replace('editDescription');

        $('#tableImages').on('click', '.edit-record', function() {
            const id = $(this).attr('id')

            const url = "{{ route('api.manuscript.image.show', '') }}/" + id
            axios.get(url)
            .then(res => {
                const data = res.data.data
                // return console.log(data)

                if(data) {
                    $('#formEditManuscriptImage #manuscriptImageId').val(id)
                    $('#formEditManuscriptImage #title').val(data.title)
                    $('#formEditManuscriptImage #status').val(data.status)
                    CKEDITOR.instances['editDescription'].setData(data.description);

                    if(data.image) {
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

        $('#formEditManuscriptImage').on('submit', function(e) {
            e.preventDefault()
            
            const id = $('#formEditManuscriptImage #manuscriptImageId').val()

            // return console.log(id)

            const formData = new FormData()
            formData.append('_method', 'put');
            formData.append('status', $('#formEditManuscriptImage #status').val())
            formData.append('title', $('#formEditManuscriptImage #title').val())
            formData.append('description', CKEDITOR.instances['editDescription'].getData())

            const image = $('#formEditManuscriptImage #image').prop('files')
            if(image.length == 1) {
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

        $('#tableImages').on('click', '.delete-record', function() {
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
                if(res.isConfirmed) {
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
    })
</script>
@endpush