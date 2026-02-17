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
                                <h3 class="mb-0">{{ $isEdit ? 'Edit Student' : 'Register New Student' }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('students.index') }}" class="btn btn-sm btn-primary">Back to List</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <form action="{{ route('students.store') }}" method="POST">
                            @csrf
                            @if($isEdit)
                                <input type="hidden" name="is_update" value="1">
                                <input type="hidden" name="student_id" value="{{ $student->id }}">
                            @endif
                            
                            <h6 class="heading-small text-muted mb-4">Student Information</h6>
                            
                            <div class="pl-lg-4">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="firstname">First Name</label>
                                            <input type="text" id="firstname" name="firstname" 
                                                class="form-control form-control-alternative @error('firstname') is-invalid @enderror" 
                                                placeholder="First Name" 
                                                value="{{ old('firstname', $student->firstname) }}" required>
                                            @error('firstname')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="lastname">Last Name</label>
                                            <input type="text" id="lastname" name="lastname" 
                                                class="form-control form-control-alternative @error('lastname') is-invalid @enderror" 
                                                placeholder="Last Name" 
                                                value="{{ old('lastname', $student->lastname) }}" required>
                                            @error('lastname')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="mobile_number">Mobile Number</label>
                                            <input type="text" id="mobile_number" name="mobile_number" 
                                                class="form-control form-control-alternative @error('mobile_number') is-invalid @enderror" 
                                                placeholder="Mobile Number" 
                                                value="{{ old('mobile_number', $student->mobile_number) }}" required>
                                            @error('mobile_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="age">Age</label>
                                            <input type="number" id="age" name="age" 
                                                class="form-control form-control-alternative @error('age') is-invalid @enderror" 
                                                placeholder="Age" min="1" max="100"
                                                value="{{ old('age', $student->age) }}" required>
                                            @error('age')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label class="form-control-label" for="address">Address</label>
                                            <textarea id="address" name="address" rows="4"
                                                class="form-control form-control-alternative @error('address') is-invalid @enderror" 
                                                placeholder="A few words about the address..." required>{{ old('address', $student->address) }}</textarea>
                                            @error('address')
                                                <div class="invalid-feedback">{{ $message }}</div>
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
@endsection