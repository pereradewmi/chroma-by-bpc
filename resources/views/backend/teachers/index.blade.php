@extends('backend.layouts.app')

@section('content')
    @include('backend.layouts.headers.cards')
    
    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-0">Teachers</h3>
                            </div>
                            <div class="col text-right">
                                <a href="{{ route('teachers.form') }}" class="btn btn-sm btn-primary">Add New Teacher</a>
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
                                    <th scope="col">ID</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Mobile Number</th>
                                    <th scope="col">Subject</th>
                                    <th scope="col">Address</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($teachers as $teacher)
                                    <tr>
                                        <td>{{ $teacher->id }}</td>
                                        <td>{{ $teacher->firstname }} {{ $teacher->lastname }}</td>
                                        <td>{{ $teacher->mobile_number }}</td>
                                        <td><span class="badge badge-primary">{{ $teacher->subject_name }}</span></td>
                                        <td>{{ Str::limit($teacher->address, 40) }}</td>
                                        <td>
                                            <a href="{{ route('teachers.form', $teacher->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                            <form action="{{ route('teachers.destroy', $teacher->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this teacher?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No teachers found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($teachers->hasPages())
                        <div class="card-footer py-4">
                            <nav class="d-flex justify-content-end" aria-label="...">
                                {{ $teachers->links() }}
                            </nav>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection