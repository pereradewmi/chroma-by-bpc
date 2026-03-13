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
                                <h3 class="mb-0">{{ $isEdit ? 'Edit Event' : 'Create New Event' }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('events.index') }}" class="btn btn-sm btn-primary">Back to List</a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @if($isEdit)
                                <input type="hidden" name="is_update" value="1">
                                <input type="hidden" name="event_id" value="{{ $event->eID }}">
                            @endif

                            <h6 class="heading-small text-muted mb-4">Event Information</h6>

                            <div class="pl-lg-4">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="eName">Event Name</label>
                                            <input type="text" id="eName" name="eName"
                                                class="form-control form-control-alternative @error('eName') is-invalid @enderror"
                                                placeholder="Enter event name (e.g., Annual Science Fair, Summer Camp)"
                                                value="{{ old('eName', $event->eName) }}" required>
                                            @error('eName')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="eImage">Event Image</label>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input @error('eImage') is-invalid @enderror"
                                                    id="eImage" name="eImage" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
                                                <label class="custom-file-label" for="eImage">Choose image...</label>
                                            </div>
                                            <small class="form-text text-muted d-block mt-2">
                                                <i class="fas fa-info-circle"></i> Supported formats: JPEG, PNG, JPG, GIF, WebP (Max 5MB)
                                                @if($isEdit && $event->eImage)
                                                    <br><strong>Current Image:</strong> {{ $event->eImage }}
                                                @endif
                                                <br><strong>Note:</strong> If no image is uploaded, the Chroma logo will be used as default.
                                            </small>
                                            @error('eImage')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label class="form-control-label" for="eDescription">Description</label>
                                            <textarea id="eDescription" name="eDescription"
                                                class="form-control form-control-alternative @error('eDescription') is-invalid @enderror"
                                                placeholder="Enter event description..." rows="6">{{ old('eDescription', $event->eDescription) }}</textarea>
                                            @error('eDescription')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                @if($isEdit && $event->eImage && file_exists(storage_path('app/public/events/' . $event->eImage)))
                                    <div class="row mb-4">
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label class="form-control-label">Current Image Preview</label>
                                                <div class="mt-2">
                                                    <img src="{{ $event->getEventImage() }}" alt="Event Image" style="max-width: 200px; max-height: 150px; object-fit: cover; border-radius: 4px;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="row">
                                    <div class="col-lg-12">
                                        <button type="submit" class="btn btn-primary">
                                            {{ $isEdit ? 'Update Event' : 'Create Event' }}
                                        </button>
                                        <a href="{{ route('events.index') }}" class="btn btn-secondary">Cancel</a>
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
        document.getElementById('eImage').addEventListener('change', function(e) {
            const fileName = e.target.files[0] ? e.target.files[0].name : 'Choose image...';
            document.querySelector('.custom-file-label').textContent = fileName;
        });
    </script>
@endsection
