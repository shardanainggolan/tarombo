@extends('admin.layouts.index')
@section('title', 'Edit Pola Hubungan - Tarombo')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Pola Hubungan</h5>
                    <a href="{{ route('admin.relationship-patterns.index') }}" class="btn btn-secondary">
                        <i class="ti ti-arrow-left me-sm-1"></i> Kembali
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.relationship-patterns.update', $relationshipPattern->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="pattern">Pola Hubungan</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control @error('pattern') is-invalid @enderror" id="pattern" name="pattern" value="{{ old('pattern', $relationshipPattern->pattern) }}" />
                                <small class="text-muted">Gunakan format segmen yang dipisahkan titik (.) seperti <code>father.brother</code></small>
                                <div id="pattern-preview" class="mt-2"></div>
                                @error('pattern')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="description">Deskripsi</label>
                            <div class="col-sm-10">
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description', $relationshipPattern->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        @if($relationshipPattern->rules->count() > 0)
                            <div class="row mb-4">
                                <label class="col-sm-2 col-form-label">Aturan Terkait</label>
                                <div class="col-sm-10">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Istilah</th>
                                                    <th>Jenis Kelamin Ego</th>
                                                    <th>Jenis Kelamin Kerabat</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($relationshipPattern->rules as $index => $rule)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>{{ $rule->partuturanTerm->term }}</td>
                                                        <td>{{ $rule->ego_gender == 'male' ? 'Laki-laki' : 'Perempuan' }}</td>
                                                        <td>{{ $rule->relative_gender == 'male' ? 'Laki-laki' : 'Perempuan' }}</td>
                                                        <td>
                                                            <a href="{{ route('admin.partuturan-rules.edit', $rule->id) }}" class="btn btn-sm btn-icon btn-label-info waves-effect">
                                                                <i class="ti ti-pencil"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    <div class="mt-2">
                                        <a href="{{ route('admin.partuturan-rules.create', ['pattern_id' => $relationshipPattern->id]) }}" class="btn btn-sm btn-primary">
                                            <i class="ti ti-plus me-1"></i> Tambah Aturan Baru
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="row mb-3">
                                <div class="col-sm-10 offset-sm-2">
                                    <div class="alert alert-warning">
                                        <div class="d-flex">
                                            <i class="ti ti-alert-triangle me-2"></i>
                                            <div>
                                                <p class="mb-0">Pola hubungan ini belum memiliki aturan penggunaan.</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-2">
                                        <a href="{{ route('admin.partuturan-rules.create', ['pattern_id' => $relationshipPattern->id]) }}" class="btn btn-sm btn-primary">
                                            <i class="ti ti-plus me-1"></i> Tambah Aturan Penggunaan
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif
                        
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const patternInput = document.getElementById('pattern');
        const patternPreview = document.getElementById('pattern-preview');
        
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
        
        // Update preview on input
        patternInput.addEventListener('input', function() {
            const pattern = this.value.trim();
            if (pattern) {
                patternPreview.innerHTML = '<div class="d-flex align-items-center mt-2">' + formatPattern(pattern) + '</div>';
            } else {
                patternPreview.innerHTML = '';
            }
        });
        
        // Initialize preview if value exists
        if (patternInput.value) {
            patternPreview.innerHTML = '<div class="d-flex align-items-center mt-2">' + formatPattern(patternInput.value.trim()) + '</div>';
        }
    });
</script>
@endpush