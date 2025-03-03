@extends('admin.layouts.index')
@section('title', 'Tambah Anak - Tarombo')
@section('styles')
<link rel="stylesheet" href="{{ asset('admin/css/select2.css') }}" />
@endsection
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Tambah Hubungan Orang Tua-Anak</h5>
                    <a href="{{ route('admin.people.show', $parent->id) }}" class="btn btn-secondary">
                        <i class="ti ti-arrow-left me-sm-1"></i> Kembali
                    </a>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <div class="alert alert-primary">
                            <div class="d-flex">
                                <i class="ti ti-info-circle me-2 mt-1"></i>
                                <div>
                                    <h6 class="alert-heading mb-1">Orang Tua</h6>
                                    <p class="mb-0">{{ $parent->first_name }} {{ $parent->last_name }} ({{ $parent->marga->name }})</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('admin.parent-child.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="parent_id" value="{{ $parent->id }}">
                        
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="child_ids">Pilih Anak</label>
                            <div class="col-sm-10">
                                <select class="select2 form-select @error('child_ids') is-invalid @enderror" id="child_ids" name="child_ids[]" multiple>
                                    @foreach($potentialChildren as $child)
                                        <option value="{{ $child->id }}">
                                            {{ $child->first_name }} {{ $child->last_name }} 
                                            ({{ $child->marga->name }}, {{ $child->gender == 'male' ? 'Laki-laki' : 'Perempuan' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('child_ids')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                
                                @if($potentialChildren->isEmpty())
                                    <div class="text-danger mt-1">
                                        <small><i class="ti ti-alert-circle me-1"></i> Tidak ada calon anak yang tersedia.</small>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        @if($parent->gender == 'male' && $marriages->isNotEmpty())
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="marriage_id">Pernikahan</label>
                            <div class="col-sm-10">
                                <select class="form-select @error('marriage_id') is-invalid @enderror" id="marriage_id" name="marriage_id">
                                    <option value="">Pilih Pernikahan</option>
                                    @foreach($marriages as $marriage)
                                        <option value="{{ $marriage->id }}">
                                            Dengan {{ $marriage->wife->first_name }} {{ $marriage->wife->last_name }}
                                            @if($marriage->marriage_date)
                                                ({{ $marriage->marriage_date->format('d M Y') }})
                                            @endif
                                            @if($marriage->is_current)
                                                - Aktif
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Opsional: Pilih pernikahan untuk mengaitkan anak dengan ibu.</small>
                                @error('marriage_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        @endif
                        
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="is_biological">Hubungan</label>
                            <div class="col-sm-10">
                                <div class="form-check form-check-inline mt-2">
                                    <input class="form-check-input @error('is_biological') is-invalid @enderror" type="radio" name="is_biological" id="biological" value="1" checked>
                                    <label class="form-check-label" for="biological">Anak Kandung</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input @error('is_biological') is-invalid @enderror" type="radio" name="is_biological" id="non_biological" value="0">
                                    <label class="form-check-label" for="non_biological">Anak Angkat</label>
                                </div>
                                @error('is_biological')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="birth_order">Urutan Kelahiran</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control @error('birth_order') is-invalid @enderror" id="birth_order" name="birth_order" value="1" min="1">
                                <small class="text-muted">Urutan kelahiran anak dalam keluarga (sesuai kronologi).</small>
                                @error('birth_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
@push('scripts')
<script src="{{ asset('admin/js/select2.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize select2
        $('.select2').select2({
            placeholder: 'Pilih satu atau lebih anak',
            allowClear: true
        });
    });
</script>
@endpush