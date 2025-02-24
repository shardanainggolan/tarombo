@extends('admin.layouts.index')

@section('title', 'Tambah Page - Yayasan Scriptura Indonesia')

@section('styles')

@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Tambah Page</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.page.store') }}" enctype="multipart/form-data">
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
                    <label class="form-label" for="content">Konten</label>
                    <textarea class="form-control" rows="10" id="content" name="content" placeholder="Konten"></textarea>
                </div>
                <div class="tw-flex">
                    <button type="submit" class="btn btn-primary waves-effect waves-light">
                        Simpan
                    </button>
                    <a href="{{ route('admin.page.index') }}">
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
<script src="//cdn.ckeditor.com/4.22.1/full/ckeditor.js"></script>

<script>
    $(document).ready(function() {
        CKEDITOR.replace('content');
    })
</script>
@endpush