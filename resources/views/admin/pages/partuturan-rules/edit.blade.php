@extends('admin.layouts.index')
@section('title', 'Edit Aturan Partuturan - Tarombo')
@section('styles')
<link rel="stylesheet" href="{{ asset('admin/css/select2.css') }}" />
<style>
    .pattern-preview {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        margin-top: 0.5rem;
    }
</style>
@endsection
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Aturan Partuturan</h5>
                    <a href="{{ route('admin.partuturan-rules.index') }}" class="btn btn-secondary">
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
                    
                    <form action="{{ route('admin.partuturan-rules.update', $partuturanRule->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="partuturan_term_id">Istilah Partuturan</label>
                            <div class="col-sm-10">
                                <select class="select2 form-select @error('partuturan_term_id') is-invalid @enderror" id="partuturan_term_id" name="partuturan_term_id">
                                    <option value="">Pilih Istilah</option>
                                    @foreach($terms as $term)
                                        <option value="{{ $term->id }}" data-category="{{ $term->category->name }}" {{ old('partuturan_term_id', $partuturanRule->partuturan_term_id) == $term->id ? 'selected' : '' }}>
                                            {{ $term->term }} ({{ $term->category->name }})
                                        </option>
                                    @endforeach
                                </select>
                                <div id="term-details" class="mt-2"></div>
                                @error('partuturan_term_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="relationship_pattern_id">Pola Hubungan</label>
                            <div class="col-sm-10">
                                <select class="select2 form-select @error('relationship_pattern_id') is-invalid @enderror" id="relationship_pattern_id" name="relationship_pattern_id">
                                    <option value="">Pilih Pola Hubungan</option>
                                    @foreach($patterns as $pattern)
                                        <option value="{{ $pattern->id }}" data-pattern="{{ $pattern->pattern }}" {{ old('relationship_pattern_id', $partuturanRule->relationship_pattern_id) == $pattern->id ? 'selected' : '' }}>
                                            {{ $pattern->pattern }} {{ $pattern->description ? '(' . $pattern->description . ')' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                                <div id="pattern-preview" class="pattern-preview"></div>
                                @error('relationship_pattern_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Jenis Kelamin Ego</label>
                            <div class="col-sm-10">
                                <div class="form-check form-check-inline mt-2">
                                    <input class="form-check-input @error('ego_gender') is-invalid @enderror" type="radio" name="ego_gender" id="ego_male" value="male" {{ old('ego_gender', $partuturanRule->ego_gender) == 'male' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="ego_male">Laki-laki</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input @error('ego_gender') is-invalid @enderror" type="radio" name="ego_gender" id="ego_female" value="female" {{ old('ego_gender', $partuturanRule->ego_gender) == 'female' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="ego_female">Perempuan</label>
                                </div>
                                <small class="d-block text-muted">Jenis kelamin dari orang yang menggunakan istilah ini.</small>
                                @error('ego_gender')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Jenis Kelamin Kerabat</label>
                            <div class="col-sm-10">
                                <div class="form-check form-check-inline mt-2">
                                    <input class="form-check-input @error('relative_gender') is-invalid @enderror" type="radio" name="relative_gender" id="relative_male" value="male" {{ old('relative_gender', $partuturanRule->relative_gender) == 'male' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="relative_male">Laki-laki</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input @error('relative_gender') is-invalid @enderror" type="radio" name="relative_gender" id="relative_female" value="female" {{ old('relative_gender', $partuturanRule->relative_gender) == 'female' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="relative_female">Perempuan</label>
                                </div>
                                <small class="d-block text-muted">Jenis kelamin dari orang yang dipanggil dengan istilah ini.</small>
                                @error('relative_gender')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-10 offset-sm-2">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="invalidate_cache" name="invalidate_cache" value="1" {{ old('invalidate_cache', '1') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="invalidate_cache">Hapus relasi dalam cache untuk pola ini</label>
                                </div>
                                <small class="text-muted">Pilih opsi ini untuk memastikan bahwa hubungan yang tersimpan dalam cache dihitung ulang dengan aturan baru.</small>
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
<script src="{{ asset('admin/js/select2.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize select2
        $('.select2').select2({
            placeholder: 'Pilih...',
            allowClear: true
        });
        
        // Term details
        const termSelect = document.getElementById('partuturan_term_id');
        const termDetails = document.getElementById('term-details');
        
        termSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value) {
                const category = selectedOption.getAttribute('data-category');
                termDetails.innerHTML = `
                    <div class="alert alert-primary mb-0">
                        <div class="d-flex align-items-center">
                            <i class="ti ti-info-circle me-2"></i>
                            <div>Kategori: <strong>${category}</strong></div>
                        </div>
                    </div>
                `;
            } else {
                termDetails.innerHTML = '';
            }
        });
        
        // Pattern preview
        const patternSelect = document.getElementById('relationship_pattern_id');
        const patternPreview = document.getElementById('pattern-preview');
        
        patternSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value) {
                const pattern = selectedOption.getAttribute('data-pattern');
                patternPreview.innerHTML = formatPattern(pattern);
            } else {
                patternPreview.innerHTML = '';
            }
        });
        
        // Initialize displays if values exist
        if (termSelect.value) {
            const selectedOption = termSelect.options[termSelect.selectedIndex];
            const category = selectedOption.getAttribute('data-category');
            termDetails.innerHTML = `
                <div class="alert alert-primary mb-0">
                    <div class="d-flex align-items-center">
                        <i class="ti ti-info-circle me-2"></i>
                        <div>Kategori: <strong>${category}</strong></div>
                    </div>
                </div>
            `;
        }
        
        if (patternSelect.value) {
            const selectedOption = patternSelect.options[patternSelect.selectedIndex];
            const pattern = selectedOption.getAttribute('data-pattern');
            patternPreview.innerHTML = formatPattern(pattern);
        }
        
        // Function to format pattern segments with visual cues
        function formatPattern(pattern) {
            if (!pattern) return '';
            
            const segments = pattern.split('.');
            let formatted = '';
            
            segments.forEach((segment, index) => {
                const segmentClass = getSegmentColorClass(segment);
                const arrow = index > 0 ? '<i class="ti ti-arrow-right mx-1"></i>' : '';
                formatted += arrow + '<span class="badge ' + segmentClass + ' me-1">' + segment + '</span>';
            });
            
            return formatted;
        }
        
        // Function to determine color class based on segment type
        function getSegmentColorClass(segment) {
            const maleTerms = ['father', 'son', 'brother', 'husband'];
            const femaleTerms = ['mother', 'daughter', 'sister', 'wife'];
            const neutralTerms = ['child', 'parent', 'sibling', 'spouse'];
            
            if (maleTerms.includes(segment)) {
                return 'bg-primary';
            } else if (femaleTerms.includes(segment)) {
                return 'bg-danger';
            } else if (neutralTerms.includes(segment)) {
                return 'bg-warning';
            } else {
                return 'bg-secondary';
            }
        }
    });
</script>
@endpush