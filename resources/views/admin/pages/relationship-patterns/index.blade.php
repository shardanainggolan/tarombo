@extends('admin.layouts.index')
@section('title', 'Pola Hubungan - Tarombo')
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
                    <h5 class="card-title mb-0">Pola Hubungan</h5>
                </div>
                <div class="dt-action-buttons text-end pt-3 pt-md-0">
                    <div class="dt-buttons btn-group flex-wrap">
                        <div class="btn-group">
                            <a href="{{ route('admin.relationship-patterns.create') }}" class="btn btn-secondary create-new btn-primary waves-effect waves-light">
                                <span>
                                    <i class="ti ti-plus me-sm-1"></i>
                                    <span class="d-none d-sm-inline-block">
                                        Tambah Pola
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
                        <th>Pola</th>
                        <th>Deskripsi</th>
                        <th>Jumlah Aturan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0">Panduan Penulisan Pola</h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <p>Pola hubungan ditulis dengan format jalur relasi yang dipisahkan tanda titik (<code>.</code>).</p>
                <p>Contoh: <code>father.brother</code> berarti "saudara laki-laki dari ayah" (paman).</p>
            </div>
            
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Jenis</th>
                            <th>Segmen Pola</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td rowspan="4" class="align-middle">
                                <span class="badge bg-primary">Laki-laki</span>
                            </td>
                            <td><code>father</code></td>
                            <td>Ayah</td>
                        </tr>
                        <tr>
                            <td><code>son</code></td>
                            <td>Anak laki-laki</td>
                        </tr>
                        <tr>
                            <td><code>brother</code></td>
                            <td>Saudara laki-laki</td>
                        </tr>
                        <tr>
                            <td><code>husband</code></td>
                            <td>Suami</td>
                        </tr>
                        <tr>
                            <td rowspan="4" class="align-middle">
                                <span class="badge bg-danger">Perempuan</span>
                            </td>
                            <td><code>mother</code></td>
                            <td>Ibu</td>
                        </tr>
                        <tr>
                            <td><code>daughter</code></td>
                            <td>Anak perempuan</td>
                        </tr>
                        <tr>
                            <td><code>sister</code></td>
                            <td>Saudara perempuan</td>
                        </tr>
                        <tr>
                            <td><code>wife</code></td>
                            <td>Istri</td>
                        </tr>
                        <tr>
                            <td rowspan="4" class="align-middle">
                                <span class="badge bg-warning">Netral</span>
                            </td>
                            <td><code>child</code></td>
                            <td>Anak (laki/perempuan)</td>
                        </tr>
                        <tr>
                            <td><code>parent</code></td>
                            <td>Orang tua (ayah/ibu)</td>
                        </tr>
                        <tr>
                            <td><code>sibling</code></td>
                            <td>Saudara (laki/perempuan)</td>
                        </tr>
                        <tr>
                            <td><code>spouse</code></td>
                            <td>Pasangan (suami/istri)</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                <h6>Contoh Pola Kompleks:</h6>
                <ul>
                    <li><code>father.sister.husband</code> - Suami dari saudara perempuan ayah (amangboru)</li>
                    <li><code>mother.brother</code> - Saudara laki-laki ibu (tulang)</li>
                    <li><code>father.father.brother.son</code> - Anak laki-laki dari saudara laki-laki kakek (dari ayah)</li>
                </ul>
            </div>
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
            ajax: "{{ route('admin.relationship-patterns.index') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'formatted_pattern', name: 'pattern'},
                {data: 'description', name: 'description'},
                {data: 'rules_count', name: 'rules_count'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
            order: [[1, 'asc']]
        });
        
        $('.datatables').on('click', '.delete-record', function() {
            const id = $(this).attr('id')
            Swal.fire({
                title: "Apakah Anda yakin menghapus pola ini?",
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
                        url: '{{ route('admin.relationship-patterns.destroy', '') }}/' + id,
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