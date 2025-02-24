@extends('admin.layouts.index')

@section('title', 'Tambah Pengarang/Penerjemah - Scriptura')

@section('styles')

@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Tambah Pengarang/Penerjemah</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.product.author.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label" for="name">Nama</label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="name" 
                        name="name"
                        placeholder="Nama" 
                        required
                    />
                </div>
                <div class="mb-3">
                    <label class="form-label" for="photo">Foto</label>
                    <input 
                        type="file" 
                        class="form-control" 
                        id="photo" 
                        name="photo"
                    />
                </div>
                <div class="mb-3">
                    <label class="form-label" for="about">Tentang</label>
                    <textarea class="form-control" rows="5" id="about" name="about" placeholder="Tentang"></textarea>
                </div>
                <div class="tw-flex">
                    <button type="submit" class="btn btn-primary waves-effect waves-light">
                        Simpan
                    </button>
                    <a href="{{ route('admin.product.author.index') }}">
                        <button type="button" class="btn btn-danger waves-effect waves-light">
                            Kembali
                        </button>
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    
</script>
@endpush