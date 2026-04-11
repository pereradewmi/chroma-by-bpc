@extends('frontend.components.layout')

@push('head-scripts')
<style>
    .register-page {
        background: linear-gradient(180deg, #f4f8fb 0%, #eef4f8 100%);
        min-height: calc(100vh - 140px);
    }

    .register-hero {
        padding: 3rem 0 1.5rem;
    }

    .register-card {
        border: 0;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 18px 40px rgba(4, 65, 95, 0.15);
    }

    .register-card .card-header {
        background: linear-gradient(135deg, #001f3f 0%, #003d82 100%);
        color: #fff;
        border: 0;
        padding: 1rem 1.25rem;
    }

    .register-card .form-label {
        font-weight: 600;
        color: #17324d;
    }

    .register-card .form-control,
    .register-card .form-select {
        border-radius: 10px;
        min-height: 46px;
    }

    .register-panel {
        background: linear-gradient(135deg, #001f3f 0%, #003d82 100%);
        color: #fff;
        border-radius: 18px;
        padding: 1.5rem;
        height: 100%;
        box-shadow: 0 18px 40px rgba(0, 31, 63, 0.16);
    }

    .register-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.45rem 0.8rem;
        background: rgba(255, 255, 255, 0.14);
        border-radius: 999px;
        margin-bottom: 1rem;
        font-size: 0.9rem;
    }

    .class-scroll {
        max-height: 220px;
        overflow-y: auto;
        border: 1px solid #e5ebf2;
        border-radius: 12px;
        padding: 0.75rem;
        background: #fff;
    }

    .class-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        padding: 0.7rem 0.75rem;
        border-radius: 10px;
        background: #f8fbff;
        margin-bottom: 0.5rem;
    }

    .class-item:last-child {
        margin-bottom: 0;
    }

    @media (max-width: 991.98px) {
        .register-hero {
            padding-top: 1.25rem;
        }
    }
</style>
@endpush

@section('title', 'Student Registration')

@section('main')
<main class="main register-page">
    <section class="register-hero">
        <div class="container py-4">
            <div class="row g-4 align-items-stretch">
                <div class="col-lg-4">
                    <div class="register-panel">
                        <div class="register-badge">
                            <i class="bi bi-mortarboard-fill"></i>
                            Student Registration
                        </div>
                        <h1 class="h3 mb-3">Join Chroma By BPC</h1>
                        <p class="mb-4">Register your student details online and select the classes you want to enroll in.</p>
                        <div class="mb-3">
                            <strong>What to prepare</strong>
                            <ul class="mt-2 mb-0 ps-3">
                                <li>Student contact details</li>
                                <li>Guardian information</li>
                                <li>A profile photo if available</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="card register-card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-person-plus-fill me-2"></i>Student Registration Form</h5>
                        </div>
                        <div class="card-body p-4 p-md-5">
                            @if (session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0 ps-3">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('frontend.register.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="Active" value="1">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label" for="fName">First Name <span class="text-danger">*</span></label>
                                        <input type="text" id="fName" name="fName" class="form-control @error('fName') is-invalid @enderror" value="{{ old('fName') }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="lName">Last Name <span class="text-danger">*</span></label>
                                        <input type="text" id="lName" name="lName" class="form-control @error('lName') is-invalid @enderror" value="{{ old('lName') }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="mobileNo">Mobile Number <span class="text-danger">*</span></label>
                                        <input type="text" id="mobileNo" name="mobileNo" class="form-control @error('mobileNo') is-invalid @enderror" value="{{ old('mobileNo') }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="studentemail">Email Address <span class="text-danger">*</span></label>
                                        <input type="email" id="studentemail" name="studentemail" class="form-control @error('studentemail') is-invalid @enderror" value="{{ old('studentemail') }}" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label" for="Age">Age <span class="text-danger">*</span></label>
                                        <input type="number" id="Age" name="Age" class="form-control @error('Age') is-invalid @enderror" min="1" max="100" value="{{ old('Age') }}" required>
                                    </div>
                                    <div class="col-md-8">
                                        <label class="form-label" for="Address">Address <span class="text-danger">*</span></label>
                                        <textarea id="Address" name="Address" rows="1" class="form-control @error('Address') is-invalid @enderror" required>{{ old('Address') }}</textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="guardian_name">Guardian Name <span class="text-danger">*</span></label>
                                        <input type="text" id="guardian_name" name="guardian_name" class="form-control @error('guardian_name') is-invalid @enderror" value="{{ old('guardian_name') }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="guardian_phone">Guardian Phone <span class="text-danger">*</span></label>
                                        <input type="text" id="guardian_phone" name="guardian_phone" class="form-control @error('guardian_phone') is-invalid @enderror" value="{{ old('guardian_phone') }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="studentpic">Student Picture</label>
                                        <input type="file" id="studentpic" name="studentpic" class="form-control @error('studentpic') is-invalid @enderror" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Select Classes</label>
                                        <div class="class-scroll">
                                            @forelse($classes as $class)
                                                <label class="class-item mb-0">
                                                    <span>
                                                        <strong>{{ $class->cName }}</strong>
                                                        <span class="d-block small text-muted">Rs. {{ number_format($class->classfee ?? 0, 2) }}</span>
                                                    </span>
                                                    <input type="checkbox" name="class_ids[]" value="{{ $class->cID }}" class="form-check-input m-0">
                                                </label>
                                            @empty
                                                <div class="text-muted">No classes available right now.</div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex flex-wrap gap-2 justify-content-end mt-4">
                                    <a href="{{ route('home') }}" class="btn btn-outline-secondary px-4">Cancel</a>
                                    <button type="submit" class="btn btn-primary px-4" style="background: linear-gradient(135deg, #001f3f 0%, #003d82 100%); border: none;">
                                        <i class="bi bi-check2-circle me-2"></i>Register Student
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection