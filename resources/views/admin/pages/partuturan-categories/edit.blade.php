@extends('admin.layouts.index')
@section('title', 'Edit Kategori Partuturan - Tarombo')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Kategori Partuturan</h5>
                    <a href="{{ route('admin.partuturan-categories.index') }}" class="btn btn-secondary">
                        <i class="ti ti-arrow-left me-sm-1"></i> Kembali
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.partuturan-categories.update', $partuturanCategory->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="name">Nama Kategori</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $partuturanCategory->name) }}" />
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="description">Deskripsi</label>
                            <div class="col-sm-10">
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description', $partuturanCategory->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        @if($partuturanCategory->terms->count() > 0)
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Istilah Terkait</label>
                                <div class="col-sm-10">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Istilah</th>
                                                    <th>Deskripsi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($partuturanCategory->terms as $index => $term)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>
                                                            <a href="{{ route('admin.partuturan-terms.edit', $term->id) }}">{{ $term->term }}</a>
                                                        </td>
                                                        <td>{{ $term->description }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    <div class="mt-2">
                                        <a href="{{ route('admin.partuturan-terms.create', ['category_id' => $partuturanCategory->id]) }}" class="btn btn-sm btn-primary">
                                            <i class="ti ti-plus me-1"></i> Tambah Istilah Baru
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        <div class="row justify-content-end">
                            <div class="col-sm-10">
                                <button type="submit" class="btn btn-primary">Perbarui</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection