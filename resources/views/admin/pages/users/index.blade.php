@extends('admin.layouts.index')

@section('title', 'Daftar User - Tarombo')

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
                    <h5 class="card-title mb-0">Daftar User</h5>
                </div>
                <div class="dt-action-buttons text-end pt-3 pt-md-0">
                    <div class="dt-buttons btn-group flex-wrap">
                        <div class="btn-group">
                            <a href="{{ route('admin.users.create') }}" class="btn btn-secondary create-new btn-primary waves-effect waves-light">
                                <span>
                                    <i class="ti ti-plus me-sm-1"></i>
                                    <span class="d-none d-sm-inline-block">
                                        Tambah User
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
                        <th>Email</th>
                        <th>Terhubung ke</th>
                        <th class="text-center" style="width: 10%;" data-sortable="false">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->person)
                            <span class="badge bg-success">
                                {{ $user->person->user->name }} ({{ $user->person->marga }})
                            </span>
                            @else
                            <span class="badge bg-secondary">Tidak terhubung</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="tw-flex gap-2 justify-content-center">
                                <a href="{{ route('admin.users.edit', $user) }}" 
                                   class="text-primary">
                                    <i class="ti ti-edit"></i>
                                </a>
                                <form action="{{ route('admin.users.destroy', $user) }}" 
                                      method="POST">
                                    @csrf @method('DELETE')
                                    <button type="button" 
                                            class="text-danger delete-record bg-transparent border-0">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </form>
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
                        url: '{{ route('admin.users.destroy', '') }}/' + id,
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
