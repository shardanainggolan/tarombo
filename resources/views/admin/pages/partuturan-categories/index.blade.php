@extends('admin.layouts.index')
@section('title', 'Kategori Partuturan - Tarombo')
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
                    <h5 class="card-title mb-0">Kategori Partuturan</h5>
                </div>
                <div class="dt-action-buttons text-end pt-3 pt-md-0">
                    <div class="dt-buttons btn-group flex-wrap">
                        <div class="btn-group">
                            <a href="{{ route('admin.partuturan-categories.create') }}" class="btn btn-secondary create-new btn-primary waves-effect waves-light">
                                <span>
                                    <i class="ti ti-plus me-sm-1"></i>
                                    <span class="d-none d-sm-inline-block">
                                        Tambah Kategori
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
                        <th>Nama Kategori</th>
                        <th>Deskripsi</th>
                        <th>Jumlah Istilah</th>
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
            ajax: "{{ route('admin.partuturan-categories.index') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'name', name: 'name'},
                {data: 'description', name: 'description'},
                {data: 'terms_count', name: 'terms_count'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
            order: [[1, 'asc']]
        });
        
        $('.datatables').on('click', '.delete-record', function() {
            const id = $(this).attr('id')
            Swal.fire({
                title: "Apakah Anda yakin menghapus kategori ini?",
                text: "Data yang dihapus tidak dapat dikembalikan",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: 'Batal',
                customClass: {
                    confirmButton: "btn btn-primary me-3 waves-effect waves-light",
                    cancelButton: "btn btn-label-secondary waves-effect waves-light"
                },
                buttonsStyling: false
            })
            .then(res => {
                if(res.isConfirmed) {
                    axios({
                        method: 'delete',
                        url: '{{ route('admin.partuturan-categories.destroy', '') }}/' + id,
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