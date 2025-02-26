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
                <div class="mb-3">
                    <label for="husband_id" class="form-label">Suami</label>
                    <select name="husband_id" id="husband_id" class="form-control" required>
                        <option value="">Pilih Suami</option>
                        @foreach ($familyMembers as $familyMember)
                            <option value="{{ $familyMember->id }}">{{ $familyMember->user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="wife_id" class="form-label">Istri</label>
                    <select name="wife_id" id="wife_id" class="form-control" required>
                        <option value="">Pilih Istri</option>
                        @foreach ($familyMembers as $familyMember)
                            <option value="{{ $familyMember->id }}">{{ $familyMember->user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="marriage_date" class="form-label">Tanggal Pernikahan</label>
                    <input type="date" class="form-control" name="marriage_date" id="marriage_date" required>
                </div>
                <div class="mb-3">
                    <label for="divorce_date" class="form-label">Tanggal Perceraian (Opsional)</label>
                    <input type="date" class="form-control" name="divorce_date" id="divorce_date">
                </div>
                <div class="tw-flex">
                    <button type="submit" class="btn btn-primary waves-effect waves-light">
                        Simpan
                    </button>
                    <a href="{{ route('admin.users.index') }}">
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