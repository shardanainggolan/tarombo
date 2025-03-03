@extends('admin.layouts.index')
@section('title', 'Daftar Pernikahan - Tarombo')
@section('styles')
<link rel="stylesheet" href="{{ asset('admin/css/datatables.bootstrap5.css') }}" />
<link rel="stylesheet" href="{{ asset('admin/css/responsive.bootstrap5.css') }}" />
<link rel="stylesheet" href="{{ asset('admin/css/buttons.bootstrap5.css') }}" />
<link rel="stylesheet" href="{{ asset('admin/css/sweetalert2.css') }}" />
@endsection
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-datatable table-responsive pt-0">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="head-label">
                    <h5 class="card-title mb-0">Daftar Pernikahan</h5>
                </div>
                <div class="dt-action-buttons text-end pt-3 pt-md-0">
                    <div class="dt-buttons btn-group flex-wrap">
                        <div class="btn-group">
                            <a href="{{ route('admin.marriages.create') }}" class="btn btn-secondary create-new btn-primary waves-effect waves-light">
                                <span>
                                    <i class="ti ti-plus me-sm-1"></i>
                                    <span class="d-none d-sm-inline-block">
                                        Tambah Pernikahan
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
                        <th>No</th>
                        <th>Suami</th>
                        <th>Istri</th>
                        <th>Tanggal Pernikahan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
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
<script>
    $(document).ready(function () {
        $('.datatables').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.marriages.index') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'husband_name', name: 'husband_name'},
                {data: 'wife_name', name: 'wife_name'},
                {data: 'marriage_date', name: 'marriage_date'},
                {data: 'status', name: 'status'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
            dom: '<"d-flex justify-content-between align-items-center header-actions mx-2 row mt-75"' +
                '<"col-sm-12 col-lg-4 d-flex justify-content-center justify-content-lg-start" l>' +
                '<"col-sm-12 col-lg-8 ps-xl-75 ps-0"<"dt-action-buttons d-flex align-items-center justify-content-center justify-content-lg-end flex-lg-nowrap flex-wrap"<"me-1"f>B>>' +
                '>t' +
                '<"d-flex justify-content-between mx-2 row mb-1"' +
                '<"col-sm-12 col-md-6"i>' +
                '<"col-sm-12 col-md-6"p>' +
                '>',
            buttons: [
                {
                    extend: 'collection',
                    className: 'btn btn-label-secondary dropdown-toggle me-2',
                    text: '<i class="ti ti-download me-sm-1"></i> <span class="d-none d-sm-inline-block">Export</span>',
                    buttons: [
                        {
                            extend: 'excel',
                            text: '<i class="ti ti-file-spreadsheet me-1"></i>Excel',
                            className: 'dropdown-item',
                            exportOptions: { columns: [0, 1, 2, 3, 4] }
                        },
                        {
                            extend: 'pdf',
                            text: '<i class="ti ti-file-description me-1"></i>PDF',
                            className: 'dropdown-item',
                            exportOptions: { columns: [0, 1, 2, 3, 4] }
                        },
                        {
                            extend: 'print',
                            text: '<i class="ti ti-printer me-1"></i>Print',
                            className: 'dropdown-item',
                            exportOptions: { columns: [0, 1, 2, 3, 4] }
                        }
                    ]
                }
            ],
            order: []
        });
        
        $('.datatables').on('click', '.delete-record', function() {
            const id = $(this).attr('id')
            Swal.fire({
                title: "Apakah Anda yakin menghapus data ini?",
                text: "Data yang dihapus tidak dapat dikembalikan",
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
                        url: '{{ route('admin.marriages.destroy', '') }}/' + id,
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
                            timer: 3000
                        })
                        .then(res => {
                            window.location.reload()
                        })
                    })
                    .catch(function (error) {
                        let errorMsg = 'Terjadi kesalahan sistem';
                        if (error.response && error.response.data && error.response.data.message) {
                            errorMsg = error.response.data.message;
                        }
                        
                        Swal.fire({
                            icon: "error",
                            title: "Gagal Menghapus",
                            text: errorMsg,
                            customClass: {
                                confirmButton: "btn btn-danger waves-effect waves-light"
                            }
                        });
                    });
                }
            })
        })
    })
</script>
@endpush