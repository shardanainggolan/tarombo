@extends('admin.layouts.index')
@section('title', 'Detail Orang - Tarombo')
@section('styles')
<style>
    .person-photo {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border-radius: 6px;
    }
    .relationship-card {
        transition: all 0.2s ease;
    }
    .relationship-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
</style>
@endsection
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Detail Orang</h5>
                    <div>
                        <a href="{{ route('admin.people.edit', $person->id) }}" class="btn btn-primary waves-effect waves-light me-2">
                            <i class="ti ti-pencil me-1"></i> Edit
                        </a>
                        <a href="{{ route('admin.people.index') }}" class="btn btn-secondary waves-effect waves-light">
                            <i class="ti ti-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center mb-4 mb-md-0">
                            @if($person->photo_url)
                                <img src="{{ Storage::url($person->photo_url) }}" alt="{{ $person->first_name }}" class="person-photo">
                            @else
                                <div class="person-photo d-flex align-items-center justify-content-center bg-light">
                                    <i class="ti ti-user fs-1"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-9">
                            <div class="row mb-3">
                                <div class="col-md-3 fw-bold">Nama Lengkap</div>
                                <div class="col-md-9">{{ $person->first_name }} {{ $person->last_name }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-3 fw-bold">Marga</div>
                                <div class="col-md-9">{{ $person->marga->name }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-3 fw-bold">Jenis Kelamin</div>
                                <div class="col-md-9">{{ $person->gender == 'male' ? 'Laki-laki' : 'Perempuan' }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-3 fw-bold">Tanggal Lahir</div>
                                <div class="col-md-9">{{ $person->birth_date ? $person->birth_date->format('d M Y') : '-' }}</div>
                            </div>
                            @if($person->death_date)
                            <div class="row mb-3">
                                <div class="col-md-3 fw-bold">Tanggal Meninggal</div>
                                <div class="col-md-9">{{ $person->death_date->format('d M Y') }}</div>
                            </div>
                            @endif
                            <div class="row mb-3">
                                <div class="col-md-3 fw-bold">Catatan</div>
                                <div class="col-md-9">{{ $person->notes ?: '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Parents Section -->
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Orang Tua</h5>
                </div>
                <div class="card-body">
                    @if($person->parents->count() > 0)
                        <div class="row">
                            @foreach($person->parents as $parent)
                                <div class="col-md-6 mb-3">
                                    <div class="card relationship-card">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    @if($parent->photo_url)
                                                        <img src="{{ Storage::url($parent->photo_url) }}" class="rounded-circle" width="50" height="50" style="object-fit: cover;">
                                                    @else
                                                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                            <i class="ti ti-user"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h6 class="mb-1">{{ $parent->first_name }} {{ $parent->last_name }}</h6>
                                                    <div>{{ $parent->marga->name }} | {{ $parent->gender == 'male' ? 'Ayah' : 'Ibu' }}</div>
                                                </div>
                                                <a href="{{ route('admin.people.show', $parent->id) }}" class="btn btn-sm btn-icon btn-label-primary waves-effect">
                                                    <i class="ti ti-eye"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-3 text-muted">
                            <i class="ti ti-info-circle fs-3 mb-2"></i>
                            <p>Belum ada data orang tua</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Marriages Section -->
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Pernikahan</h5>
                    <a href="{{ route('admin.marriages.create', ['person_id' => $person->id]) }}" class="btn btn-sm btn-primary waves-effect waves-light">
                        <i class="ti ti-plus me-1"></i> Tambah Pernikahan
                    </a>
                </div>
                <div class="card-body">
                    @php
                        $marriages = $person->gender == 'male' 
                            ? $person->marriagesAsHusband 
                            : $person->marriagesAsWife;
                    @endphp
                    
                    @if($marriages->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>{{ $person->gender == 'male' ? 'Istri' : 'Suami' }}</th>
                                        <th>Tanggal Pernikahan</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($marriages as $index => $marriage)
                                        @php
                                            $spouse = $person->gender == 'male' 
                                                ? $marriage->wife 
                                                : $marriage->husband;
                                        @endphp
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($spouse->photo_url)
                                                        <img src="{{ Storage::url($spouse->photo_url) }}" class="rounded-circle me-2" width="40" height="40" style="object-fit: cover;">
                                                    @else
                                                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                                                            <i class="ti ti-user"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <h6 class="mb-0">{{ $spouse->first_name }} {{ $spouse->last_name }}</h6>
                                                        <small>{{ $spouse->marga->name }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $marriage->marriage_date ? $marriage->marriage_date->format('d M Y') : '-' }}</td>
                                            <td>
                                                @if($marriage->is_current)
                                                    <span class="badge bg-success">Aktif</span>
                                                @elseif($marriage->divorce_date)
                                                    <span class="badge bg-danger">Bercerai ({{ $marriage->divorce_date->format('d M Y') }})</span>
                                                @else
                                                    <span class="badge bg-secondary">Tidak Aktif</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <a href="{{ route('admin.marriages.edit', $marriage->id) }}" class="btn btn-sm btn-icon btn-label-info waves-effect me-2">
                                                        <i class="ti ti-pencil"></i>
                                                    </a>
                                                    <a href="{{ route('admin.people.show', $spouse->id) }}" class="btn btn-sm btn-icon btn-label-primary waves-effect">
                                                        <i class="ti ti-eye"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-3 text-muted">
                            <i class="ti ti-info-circle fs-3 mb-2"></i>
                            <p>Belum ada data pernikahan</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Children Section -->
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Anak</h5>
                    <a href="{{ route('admin.parent-child.create', ['parent_id' => $person->id]) }}" class="btn btn-sm btn-primary waves-effect waves-light">
                        <i class="ti ti-plus me-1"></i> Tambah Anak
                    </a>
                </div>
                <div class="card-body">
                    @if($person->children->count() > 0)
                        <div class="row" id="children-list">
                            @php
                                // Sort children: males first, then females
                                $maleChildren = $person->children->where('gender', 'male')->values();
                                $femaleChildren = $person->children->where('gender', 'female')->values();
                                $sortedChildren = $maleChildren->concat($femaleChildren);
                            @endphp
                            
                            @foreach($sortedChildren as $child)
                                @php
                                    $parentChild = $person->children()->where('child_id', $child->id)->first()->pivot;
                                @endphp
                                <div class="col-md-6 mb-3 child-item" data-id="{{ $child->id }}">
                                    <div class="card relationship-card">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                @if($person->gender == 'male')
                                                <div class="drag-handle cursor-move me-2">
                                                    <i class="ti ti-grip-vertical text-muted"></i>
                                                </div>
                                                @endif
                                                <div class="flex-shrink-0">
                                                    @if($child->photo_url)
                                                        <img src="{{ Storage::url($child->photo_url) }}" class="rounded-circle" width="50" height="50" style="object-fit: cover;">
                                                    @else
                                                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                            <i class="ti ti-user"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h6 class="mb-1">{{ $child->first_name }} {{ $child->last_name }}</h6>
                                                    <div>
                                                        {{ $child->marga->name }} | 
                                                        {{ $child->gender == 'male' ? 'Laki-laki' : 'Perempuan' }}
                                                        @if($child->birth_date)
                                                          | {{ $child->birth_date->format('d M Y') }}
                                                        @endif
                                                    </div>
                                                    <div class="mt-1">
                                                        <span class="badge bg-label-{{ $parentChild->is_biological ? 'success' : 'info' }}">
                                                            {{ $parentChild->is_biological ? 'Anak Kandung' : 'Anak Angkat' }}
                                                        </span>
                                                        <span class="badge bg-label-primary">
                                                            Urutan: {{ $parentChild->birth_order }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-icon" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="ti ti-dots-vertical"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            <li>
                                                                <a class="dropdown-item" href="{{ route('admin.people.show', $child->id) }}">
                                                                    <i class="ti ti-eye me-1"></i> Lihat Detail
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item" href="{{ route('admin.parent-child.edit', $parentChild->id) }}">
                                                                    <i class="ti ti-pencil me-1"></i> Edit Hubungan
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item text-danger delete-relationship" href="javascript:void(0)" 
                                                                   data-parent-id="{{ $person->id }}" data-child-id="{{ $child->id }}">
                                                                    <i class="ti ti-trash me-1"></i> Hapus Hubungan
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-3 text-muted">
                            <i class="ti ti-info-circle fs-3 mb-2"></i>
                            <p>Belum ada data anak</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('admin/js/Sortable.min.js') }}"></script>

<script>
    // Initialize sortable list for children reordering
    const childrenList = document.getElementById('children-list');
    if (childrenList) {
        new Sortable(childrenList, {
            animation: 150,
            handle: '.drag-handle',
            onEnd: function(evt) {
                // Get the new order of children IDs
                const childIds = Array.from(childrenList.querySelectorAll('.child-item'))
                    .map(item => item.getAttribute('data-id'));
                
                // Send to server
                axios.post('{{ route('admin.parent-child.reorder') }}', {
                    parent_id: {{ $person->id }},
                    child_ids: childIds
                })
                .then(response => {
                    const toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                    
                    toast.fire({
                        icon: 'success',
                        title: 'Urutan anak berhasil diperbarui'
                    });
                })
                .catch(error => {
                    console.error('Reordering failed:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Mengubah Urutan',
                        text: 'Terjadi kesalahan saat menyimpan urutan baru',
                        timer: 3000
                    });
                });
            }
        });
    }

    // Delete relationship handler
    document.querySelectorAll('.delete-relationship').forEach(btn => {
        btn.addEventListener('click', function() {
            const parentId = this.getAttribute('data-parent-id');
            const childId = this.getAttribute('data-child-id');
            
            Swal.fire({
                title: "Apakah Anda yakin?",
                text: "Hubungan orang tua-anak akan dihapus",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: 'Batal',
                customClass: {
                    confirmButton: "btn btn-primary me-3",
                    cancelButton: "btn btn-label-secondary"
                },
                buttonsStyling: false
            }).then(result => {
                if (result.isConfirmed) {
                    axios({
                        method: 'delete',
                        url: '{{ route('admin.parent-child.destroy') }}',
                        data: {
                            parent_id: parentId,
                            child_id: childId
                        }
                    })
                    .then(function(response) {
                        Swal.fire({
                            icon: "success",
                            title: response.data.message,
                            customClass: {
                                confirmButton: "btn btn-success"
                            },
                            timer: 3000
                        }).then(() => {
                            window.location.reload();
                        });
                    })
                    .catch(function(error) {
                        let errorMsg = 'Terjadi kesalahan sistem';
                        if (error.response && error.response.data && error.response.data.message) {
                            errorMsg = error.response.data.message;
                        }
                        
                        Swal.fire({
                            icon: "error",
                            title: "Gagal Menghapus",
                            text: errorMsg,
                            customClass: {
                                confirmButton: "btn btn-danger"
                            }
                        });
                    });
                }
            });
        });
    });
</script>
@endpush