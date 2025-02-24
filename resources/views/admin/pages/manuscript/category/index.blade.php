@extends('admin.layouts.index')

@section('title', 'Daftar Kategori Manuskrip - Elite Plafon')

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
                    <h5 class="card-title mb-0">Daftar Kategori Manuskrip</h5>
                </div>
                <div class="dt-action-buttons text-end pt-3 pt-md-0">
                    <div class="dt-buttons btn-group flex-wrap">
                        <div class="btn-group">
                            <a href="{{ route('admin.manuscript.category.create') }}" class="btn btn-secondary create-new btn-primary waves-effect waves-light">
                                <span>
                                    <i class="ti ti-plus me-sm-1"></i>
                                    <span class="d-none d-sm-inline-block">
                                        Tambah Kategori Manuskrip
                                    </span>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <ul class="list-group list-group-flush nested-sortable" id="category-list">
                @foreach($datas as $data)
                    @include('admin.partials.category', ['category' => $data])
                {{-- <li
                    class="list-group-item d-flex justify-content-between align-items-center">
                    <span class="d-flex justify-content-between align-items-center">
                        <i
                            class="drag-handle cursor-move ti ti-menu-2 align-text-bottom me-2"></i>
                        <span>Buy products</span>
                    </span>
                    <img class="rounded-circle" src="../../assets/img/avatars/1.png"
                        alt="avatar" height="32" width="32" />
                </li> --}}
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('admin/js/datatables-bootstrap5.js') }}"></script>
<script src="{{ asset('admin/js/sweetalert2.js') }}"></script>
<script src="{{ asset('admin/js/Sortable.min.js') }}"></script>
<script src="{{ asset('js/axios.min.js') }}"></script>
<script src="{{ asset('js/fancybox.umd.js') }}"></script>

<script>
    $(document).ready(function () {
        $('.datatables').DataTable({
            order: []
        });

        Fancybox.bind("[data-fancybox]", {
            
        });

        const buildSortable = (element) => {
            new Sortable(element, {
                group: {
                    name: 'nested',
                    pull: true,
                    put: true
                },
                animation: 150,
                fallbackOnBody: true,
                swapThreshold: 0.65,
                onEnd: function (evt) {
                    let order = [];
                    const buildOrder = function (list, parent) {
                        Array.from(list.children).forEach((item, index) => {
                            order.push({
                                id: item.dataset.id,
                                position: index,
                                parent_id: parent
                            });
                            let nestedList = item.querySelector('ul');
                            if (nestedList) {
                                buildOrder(nestedList, item.dataset.id);
                            }
                        });
                    };
                    buildOrder(document.getElementById('category-list'), null);
                    // fetch('{{ route('api.manuscript.category.updateOrder') }}', {
                    //     method: 'POST',
                    //     headers: {
                    //         'Content-Type': 'application/json',
                    //         'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    //     },
                    //     body: JSON.stringify({categories: order})
                    // }).then(response => response.json())
                    //     .then(data => console.log(data))
                    //     .catch(error => console.error('Error:', error));
                }
            });
        };

        // Initialize sortable for the main category list
        buildSortable(document.getElementById('category-list'));

        // Initialize sortable for all nested sortable lists
        document.querySelectorAll('.nested-sortable').forEach((el) => {
            buildSortable(el);
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
                        url: '{{ route('admin.manuscript.category.destroy', '') }}/' + id,
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
