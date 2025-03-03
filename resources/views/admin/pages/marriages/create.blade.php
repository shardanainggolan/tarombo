@extends('admin.layouts.index')
@section('title', 'Tambah Pernikahan - Tarombo')
@section('styles')
<link rel="stylesheet" href="{{ asset('admin/css/flatpickr.min.css') }}" />
<link rel="stylesheet" href="{{ asset('admin/css/select2.css') }}" />
<style>
    .select2-container--default .select2-results__option[aria-disabled=true] {
        background-color: #f8f9fa;
        color: #6c757d;
    }
</style>
@endsection
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Tambah Pernikahan</h5>
                    <a href="{{ route('admin.marriages.index') }}" class="btn btn-secondary">
                        <i class="ti ti-arrow-left me-sm-1"></i> Kembali
                    </a>
                </div>
                <div class="card-body">
                    @if ($errors->has('general'))
                        <div class="alert alert-danger mb-4">
                            <div class="d-flex">
                                <i class="ti ti-alert-triangle me-2"></i>
                                <div>{{ $errors->first('general') }}</div>
                            </div>
                        </div>
                    @endif
                    
                    <div class="alert alert-info mb-4">
                        <div class="d-flex">
                            <i class="ti ti-info-circle me-2"></i>
                            <div>
                                <p class="mb-0">Sesuai adat Batak Toba, pernikahan tidak diperbolehkan antara orang dengan marga yang sama.</p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('admin.marriages.store') }}" method="POST">
                        @csrf
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="husband_id">Suami</label>
                            <div class="col-sm-10">
                                <select class="select2 form-select @error('husband_id') is-invalid @enderror" id="husband_id" name="husband_id" 
                                        {{ $preSelectedGender === 'male' ? 'disabled' : '' }}>
                                    <option value="">Pilih Suami</option>
                                    @foreach($potentialHusbands as $husband)
                                        <option value="{{ $husband->id }}" 
                                                {{ old('husband_id', $preSelectedPerson && $preSelectedPerson->gender === 'male' ? $preSelectedPerson->id : '') == $husband->id ? 'selected' : '' }}>
                                            {{ $husband->first_name }} {{ $husband->last_name }} ({{ $husband->marga->name }})
                                        </option>
                                    @endforeach
                                </select>
                                @if ($preSelectedGender === 'male')
                                    <input type="hidden" name="husband_id" value="{{ $preSelectedPerson->id }}">
                                @endif
                                @error('husband_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="wife_id">Istri</label>
                            <div class="col-sm-10">
                                <select class="select2 form-select @error('wife_id') is-invalid @enderror" id="wife_id" name="wife_id"
                                        {{ $preSelectedGender === 'female' ? 'disabled' : '' }}>
                                    <option value="">Pilih Istri</option>
                                    @foreach($potentialWives as $wife)
                                        <option value="{{ $wife->id }}" 
                                                {{ old('wife_id', $preSelectedPerson && $preSelectedPerson->gender === 'female' ? $preSelectedPerson->id : '') == $wife->id ? 'selected' : '' }}>
                                            {{ $wife->first_name }} {{ $wife->last_name }} ({{ $wife->marga->name }})
                                        </option>
                                    @endforeach
                                </select>
                                @if ($preSelectedGender === 'female')
                                    <input type="hidden" name="wife_id" value="{{ $preSelectedPerson->id }}">
                                @endif
                                @error('wife_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="marriage_date">Tanggal Pernikahan</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control flatpickr-date @error('marriage_date') is-invalid @enderror" id="marriage_date" name="marriage_date" value="{{ old('marriage_date') }}" />
                                @error('marriage_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="is_current">Status Pernikahan</label>
                            <div class="col-sm-10">
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input @error('is_current') is-invalid @enderror" type="checkbox" id="is_current" name="is_current" value="1" {{ old('is_current', '1') == '1' ? 'checked' : '' }}>
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
                                <input type="text" class="form-control flatpickr-date @error('divorce_date') is-invalid @enderror" id="divorce_date" name="divorce_date" value="{{ old('divorce_date') }}" />
                                <small class="text-muted">Kosongkan jika masih menikah atau berakhir tanpa perceraian formal.</small>
                                @error('divorce_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="marriage_order">Urutan Pernikahan</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control @error('marriage_order') is-invalid @enderror" id="marriage_order" name="marriage_order" value="{{ old('marriage_order', 1) }}" min="1">
                                <small class="text-muted">Urutan pernikahan (jika memiliki lebih dari satu pernikahan).</small>
                                @error('marriage_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="notes">Catatan</label>
                            <div class="col-sm-10">
                                <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                                @error('notes')
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
<script src="{{ asset('admin/js/flatpickr.js') }}"></script>
<script src="{{ asset('admin/js/select2.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize date pickers
        flatpickr('.flatpickr-date', {
            dateFormat: 'Y-m-d',
            allowInput: true
        });
        
        // Initialize select2
        $('.select2').select2({
            placeholder: 'Pilih...',
            allowClear: true
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
        
        // Handle marga validation between husband and wife
        const husbandSelect = document.getElementById('husband_id');
        const wifeSelect = document.getElementById('wife_id');
        
        if (husbandSelect && wifeSelect) {
            const husbandData = {};
            const wifeData = {};
            
            // Populate husband data
            @foreach($potentialHusbands as $husband)
                husbandData[{{ $husband->id }}] = {
                    marga_id: {{ $husband->marga_id }},
                    marga_name: "{{ $husband->marga->name }}"
                };
            @endforeach
            
            // Populate wife data
            @foreach($potentialWives as $wife)
                wifeData[{{ $wife->id }}] = {
                    marga_id: {{ $wife->marga_id }},
                    marga_name: "{{ $wife->marga->name }}"
                };
            @endforeach
            
            // Function to update selectable options
            function updateSelectableSpouses() {
                const husbandId = husbandSelect.value;
                const wifeId = wifeSelect.value;
                
                if (husbandId) {
                    const husbandMargaId = husbandData[husbandId].marga_id;
                    
                    // Disable wives with same marga
                    $(wifeSelect).find('option').each(function() {
                        const wifeId = $(this).val();
                        if (wifeId && wifeData[wifeId].marga_id === husbandMargaId) {
                            $(this).attr('disabled', 'disabled');
                        } else {
                            $(this).removeAttr('disabled');
                        }
                    });
                    
                    // If selected wife now has same marga, deselect
                    if (wifeId && wifeData[wifeId].marga_id === husbandMargaId) {
                        $(wifeSelect).val(null).trigger('change');
                    }
                }
                
                if (wifeId) {
                    const wifeMargaId = wifeData[wifeId].marga_id;
                    
                    // Disable husbands with same marga
                    $(husbandSelect).find('option').each(function() {
                        const husbandId = $(this).val();
                        if (husbandId && husbandData[husbandId].marga_id === wifeMargaId) {
                            $(this).attr('disabled', 'disabled');
                        } else {
                            $(this).removeAttr('disabled');
                        }
                    });
                    
                    // If selected husband now has same marga, deselect
                    if (husbandId && husbandData[husbandId].marga_id === wifeMargaId) {
                        $(husbandSelect).val(null).trigger('change');
                    }
                }
                
                // Refresh Select2 to show changes
                $(husbandSelect).select2();
                $(wifeSelect).select2();
            }
            
            // Set up event listeners
            $(husbandSelect).on('change', updateSelectableSpouses);
            $(wifeSelect).on('change', updateSelectableSpouses);
            
            // Initialize constraints
            updateSelectableSpouses();
        }
    });
</script>
@endpush