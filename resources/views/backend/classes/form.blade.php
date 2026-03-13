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
                                <h3 class="mb-0">{{ $isEdit ? 'Edit Class' : 'Create New Class' }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('classes.index') }}" class="btn btn-sm btn-primary">Back to List</a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('classes.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @if($isEdit)
                                <input type="hidden" name="is_update" value="1">
                                <input type="hidden" name="class_id" value="{{ $class->cID }}">
                            @endif

                            <h6 class="heading-small text-muted mb-4">Class Information</h6>

                            <div class="pl-lg-4">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="cName">Class Name</label>
                                            <input type="text" id="cName" name="cName"
                                                class="form-control form-control-alternative @error('cName') is-invalid @enderror"
                                                placeholder="Enter class name (e.g., Grade 10A, Mathematics Advanced)"
                                                value="{{ old('cName', $class->cName) }}" required>
                                            @error('cName')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="cImage">Class Image</label>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input @error('cImage') is-invalid @enderror"
                                                    id="cImage" name="cImage" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
                                                <label class="custom-file-label" for="cImage">Choose image...</label>
                                            </div>
                                            <small class="form-text text-muted d-block mt-2">
                                                <i class="fas fa-info-circle"></i> Supported formats: JPEG, PNG, JPG, GIF, WebP (Max 5MB)
                                                @if($isEdit && $class->cImage)
                                                    <br><strong>Current Image:</strong> {{ $class->cImage }}
                                                @endif
                                                <br><strong>Note:</strong> If no image is uploaded, the Chroma logo will be used as default.
                                            </small>
                                            @error('cImage')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label class="form-control-label" for="cDescription">Description</label>
                                            <textarea id="cDescription" name="cDescription"
                                                class="form-control form-control-alternative @error('cDescription') is-invalid @enderror"
                                                placeholder="Enter class description..." rows="6">{{ old('cDescription', $class->cDescription) }}</textarea>
                                            @error('cDescription')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                @if($isEdit && $class->cImage && file_exists(storage_path('app/public/classes/' . $class->cImage)))
                                    <div class="row mb-4">
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label class="form-control-label">Current Image Preview</label>
                                                <div class="mt-2">
                                                    <img src="{{ $class->getClassImage() }}" alt="Class Image" style="max-width: 200px; max-height: 150px; object-fit: cover; border-radius: 4px;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="row">
                                    <div class="col-lg-12">
                                        <button type="submit" class="btn btn-primary">
                                            {{ $isEdit ? 'Update Class' : 'Create Class' }}
                                        </button>
                                        <a href="{{ route('classes.index') }}" class="btn btn-secondary">Cancel</a>
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
        document.getElementById('cImage').addEventListener('change', function(e) {
            const fileName = e.target.files[0] ? e.target.files[0].name : 'Choose image...';
            document.querySelector('.custom-file-label').textContent = fileName;
        });
    </script>
@endsection