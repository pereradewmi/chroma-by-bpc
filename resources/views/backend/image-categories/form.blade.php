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
                                <h3 class="mb-0">{{ $isEdit ? 'Edit Image Category' : 'Create Image Category' }}</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('admin.image-categories.index') }}" class="btn btn-sm btn-primary">Back to List</a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('admin.image-categories.store') }}" method="POST">
                            @csrf
                            @if($isEdit)
                                <input type="hidden" name="is_update" value="1">
                                <input type="hidden" name="category_id" value="{{ $category->id }}">
                            @endif

                            <h6 class="heading-small text-muted mb-4">Category Information</h6>

                            <div class="pl-lg-4">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="name">Category Name</label>
                                            <input type="text" id="name" name="name"
                                                class="form-control form-control-alternative @error('name') is-invalid @enderror"
                                                placeholder="Enter category name"
                                                value="{{ old('name', $category->name) }}" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="status">Status</label>
                                            <select id="status" name="status" class="form-control form-control-alternative @error('status') is-invalid @enderror" required>
                                                <option value="1" {{ (string) old('status', $category->status ?? 1) === '1' ? 'selected' : '' }}>Active</option>
                                                <option value="0" {{ (string) old('status', $category->status ?? 1) === '0' ? 'selected' : '' }}>Inactive</option>
                                                <option value="2" {{ (string) old('status', $category->status ?? 1) === '2' ? 'selected' : '' }}>Deleted</option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <button type="submit" class="btn btn-primary">
                                            {{ $isEdit ? 'Update Category' : 'Create Category' }}
                                        </button>
                                        <a href="{{ route('admin.image-categories.index') }}" class="btn btn-secondary">Cancel</a>
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
