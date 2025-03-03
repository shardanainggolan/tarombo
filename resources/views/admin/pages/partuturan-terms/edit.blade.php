@extends('admin.layouts.index')
@section('title', 'Edit Istilah Partuturan - Tarombo')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Istilah Partuturan</h5>
                    <a href="{{ route('admin.partuturan-terms.index') }}" class="btn btn-secondary">
                        <i class="ti ti-arrow-left me-sm-1"></i> Kembali
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.partuturan-terms.update', $partuturanTerm->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="term">Istilah</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('term') is-invalid @enderror" id="term" name="term" value="{{ old('term', $partuturanTerm->term) }}" />
                                @error('term')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="category_id">Kategori</label>
                            <div class="col-sm-10">
                                <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id">
                                    <option value="">Pilih Kategori</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $partuturanTerm->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="description">Deskripsi</label>
                            <div class="col-sm-10">
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description', $partuturanTerm->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        @if($partuturanTerm->rules->count() > 0)
                            <div class="row mb-4">
                                <label class="col-sm-2 col-form-label">Aturan Terkait</label>
                                <div class="col-sm-10">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Pola Hubungan</th>
                                                    <th>Jenis Kelamin Ego</th>
                                                    <th>Jenis Kelamin Kerabat</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($partuturanTerm->rules as $index => $rule)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>{{ $rule->relationshipPattern->pattern }}</td>
                                                        <td>{{ $rule->ego_gender == 'male' ? 'Laki-laki' : 'Perempuan' }}</td>
                                                        <td>{{ $rule->relative_gender == 'male' ? 'Laki-laki' : 'Perempuan' }}</td>
                                                        <td>
                                                            <a href="{{ route('admin.partuturan-rules.edit', $rule->id) }}" class="btn btn-sm btn-icon btn-label-info waves-effect">
                                                                <i class="ti ti-pencil"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    <div class="mt-2">
                                        <a href="{{ route('admin.partuturan-rules.create', ['term_id' => $partuturanTerm->id]) }}" class="btn btn-sm btn-primary">
                                            <i class="ti ti-plus me-1"></i> Tambah Aturan Baru
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="row mb-3">
                                <div class="col-sm-10 offset-sm-2">
                                    <div class="alert alert-warning">
                                        <div class="d-flex">
                                            <i class="ti ti-alert-triangle me-2"></i>
                                            <div>
                                                <p class="mb-0">Istilah ini belum memiliki aturan penggunaan.</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-2">
                                        <a href="{{ route('admin.partuturan-rules.create', ['term_id' => $partuturanTerm->id]) }}" class="btn btn-sm btn-primary">
                                            <i class="ti ti-plus me-1"></i> Tambah Aturan Penggunaan
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