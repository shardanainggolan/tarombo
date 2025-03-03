@extends('admin.layouts.index')
@section('title', 'Edit Pernikahan - Tarombo')
@section('styles')
<link rel="stylesheet" href="{{ asset('admin/css/flatpickr.css') }}" />
@endsection
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Pernikahan</h5>
                    <a href="{{ route('admin.marriages.index') }}" class="btn btn-secondary">
                        <i class="ti ti-arrow-left me-sm-1"></i> Kembali
                    </a>
                </div>
                <div class="card-body">
                    <div class="alert alert-primary mb-4">
                        <div class="d-flex">
                            <i class="ti ti-info-circle me-2"></i>
                            <div>
                                <p class="mb-0">Pernikahan antara <strong>{{ $marriage->husband->first_name }} {{ $marriage->husband->last_name }}</strong> 
                                dan <strong>{{ $marriage->wife->first_name }} {{ $marriage->wife->last_name }}</strong></p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('admin.marriages.update', $marriage->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="marriage_date">Tanggal Pernikahan</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control flatpickr-date @error('marriage_date') is-invalid @enderror" id="marriage_date" name="marriage_date" value="{{ old('marriage_date', $marriage->marriage_date ? $marriage->marriage_date->format('Y-m-d') : '') }}" />
                                @error('marriage_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="is_current">Status Pernikahan</label>
                            <div class="col-sm-10">
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input @error('is_current') is-invalid @enderror" type="checkbox" id="is_current" name="is_current" value="1" {{ old('is_current', $marriage->is_current) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_current">Pernikahan Aktif</label>
                                </div>
                                <small class="text-muted">Jika dinonaktifkan, pernikahan ini dianggap telah berakhir tanpa perceraian formal.</small>
                                @error('is_current')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="divorce_date">Tanggal Perceraian</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control flatpickr-date @error('divorce_date') is-invalid @enderror" id="divorce_date" name="divorce_date" value="{{ old('divorce_date', $marriage->divorce_date ? $marriage->divorce_date->format('Y-m-d') : '') }}" />
                                <small class="text-muted">Kosongkan jika masih menikah atau berakhir tanpa perceraian formal.</small>
                                @error('divorce_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="marriage_order">Urutan Pernikahan</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control @error('marriage_order') is-invalid @enderror" id="marriage_order" name="marriage_order" value="{{ old('marriage_order', $marriage->marriage_order) }}" min="1">
                                <small class="text-muted">Urutan pernikahan (jika memiliki lebih dari satu pernikahan).</small>
                                @error('marriage_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="notes">Catatan</label>
                            <div class="col-sm-10">
                                <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes', $marriage->notes) }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
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
@push('scripts')
<script src="{{ asset('admin/js/flatpickr.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize date pickers
        flatpickr('.flatpickr-date', {
            dateFormat: 'Y-m-d',
            allowInput: true
        });
        
        // Handle is_current and divorce_date interdependency
        const isCurrentCheckbox = document.getElementById('is_current');
        const divorceDateInput = document.getElementById('divorce_date');
        
        isCurrentCheckbox.addEventListener('change', function() {
            if (this.checked && divorceDateInput.value) {
                // If marking as current but divorce date is set, clear divorce date
                divorceDateInput._flatpickr.clear();
            }
        });
        
        divorceDateInput._flatpickr.config.onChange.push(function(selectedDates, dateStr) {
            if (dateStr && isCurrentCheckbox.checked) {
                // If setting divorce date but marked as current, uncheck current
                isCurrentCheckbox.checked = false;
            }
        });
    });
</script>
@endpush