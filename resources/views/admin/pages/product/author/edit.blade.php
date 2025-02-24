@extends('admin.layouts.index')

@section('title', 'Ubah Pengarang/Penerjemah - Scriptura')

@section('styles')
<link rel="stylesheet" href="{{ asset('admin/css/sweetalert2.css') }}" />
<link rel="stylesheet" href="{{ asset('css/fancybox.css') }}" />
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Ubah Pengarang/Penerjemah</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.product.author.update', $data->id) }}">
                @method('PUT')
                @csrf
                <div class="mb-3">
                    <label class="form-label" for="name">Status</label>
                    <select class="form-control" id="status" name="status" required>
                        <option value="1" {{ $data->status == 1 ? 'selected' : '' }}>AKTIF</option>
                        <option value="0" {{ $data->status == 0 ? 'selected' : '' }}>TIDAK AKTIF</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="name">Nama</label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="name" 
                        name="name"
                        value="{{ $data->name }}"
                        placeholder="Nama" 
                        required
                    />
                </div>
                <div class="mb-3">
                    <label class="form-label" for="photo">Foto</label>
                    <div class="card mt-2 mb-3">
                        <div class="card-body p-2">
                            @if($data->photo)
                                <a href="{{ $data->originalPhoto }}" data-fancybox="" data-caption="{{ $data->name }}">
                                    <img 
                                        src="{{ $data->thumbnail }}"
                                        class="img-responsive"
                                        style="width: 96px; height: 96px;"
                                        alt="{{ $data->name }}"
                                    />
                                </a>
                            @else
                            <a href="{{ asset('images/user.png') }}" data-fancybox="" data-caption="{{ $data->name }}">
                                <img 
                                    src="{{ asset('images/user.png') }}"
                                    class="img-responsive"
                                    style="width: 96px; height: 96px;"
                                    alt="{{ $data->name }}"
                                />
                            </a>
                            @endif
                        </div>
                    </div>
                    <input 
                        type="file" 
                        class="form-control" 
                        id="photo" 
                        name="photo"
                    />
                </div>
                <div class="mb-3">
                    <label class="form-label" for="about">Tentang</label>
                    <textarea class="form-control" rows="5" id="about" name="about" placeholder="Tentang">{{ $data->about }}</textarea>
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
<script src="{{ asset('admin/js/sweetalert2.js') }}"></script>
<script src="{{ asset('js/fancybox.umd.js') }}"></script>

<script>
    $(document).ready(function() {
        Fancybox.bind("[data-fancybox]", {
            
        });
    })
</script>
@endpush