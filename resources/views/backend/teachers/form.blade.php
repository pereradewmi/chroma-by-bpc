@extends('backend.layouts.app')

@section('content')
    @include('backend.layouts.headers.cards')
    
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ $isEdit ? 'Edit Teacher' : 'Register New Teacher' }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('teachers.index') }}" class="btn btn-sm btn-primary">Back to List</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <form action="{{ route('teachers.store') }}" method="POST">
                            @csrf
                            @if($isEdit)
                                <input type="hidden" name="is_update" value="1">
                                <input type="hidden" name="teacher_id" value="{{ $teacher->T_ID }}">
                            @endif
                            
                            <h6 class="heading-small text-muted mb-4">Teacher Information</h6>
                            
                            <div class="pl-lg-4">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="tFName">First Name</label>
                                            <input type="text" id="tFName" name="tFName" 
                                                class="form-control form-control-alternative @error('tFName') is-invalid @enderror" 
                                                placeholder="First Name" 
                                                value="{{ old('tFName', $teacher->tFName) }}" required>
                                            @error('tFName')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="tLName">Last Name</label>
                                            <input type="text" id="tLName" name="tLName" 
                                                class="form-control form-control-alternative @error('tLName') is-invalid @enderror" 
                                                placeholder="Last Name" 
                                                value="{{ old('tLName', $teacher->tLName) }}" required>
                                            @error('tLName')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label">Teacher Type</label>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="teacher_type_class" name="teacher_type" value="class_teacher"
                                                    class="custom-control-input"
                                                    {{ old('teacher_type', $teacher->teacher_type ?? 'class_teacher') === 'class_teacher' ? 'checked' : '' }} required>
                                                <label class="custom-control-label" for="teacher_type_class">Class Teacher</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="teacher_type_instructor" name="teacher_type" value="instructor"
                                                    class="custom-control-input"
                                                    {{ old('teacher_type', $teacher->teacher_type ?? '') === 'instructor' ? 'checked' : '' }} required>
                                                <label class="custom-control-label" for="teacher_type_instructor">Instructor (Sessions)</label>
                                            </div>
                                            @error('teacher_type')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <div class="custom-control custom-switch mt-4">
                                                <input type="checkbox" class="custom-control-input" id="Active" name="Active" value="1"
                                                    {{ old('Active', $teacher->Active) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="Active">Active Status</label>
                                            </div>
                                            @error('Active')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label class="form-control-label" for="tAddress">Address</label>
                                            <textarea id="tAddress" name="tAddress" rows="4"
                                                class="form-control form-control-alternative @error('tAddress') is-invalid @enderror" 
                                                placeholder="A few words about the address..." required>{{ old('tAddress', $teacher->tAddress) }}</textarea>
                                            @error('tAddress')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-lg-12">
                                        <button type="submit" class="btn btn-primary">
                                            {{ $isEdit ? 'Update Teacher' : 'Register Teacher' }}
                                        </button>
                                        <a href="{{ route('teachers.index') }}" class="btn btn-secondary">Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
