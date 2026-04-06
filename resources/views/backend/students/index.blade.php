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
                                <h3 class="mb-0">Students</h3>
                            </div>
                            <div class="col text-right">
                                <a href="{{ route('students.form') }}" class="btn btn-sm btn-primary">Add New Student</a>
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
                                    <th scope="col">Mobile Number</th>
                                    {{-- <th scope="col">Age</th> --}}
                                    {{-- <th scope="col">Address</th> --}}
                                    <th scope="col">Status</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($students as $student)
                                    <tr>
                                        <td>{{ ($students->firstItem() ?? 1) + $loop->index }}</td>
                                        <td>{{ $student->fName }} {{ $student->lName }}</td>
                                        {{-- <td>{{ $student->mobileNo }}</td> --}}
                                        {{-- <td>{{ $student->Age }}</td> --}}
                                        <td>{{ Str::limit($student->Address, 50) }}</td>
                                        <td>
                                            @if((int) $student->Active === 1)
                                                <span class="badge badge-success">Active</span>
                                            @elseif((int) $student->Active === 0)
                                                <span class="badge badge-secondary">Inactive</span>
                                            @else
                                                <span class="badge badge-danger">Deleted</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('students.form', $student->AutoID) }}" class="btn btn-sm text-primary" title="Edit" aria-label="Edit">
                                                <i class="fas fa-edit" aria-hidden="true"></i>
                                            </a>
                                            @if((int) $student->Active !== 2)
                                                <form action="{{ route('students.status', $student->AutoID) }}" method="POST" class="btn btn-sm text-primary d-inline-block align-middle ml-1" title="Toggle Active/Inactive">
                                                    @csrf
                                                    <input type="hidden" name="status" value="{{ (int) $student->Active === 1 ? 1 : 0 }}">
                                                    <label class="status-switch mb-0" for="student-status-{{ $student->AutoID }}">
                                                        <input
                                                            type="checkbox"
                                                            id="student-status-{{ $student->AutoID }}"
                                                            {{ (int) $student->Active === 1 ? 'checked' : '' }}
                                                            onchange="this.form.querySelector('input[name=status]').value = this.checked ? 1 : 0; this.form.submit();"
                                                        >
                                                        <span class="status-slider"></span>
                                                    </label>
                                                </form>
                                            @endif
                                            <form action="{{ route('students.status', $student->AutoID) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this student?')">
                                                @csrf
                                                <input type="hidden" name="status" value="2">
                                                <button type="submit" class="btn btn-sm text-primary bg-white border-0" title="Delete" aria-label="Delete">
                                                    <i class="fas fa-trash" aria-hidden="true"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No students found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($students->hasPages())
                        <div class="card-footer py-4">
                            <nav class="d-flex justify-content-end" aria-label="...">
                                {{ $students->links() }}
                            </nav>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        .status-switch {
            position: relative;
            display: inline-block;
            width: 24px;
            height: 14px;
            vertical-align: middle;
        }

        .status-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .status-slider {
            position: absolute;
            cursor: pointer;
            inset: 0;
            background-color: #e9ecf3;
            transition: 0.2s;
            border-radius: 22px;
            border: 1px solid #d2d8e5;
        }

        .status-slider:before {
            position: absolute;
            content: "";
            height: 10px;
            width: 10px;
            left: 2px;
            top: 2px;
            background-color: #fff;
            transition: 0.2s;
            border-radius: 50%;
        }

        .status-switch input:checked + .status-slider {
            background-color: #04415f;
            border-color: #04415f;
        }

        .status-switch input:checked + .status-slider:before {
            transform: translateX(10px);
        }
    </style>
@endsection
