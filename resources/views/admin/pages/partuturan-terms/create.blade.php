@extends('admin.layouts.index')
@section('title', 'Tambah Istilah Partuturan - Tarombo')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Tambah Istilah Partuturan</h5>
                    <a href="{{ route('admin.partuturan-terms.index') }}" class="btn btn-secondary">
                        <i class="ti ti-arrow-left me-sm-1"></i> Kembali
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.partuturan-terms.store') }}" method="POST">
                        @csrf
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="term">Istilah</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('term') is-invalid @enderror" id="term" name="term" value="{{ old('term') }}" placeholder="Contoh: Tulang, Namboru, etc." />
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
                                        <option value="{{ $category->id }}" {{ old('category_id', $preSelectedCategory ? $preSelectedCategory->id : '') == $category->id ? 'selected' : '' }}>
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
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" placeholder="Deskripsi istilah partuturan">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-10 offset-sm-2">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="redirect_to_rules" name="redirect_to_rules" value="1" {{ old('redirect_to_rules') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="redirect_to_rules">Lanjutkan ke pembuatan aturan setelah menyimpan</label>
                                </div>
                                <small class="text-muted">Pilih opsi ini jika Anda ingin langsung menambahkan aturan penggunaan untuk istilah ini.</small>
                            </div>
                        </div>
                        <div class="row justify-content-end">
                            <div class="col-sm-10">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection