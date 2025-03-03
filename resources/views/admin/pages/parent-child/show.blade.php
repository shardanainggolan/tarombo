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
                        <div class="row">
                            @php
                                // Sort children: males first, then females
                                $maleChildren = $person->children->where('gender', 'male')->values();
                                $femaleChildren = $person->children->where('gender', 'female')->values();
                                $sortedChildren = $maleChildren->concat($femaleChildren);
                            @endphp
                            
                            @foreach($sortedChildren as $child)
                                <div class="col-md-6 mb-3">
                                    <div class="card relationship-card">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
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
                                                </div>
                                                <a href="{{ route('admin.people.show', $child->id) }}" class="btn btn-sm btn-icon btn-label-primary waves-effect">
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
                            <p>Belum ada data anak</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection