@extends('admin.layouts.index')

@section('title', 'Daftar Anggota Keluarga - Tarombo')

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
                    <h5 class="card-title mb-0">Daftar Anggota Keluarga</h5>
                </div>
                <div class="dt-action-buttons text-end pt-3 pt-md-0">
                    <div class="dt-buttons btn-group flex-wrap">
                        <div class="btn-group">
                            <a href="{{ route('admin.people.create') }}" class="btn btn-secondary create-new btn-primary waves-effect waves-light">
                                <span>
                                    <i class="ti ti-plus me-sm-1"></i>
                                    <span class="d-none d-sm-inline-block">
                                        Tambah Anggota Keluarga
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
                        <th>Nama</th>
                        <th>Marga</th>
                        <th>Ayah</th>
                        <th>Ibu</th>
                        <th>Pasangan</th>
                        <th class="text-center" style="width: 10%;" data-sortable="false">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($persons as $person)
                    <tr>
                        <td>{{ $person->user->name }}</td>
                        <td>
                            <span class="badge bg-primary">{{ $person->marga }}</span>
                            @if($person->is_boru_line)
                            <span class="badge bg-danger">Boru</span>
                            @endif
                        </td>
                        <td>{{ $person->father?->user->name ?? '-' }}</td>
                        <td>{{ $person->mother?->user->name ?? '-' }}</td>
                        <td>{{ $person->current_spouse?->user->name ?? '-' }}</td>
                        <td class="text-center">
                            <div class="tw-flex">
                                <a href="{{ route('admin.people.edit', $person->id) }}">
                                    <i class="text-primary ti ti-edit" style="font-size: 20px;"></i>
                                </a>
                                <a href="javascript:void(0);" id="{{ $person->id }}" class="delete-record">
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
                        url: '{{ route('admin.people.destroy', '') }}/' + id,
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
