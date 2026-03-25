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
                                <h3 class="mb-0">{{ $isEdit ? 'Edit Student' : 'Register New Student' }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('students.index') }}" class="btn btn-sm btn-primary">Back to List</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <form action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @if($isEdit)
                                <input type="hidden" name="is_update" value="1">
                                <input type="hidden" name="student_id" value="{{ $student->AutoID }}">
                            @endif
                            
                            <h6 class="heading-small text-muted mb-4">Student Information</h6>
                            
                            <div class="pl-lg-4">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="fName">First Name</label>
                                            <input type="text" id="fName" name="fName" 
                                                class="form-control form-control-alternative @error('fName') is-invalid @enderror" 
                                                placeholder="First Name" 
                                                value="{{ old('fName', $student->fName) }}" required>
                                            @error('fName')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="lName">Last Name</label>
                                            <input type="text" id="lName" name="lName" 
                                                class="form-control form-control-alternative @error('lName') is-invalid @enderror" 
                                                placeholder="Last Name" 
                                                value="{{ old('lName', $student->lName) }}" required>
                                            @error('lName')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="mobileNo">Mobile Number</label>
                                            <input type="text" id="mobileNo" name="mobileNo"
                                                class="form-control form-control-alternative @error('mobileNo') is-invalid @enderror"
                                                placeholder="Mobile Number"
                                                value="{{ old('mobileNo', $student->mobileNo) }}" required>
                                            @error('mobileNo')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="studentemail">Email Address</label>
                                            <input type="email" id="studentemail" name="studentemail"
                                                class="form-control form-control-alternative @error('studentemail') is-invalid @enderror"
                                                placeholder="Email Address"
                                                value="{{ old('studentemail', $student->studentemail) }}" required>
                                            @error('studentemail')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="Age">Age</label>
                                            <input type="number" id="Age" name="Age"
                                                class="form-control form-control-alternative @error('Age') is-invalid @enderror"
                                                placeholder="Age" min="1" max="100"
                                                value="{{ old('Age', $student->Age) }}" required>
                                            @error('Age')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="studentpic">Student Picture</label>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input @error('studentpic') is-invalid @enderror"
                                                    id="studentpic" name="studentpic" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
                                                <label class="custom-file-label" for="studentpic">Choose image...</label>
                                            </div>
                                            <small class="form-text text-muted d-block mt-2">
                                                <i class="fas fa-info-circle"></i> Supported formats: JPEG, PNG, JPG, GIF, WebP (Max 5MB)
                                                @if($isEdit && $student->studentpic)
                                                    <br><strong>Current Picture:</strong> {{ $student->studentpic }}
                                                @endif
                                            </small>
                                            @error('studentpic')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label class="form-control-label" for="Address">Address</label>
                                            <textarea id="Address" name="Address" rows="4"
                                                class="form-control form-control-alternative @error('Address') is-invalid @enderror" 
                                                placeholder="A few words about the address..." required>{{ old('Address', $student->Address) }}</textarea>
                                            @error('Address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label">Status</label>
                                            <div class="custom-control custom-checkbox">
                                                <input type="hidden" name="Active" value="0">
                                                <input type="checkbox" id="Active" name="Active" value="1" 
                                                    class="custom-control-input" 
                                                    {{ old('Active', $student->Active) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="Active">Active Student</label>
                                            </div>
                                            @error('Active')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-lg-12">
                                        <button type="submit" class="btn btn-primary">
                                            {{ $isEdit ? 'Update Student' : 'Register Student' }}
                                        </button>
                                        <a href="{{ route('students.index') }}" class="btn btn-secondary">Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Handle file input label update
        document.getElementById('studentpic')?.addEventListener('change', function(e) {
            const fileName = e.target.files[0] ? e.target.files[0].name : 'Choose image...';
            document.querySelector('.custom-file-label').textContent = fileName;
        });
    </script>
@endsection
