@extends('admin.layouts.index')

@section('title', 'Daftar Produk - Yayasan Scriptura Indonesia')

@section('styles')
<link rel="stylesheet" href="{{ asset('admin/css/datatables.bootstrap5.css') }}" />
<link rel="stylesheet" href="{{ asset('admin/css/responsive.bootstrap5.css') }}" />
<link rel="stylesheet" href="{{ asset('admin/css/buttons.bootstrap5.css') }}" />
<link rel="stylesheet" href="{{ asset('admin/css/sweetalert2.css') }}" />
<link rel="stylesheet" href="{{ asset('css/fancybox.css') }}" />
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-datatable table-responsive pt-0">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="head-label">
                    <h5 class="card-title mb-0">Daftar Produk</h5>
                </div>
                <div class="dt-action-buttons text-end pt-3 pt-md-0">
                    <div class="dt-buttons btn-group flex-wrap">
                        <div class="btn-group">
                            <a href="{{ route('admin.product.create') }}" class="btn btn-secondary create-new btn-primary waves-effect waves-light">
                                <span>
                                    <i class="ti ti-plus me-sm-1"></i>
                                    <span class="d-none d-sm-inline-block">
                                        Tambah Produk
                                    </span>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <table class="datatables table table-sm" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th class="text-center" style="width: 20%;">Kategori</th>
                        {{-- <th class="text-center" style="width: 20%;">Koleksi</th> --}}
                        <th class="text-center" style="width: 20%;">Status</th>
                        <th class="text-center" style="width: 10%;" data-sortable="false">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($datas as $data)
                    <tr>
                        <td>
                            <div class="d-flex justify-content-start align-items-center product-name">
                                <div class="avatar-wrapper">
                                    <div class="avatar avatar me-2 rounded-2 bg-label-secondary">
                                        @if($data->featured_image)
                                        <a href="{{ $data->originalImage }}" data-fancybox="product" data-caption="{{ $data->name }}">
                                            <img 
                                                src="{{ $data->thumbnail }}" 
                                                alt="{{ $data->name }}" 
                                                class="rounded-2" 
                                            />
                                        </a>
                                        @else
                                        <a href="{{ asset('images/product-placeholder.webp') }}" data-fancybox="product" data-caption="{{ $data->name }}">
                                            <img 
                                                src="{{ asset('images/product-placeholder.webp') }}" 
                                                alt="{{ $data->name }}" 
                                                class="rounded-2" 
                                            />
                                        </a>
                                        @endif
                                    </div>
                                </div>
                                <div class="d-flex flex-column">
                                    <h6 class="text-body text-nowrap mb-0">{{ $data->name }}</h6>
                                </div>
                            </div>    
                        </td>
                        <td class="text-center">
                            @if(count($data->categories) > 0)
                                @foreach($data->categories as $category)
                                <span class="badge bg-primary">{{ $category->name }}</span>
                                @endforeach
                            @endif
                        </td>
                        {{-- <td class="text-center">
                            @if($data->collection)
                            <span class="badge bg-success">{{ $data->collection->title }}</span>
                            @endif
                        </td> --}}
                        <td class="text-center">
                            @if($data->productStatus)
                            <span class="badge  bg-label-success">{{ $data->productStatus->name }}</span>
                            @endif
                            
                            {{-- @if($data->status == 1)
                            <span class="badge  bg-label-success">AKTIF</span>
                            @else
                            <span class="badge  bg-label-danger">TIDAK AKTIF</span>
                            @endif --}}
                        </td>
                        <td class="text-center">
                            <div class="tw-flex">
                                <a href="{{ route('admin.product.edit', $data->id) }}">
                                    <i class="text-primary ti ti-edit" style="font-size: 20px;"></i>
                                </a>
                                <a href="javascript:void(0);" id="{{ $data->id }}" class="delete-record">
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
@endsection

@push('scripts')
<script src="{{ asset('admin/js/datatables-bootstrap5.js') }}"></script>
<script src="{{ asset('admin/js/sweetalert2.js') }}"></script>
<script src="{{ asset('js/axios.min.js') }}"></script>
<script src="{{ asset('js/fancybox.umd.js') }}"></script>

<script>
    $(document).ready(function () {
        $('.datatables').DataTable({
            order: []
        });

        Fancybox.bind("[data-fancybox]", {
            
        });

        $('.datatables').on('click', '.delete-record', function() {
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
                        url: '{{ route('admin.product.destroy', '') }}/' + id,
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
