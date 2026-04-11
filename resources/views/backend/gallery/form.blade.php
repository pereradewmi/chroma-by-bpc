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
                                <h3 class="mb-0">{{ $isEdit ? 'Edit Image' : 'Add New Image' }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('admin.images.index') }}" class="btn btn-sm btn-primary">Back to List</a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('admin.images.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @if($isEdit)
                                <input type="hidden" name="is_update" value="1">
                                <input type="hidden" name="image_id" value="{{ $image->id }}">
                            @endif

                            <div class="pl-lg-4">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="c_id">Image Category</label>
                                            <select id="c_id" name="c_id" class="form-control form-control-alternative @error('c_id') is-invalid @enderror" required>
                                                <option value="">Select category</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}" {{ (string) old('c_id', $image->c_id) === (string) $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('c_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="status">Status</label>
                                            <select id="status" name="status" class="form-control form-control-alternative @error('status') is-invalid @enderror" required>
                                                <option value="1" {{ (string) old('status', $image->status ?? 1) === '1' ? 'selected' : '' }}>Active</option>
                                                <option value="0" {{ (string) old('status', $image->status ?? 1) === '0' ? 'selected' : '' }}>Inactive</option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label class="form-control-label" for="image">Image File</label>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input @error('image') is-invalid @enderror" id="image" name="image" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp" {{ $isEdit ? '' : 'required' }}>
                                                <label class="custom-file-label" for="image">Choose image...</label>
                                            </div>

                                            @error('image')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                @if($isEdit && $image->image_path)
                                    <div class="row mb-4">
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label class="form-control-label">Current Image Preview</label>
                                                <div class="mt-2">
                                                    <img src="{{ Storage::url($image->image_path) }}" alt="Image Preview" style="max-width: 200px; max-height: 150px; object-fit: cover; border-radius: 4px;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="row mt-4">
                                    <div class="col-lg-12 text-right">
                                        <a href="{{ route('admin.images.index') }}" class="btn btn-secondary">
                                            Cancel
                                        </a>
                                        <button type="submit" class="btn btn-primary ml-2">
                                            {{ $isEdit ? 'Update Image' : 'Add Image' }}
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
        document.getElementById('image').addEventListener('change', function(e) {
            const fileName = e.target.files[0] ? e.target.files[0].name : 'Choose image...';
            document.querySelector('.custom-file-label').textContent = fileName;
        });
    </script>
@endsection
