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

                            <div class="pl-lg-4 teacher-form-compact">
                                <div class="row">
                                    <div class="col-lg-4">
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
                                    <div class="col-lg-4">
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
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-control-label" for="tMobileNo">Mobile Number</label>
                                            <input type="text" id="tMobileNo" name="tMobileNo"
                                                class="form-control form-control-alternative @error('tMobileNo') is-invalid @enderror"
                                                placeholder="Mobile Number"
                                                value="{{ old('tMobileNo', $teacher->tMobileNo) }}" required>
                                            @error('tMobileNo')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group mb-3">
                                            <label class="form-control-label" for="tAddress">Address</label>
                                            <textarea id="tAddress" name="tAddress" rows="2"
                                                class="form-control form-control-alternative @error('tAddress') is-invalid @enderror"
                                                placeholder="Address" required>{{ old('tAddress', $teacher->tAddress) }}</textarea>
                                            @error('tAddress')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group mb-2">
                                            <label class="form-control-label">Teacher Type</label>
                                            <div class="d-flex align-items-center">
                                                <div class="custom-control custom-radio custom-control-inline mr-3">
                                                    <input type="radio" id="teacher_type_class" name="teacherType" value="class_teacher"
                                                        class="custom-control-input"
                                                        {{ old('teacherType', $teacher->teacherType ?? 'class_teacher') === 'class_teacher' ? 'checked' : '' }} required>
                                                    <label class="custom-control-label" for="teacher_type_class">Class Teacher</label>
                                                </div>
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" id="teacher_type_instructor" name="teacherType" value="instructor"
                                                        class="custom-control-input"
                                                        {{ old('teacherType', $teacher->teacherType ?? '') === 'instructor' ? 'checked' : '' }} required>
                                                    <label class="custom-control-label" for="teacher_type_instructor">Instructor (Sessions)</label>
                                                </div>
                                            </div>
                                            @error('teacherType')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4 align-items-center">
                                    <div class="col-lg-4">
                                        <div class="form-group mb-0">
                                            <label class="form-control-label">Status</label>
                                            <div class="custom-control custom-checkbox">
                                                <input type="hidden" name="Active" value="0">
                                                <input type="checkbox" id="Active" name="Active" value="1"
                                                    class="custom-control-input"
                                                    {{ old('Active', $isEdit ? $teacher->Active : 1) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="Active">Active Teacher</label>
                                            </div>
                                            @error('Active')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-8 text-right">
                                        <a href="{{ route('teachers.index') }}" class="btn btn-secondary">
                                            Cancel
                                        </a>
                                        <button type="submit" class="btn btn-primary ml-2">
                                            {{ $isEdit ? 'Update Teacher' : 'Register Teacher' }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <style>
        .teacher-form-compact .form-group {
            margin-bottom: 0.75rem;
        }

        .teacher-form-compact .heading-small {
            margin-bottom: 1rem !important;
        }
    </style>
@endsection
