@extends('admin.layouts.index')

@section('title', 'Tambah Pernikahan - Scriptura')

@section('styles')

@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Tambah Pernikahan</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.marriages.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Suami</label>
                            <select name="husband_id" class="form-select" required>
                                @foreach($males as $male)
                                <option value="{{ $male->id }}">
                                    {{ $male->user->name }} ({{ $male->marga }})
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Istri</label>
                            <select name="wife_id" class="form-select" required>
                                @foreach($females as $female)
                                <option value="{{ $female->id }}">
                                    {{ $female->user->name }} ({{ $female->marga }})
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Tanggal Pernikahan</label>
                            <input type="date" name="marriage_date" 
                                   class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="tw-flex">
                    <button type="submit" class="btn btn-primary waves-effect waves-light">
                        Simpan
                    </button>
                    <a href="{{ route('admin.marriages.index') }}">
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
    $(document).ready(function() {
        
    })
</script>
@endpush