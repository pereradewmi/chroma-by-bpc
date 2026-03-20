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
                                <h3 class="mb-0">{{ $isEdit ? 'Edit Session' : 'Create New Session' }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('sessions.index') }}" class="btn btn-sm btn-primary">Back to List</a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('sessions.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @if($isEdit)
                                <input type="hidden" name="is_update" value="1">
                                <input type="hidden" name="session_id" value="{{ $session->sID }}">
                            @endif

                            <h6 class="heading-small text-muted mb-4">Session Information</h6>

                            <div class="pl-lg-4">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="sName">Session Name</label>
                                            <input type="text" id="sName" name="sName"
                                                class="form-control form-control-alternative @error('sName') is-invalid @enderror"
                                                placeholder="Enter session name (e.g., Morning Session, Advanced Training)"
                                                value="{{ old('sName', $session->sName) }}" required>
                                            @error('sName')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="sImage">Session Image</label>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input @error('sImage') is-invalid @enderror"
                                                    id="sImage" name="sImage" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
                                                <label class="custom-file-label" for="sImage">Choose image...</label>
                                            </div>
                                            <small class="form-text text-muted d-block mt-2">
                                                <i class="fas fa-info-circle"></i> Supported formats: JPEG, PNG, JPG, GIF, WebP (Max 5MB)
                                                @if($isEdit && $session->sImage)
                                                    <br><strong>Current Image:</strong> {{ $session->sImage }}
                                                @endif
                                                <br><strong>Note:</strong> If no image is uploaded, the Chroma logo will be used as default.
                                            </small>
                                            @error('sImage')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label class="form-control-label" for="sDescription">Description</label>
                                            <textarea id="sDescription" name="sDescription"
                                                class="form-control form-control-alternative @error('sDescription') is-invalid @enderror"
                                                placeholder="Enter session description..." rows="6">{{ old('sDescription', $session->sDescription) }}</textarea>
                                            @error('sDescription')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                @if($isEdit && $session->sImage && file_exists(storage_path('app/public/sessions/' . $session->sImage)))
                                    <div class="row mb-4">
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label class="form-control-label">Current Image Preview</label>
                                                <div class="mt-2">
                                                    <img src="{{ $session->getSessionImage() }}" alt="Session Image" style="max-width: 200px; max-height: 150px; object-fit: cover; border-radius: 4px;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="row">
                                    <div class="col-lg-12">
                                        <button type="submit" class="btn btn-primary">
                                            {{ $isEdit ? 'Update Session' : 'Create Session' }}
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

    <script>
        // Handle file input label update
        document.getElementById('sImage').addEventListener('change', function(e) {
            const fileName = e.target.files[0] ? e.target.files[0].name : 'Choose image...';
            document.querySelector('.custom-file-label').textContent = fileName;
        });
    </script>
@endsection
