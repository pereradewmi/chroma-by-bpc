@extends('backend.layouts.app')

@section('content')
    @include('backend.layouts.headers.cards')
    
    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">{{ $isEdit ? 'Edit Session' : 'Register New Session' }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('sessions.index') }}" class="btn btn-sm btn-primary">Back to List</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <form action="{{ route('sessions.store') }}" method="POST">
                            @csrf
                            @if($isEdit)
                                <input type="hidden" name="is_update" value="1">
                                <input type="hidden" name="session_id" value="{{ $session->id }}">
                            @endif
                            
                            <h6 class="heading-small text-muted mb-4">Session Information</h6>
                            
                            <div class="pl-lg-4">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="session_name">Session Name</label>
                                            <input type="text" id="session_name" name="session_name" 
                                                class="form-control form-control-alternative @error('session_name') is-invalid @enderror" 
                                                placeholder="Enter session name (e.g., Morning Session, Advanced Mathematics)" 
                                                value="{{ old('session_name', $session->session_name) }}" required>
                                            @error('session_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="teacher_id">Teacher</label>
                                            <select id="teacher_id" name="teacher_id" 
                                                class="form-control form-control-alternative @error('teacher_id') is-invalid @enderror" required>
                                                <option value="">Select a Teacher</option>
                                                @foreach($teachers as $teacher)
                                                    <option value="{{ $teacher->id }}" 
                                                        {{ (old('teacher_id', $session->teacher_id) == $teacher->id) ? 'selected' : '' }}>
                                                        {{ $teacher->firstname }} {{ $teacher->lastname }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('teacher_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                @if(!empty($teachers->toArray()))
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <div class="alert alert-info">
                                                    <strong>Available Teachers:</strong>
                                                    <ul class="mb-0 mt-2">
                                                        @foreach($teachers as $teacher)
                                                            <li>{{ $teacher->firstname }} {{ $teacher->lastname }} - <em>{{ $teacher->subject_name }}</em></li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <div class="alert alert-warning">
                                                    <strong>No teachers available!</strong> 
                                                    <a href="{{ route('teachers.form') }}" class="alert-link">Please register teachers first</a> before creating sessions.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                
                                <div class="row">
                                    <div class="col-lg-12">
                                        <button type="submit" class="btn btn-primary" {{ empty($teachers->toArray()) ? 'disabled' : '' }}>
                                            {{ $isEdit ? 'Update Session' : 'Register Session' }}
                                        </button>
                                        <a href="{{ route('sessions.index') }}" class="btn btn-secondary">Cancel</a>
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