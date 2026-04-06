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
                                <h3 class="mb-0">Sessions</h3>
                            </div>
                            <div class="col text-right">
                                <a href="{{ route('sessions.form') }}" class="btn btn-sm btn-primary">Add New Session</a>
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

                    <div class="table-responsive sessions-table-responsive" style="width: 100%; overflow-x: auto !important; overflow-y: hidden; display: block; -webkit-overflow-scrolling: touch;">
                        <table class="table align-items-center table-flush sessions-table">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Session Image</th>
                                    <th scope="col">Session Name</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Status</th>
                                    {{-- <th scope="col">Created Date</th> --}}
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sessions as $session)
                                    <tr>
                                        <td>{{ ($sessions->firstItem() ?? 1) + $loop->index }}</td>
                                        <td>
                                            <img src="{{ $session->getSessionImage() }}" alt="Session Image" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                                        </td>
                                        <td><strong>{{ $session->sName }}</strong></td>
                                        <td>
                                            <small>{{ Str::limit($session->sDescription, 50) }}</small>
                                        </td>
                                        <td>
                                            @if((int) ($session->status ?? 1) === 1)
                                                <span class="badge badge-success">Active</span>
                                            @elseif((int) ($session->status ?? 1) === 0)
                                                <span class="badge badge-secondary">Inactive</span>
                                            @else
                                                <span class="badge badge-danger">Deleted</span>
                                            @endif
                                        </td>
                                        {{-- <td>{{ $session->created_at->format('M d, Y') }}</td> --}}
                                        <td class="session-actions-cell">
                                            <div class="session-action-group">
                                                <a href="{{ route('sessions.form', $session->sID) }}" class="btn btn-sm text-primary" title="Edit" aria-label="Edit">
                                                    <i class="fas fa-edit" aria-hidden="true"></i>
                                                </a>
                                                @if((int) ($session->status ?? 1) !== 2)
                                                    <form action="{{ route('sessions.status', $session->sID) }}" method="POST" class="btn btn-sm text-primary session-toggle-form" title="Toggle Active/Inactive">
                                                        @csrf
                                                        <input type="hidden" name="status" value="{{ (int) ($session->status ?? 1) === 1 ? 1 : 0 }}">
                                                        <label class="status-switch mb-0" for="session-status-{{ $session->sID }}">
                                                            <input
                                                                type="checkbox"
                                                                id="session-status-{{ $session->sID }}"
                                                                {{ (int) ($session->status ?? 1) === 1 ? 'checked' : '' }}
                                                                onchange="this.form.querySelector('input[name=status]').value = this.checked ? 1 : 0; this.form.submit();"
                                                            >
                                                            <span class="status-slider"></span>
                                                        </label>
                                                    </form>
                                                @endif
                                                <form action="{{ route('sessions.status', $session->sID) }}" method="POST" class="session-delete-form" onsubmit="return confirm('Are you sure you want to delete this session?')">
                                                    @csrf
                                                    <input type="hidden" name="status" value="2">
                                                    <button type="submit" class="btn btn-sm text-primary bg-white border-0" title="Delete" aria-label="Delete">
                                                        <i class="fas fa-trash" aria-hidden="true"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No sessions found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($sessions->hasPages())
                        <div class="card-footer py-4">
                            <nav class="d-flex justify-content-end" aria-label="...">
                                {{ $sessions->links() }}
                            </nav>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        .sessions-table {
            min-width: 1250px;
            width: 100%;
            white-space: nowrap;
            table-layout: auto;
        }

        .sessions-table-responsive {
            overflow-x: auto !important;
            overflow-y: hidden;
            width: 100%;
            display: block;
        }

        .sessions-table th,
        .sessions-table td {
            white-space: nowrap;
            vertical-align: middle;
        }

        .session-actions-cell {
            white-space: nowrap;
            min-width: 220px;
        }

        .session-action-group {
            display: flex;
            align-items: center;
            flex-wrap: nowrap;
            gap: 4px;
            min-width: max-content;
        }

        .session-toggle-form {
            display: inline-flex;
            align-items: center;
            flex-shrink: 0;
        }

        .session-delete-form {
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
