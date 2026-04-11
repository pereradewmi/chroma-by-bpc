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
                                                @if($isEdit && $event->eImage)
                                                    <br><strong>Current Image:</strong> {{ $event->eImage }}
                                                @endif
                                              
                                            </small>
                                            @error('eImage')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="dateFrom">Event Start Date</label>
                                            <input type="datetime-local" id="dateFrom" name="dateFrom"
                                                class="form-control form-control-alternative @error('dateFrom') is-invalid @enderror"
                                                value="{{ old('dateFrom', $event->dateFrom ? $event->dateFrom->format('Y-m-d\TH:i') : '') }}" required>
                                            @error('dateFrom')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="dateTo">Event End Date</label>
                                            <input type="datetime-local" id="dateTo" name="dateTo"
                                                class="form-control form-control-alternative @error('dateTo') is-invalid @enderror"
                                                value="{{ old('dateTo', $event->dateTo ? $event->dateTo->format('Y-m-d\TH:i') : '') }}" required>
                                            @error('dateTo')
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

                                <div class="row mt-4 align-items-center">
                                    <div class="col-lg-4">
                                        <div class="form-group mb-0">
                                            <label class="form-control-label">Status</label>
                                            <div class="custom-control custom-checkbox">
                                                <input type="hidden" name="status" value="0">
                                                <input type="checkbox" id="status" name="status" value="1"
                                                    class="custom-control-input"
                                                    {{ old('status', $isEdit ? $event->status : 1) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="status">Active Event</label>
                                            </div>
                                            @error('status')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-8 text-right">
                                        <a href="{{ route('events.index') }}" class="btn btn-secondary">
                                            Cancel
                                        </a>
                                        <button type="submit" class="btn btn-primary ml-2">
                                            {{ $isEdit ? 'Update Event' : 'Create Event' }}
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

    <script>
        // Handle file input label update
        document.getElementById('eImage').addEventListener('change', function(e) {
            const fileName = e.target.files[0] ? e.target.files[0].name : 'Choose image...';
            document.querySelector('.custom-file-label').textContent = fileName;
        });
    </script>
@endsection
