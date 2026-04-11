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
                                <h3 class="mb-0">Classes</h3>
                            </div>
                            <div class="col text-right">
                                <a href="{{ route('classes.form') }}" class="btn btn-sm btn-primary">Add New Class</a>
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
                                    <th scope="col">Class Name</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($classes as $class)
                                    <tr>
                                        <td>{{ ($classes->firstItem() ?? 1) + $loop->index }}</td>
                                        <td><strong>{{ $class->cName }}</strong></td>
                                        <td>
                                            <small>{{ Str::limit(strip_tags($class->cDescription), 50) }}</small>
                                        </td>
                                        {{-- <td>{{ $class->created_at->format('M d, Y') }}</td> --}}
                                        <td class="class-actions-cell">
                                            <div class="class-action-group">
                                                <a href="{{ route('classes.form', $class->cID) }}" class="btn btn-sm text-primary" title="Edit" aria-label="Edit">
                                                    <i class="fas fa-edit" aria-hidden="true"></i>
                                                </a>
                                                @if((int) ($class->status ?? 1) !== 2)
                                                    <form action="{{ route('classes.status', $class->cID) }}" method="POST" class="btn btn-sm text-primary class-toggle-form" title="Toggle Active/Inactive">
                                                        @csrf
                                                        <input type="hidden" name="status" value="{{ (int) ($class->status ?? 1) === 1 ? 1 : 0 }}">
                                                        <label class="status-switch mb-0" for="class-status-{{ $class->cID }}">
                                                            <input
                                                                type="checkbox"
                                                                id="class-status-{{ $class->cID }}"
                                                                {{ (int) ($class->status ?? 1) === 1 ? 'checked' : '' }}
                                                                onchange="this.form.querySelector('input[name=status]').value = this.checked ? 1 : 0; this.form.submit();"
                                                            >
                                                            <span class="status-slider"></span>
                                                        </label>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No classes found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($classes->hasPages())
                        <div class="card-footer py-4">
                            <nav class="d-flex justify-content-end" aria-label="...">
                                {{ $classes->links() }}
                            </nav>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        .class-actions-cell {
            white-space: nowrap;
            min-width: 220px;
        }

        .class-action-group {
            display: flex;
            align-items: center;
            flex-wrap: nowrap;
            gap: 4px;
            min-width: max-content;
        }

        .class-toggle-form {
            display: inline-flex;
            align-items: center;
            flex-shrink: 0;
        }

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
