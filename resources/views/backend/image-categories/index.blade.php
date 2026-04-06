@extends('backend.layouts.app')

@section('content')
    @include('backend.layouts.headers.cards')

    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-0">Image Categories</h3>
                            </div>
                            <div class="col text-right">
                                <a href="{{ route('admin.image-categories.form') }}" class="btn btn-sm btn-primary">Add Category</a>
                            </div>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mx-3" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $category)
                                    <tr>
                                        <td>{{ ($categories->firstItem() ?? 1) + $loop->index }}</td>
                                        <td><strong>{{ $category->name }}</strong></td>
                                        <td>
                                            @if($category->status == 1)
                                                <span class="badge badge-success">Active</span>
                                            @elseif($category->status == 0)
                                                <span class="badge badge-warning">Inactive</span>
                                            @else
                                                <span class="badge badge-danger">Deleted</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.image-categories.form', $category->id) }}" class="btn btn-sm text-primary" title="Edit" aria-label="Edit">
                                                <i class="fas fa-edit" aria-hidden="true"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No image categories found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($categories->hasPages())
                        <div class="card-footer py-4">
                            <nav class="d-flex justify-content-end" aria-label="...">
                                {{ $categories->links() }}
                            </nav>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
