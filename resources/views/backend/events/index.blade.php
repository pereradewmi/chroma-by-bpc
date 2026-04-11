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
                                <h3 class="mb-0">Events</h3>
                            </div>
                            <div class="col text-right">
                                <a href="{{ route('events.form') }}" class="btn btn-sm btn-primary">Add New Event</a>
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

                    <div class="table-responsive events-table-responsive">
                        <table class="table align-items-center table-flush events-table">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Event Image</th>
                                    <th scope="col">Event Name</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($events as $event)
                                    <tr>
                                        <td>{{ ($events->firstItem() ?? 1) + $loop->index }}</td>
                                        <td>
                                            <img src="{{ $event->getEventImage() }}" alt="Event Image" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                                        </td>
                                        <td><strong>{{ $event->eName }}</strong></td>
                                        <td>
                                            <small>{{ Str::limit($event->eDescription, 50) }}</small>
                                        </td>
                                        {{-- <td>
                                            <small>{{ $event->created_at->format('M d, Y') }}</small>
                                        </td> --}}
                                        <td class="event-actions-cell">
                                            <div class="event-action-group">
                                                <a href="{{ route('events.form', $event->eID) }}" class="btn btn-sm text-primary" title="Edit" aria-label="Edit">
                                                    <i class="fas fa-edit" aria-hidden="true"></i>
                                                </a>
                                                @if((int) $event->status !== 2)
                                                    <form action="{{ route('events.status', $event->eID) }}" method="POST" class="btn btn-sm text-primary event-toggle-form" title="Toggle Active/Inactive">
                                                        @csrf
                                                        <input type="hidden" name="status" value="{{ (int) $event->status === 1 ? 1 : 0 }}">
                                                        <label class="status-switch mb-0" for="event-status-{{ $event->eID }}">
                                                            <input
                                                                type="checkbox"
                                                                id="event-status-{{ $event->eID }}"
                                                                {{ (int) $event->status === 1 ? 'checked' : '' }}
                                                                onchange="this.form.querySelector('input[name=status]').value = this.checked ? 1 : 0; this.form.submit();"
                                                            >
                                                            <span class="status-slider"></span>
                                                        </label>
                                                    </form>
                                                @endif
                                                <form action="{{ route('events.status', $event->eID) }}" method="POST" class="event-delete-form" onsubmit="return confirm('Are you sure you want to delete this event?')">
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
                                        <td colspan="6" class="text-center text-muted py-5">
                                            <i class="fas fa-inbox fa-3x mb-3"></i><br>
                                            No events found. <a href="{{ route('events.form') }}">Create one now</a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($events->hasPages())
                        <div class="card-footer py-4">
                            {{ $events->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        .events-table {
            min-width: 1250px;
            width: 100%;
            white-space: nowrap;
            table-layout: auto;
        }

        /* .events-table-responsive {
            overflow-x: auto !important;
            overflow-y: hidden !important;
            width: 100%;
        } */

        .events-table th,
        .events-table td {
            white-space: nowrap;
            vertical-align: middle;
        }

        .events-table th:nth-child(1),
        .events-table td:nth-child(1) {
            width: 70px;
            max-width: 70px;
        }

        .events-table th:nth-child(2),
        .events-table td:nth-child(2) {
            width: 90px;
            max-width: 90px;
        }

        .events-table th:nth-child(5),
        .events-table td:nth-child(5) {
            width: 110px;
            max-width: 110px;
        }

        .events-table th:nth-child(3),
        .events-table td:nth-child(3) {
            width: 170px;
            max-width: 170px;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .events-table th:nth-child(4),
        .events-table td:nth-child(4) {
            width: 220px;
            max-width: 220px;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .event-actions-cell {
            white-space: nowrap;
            min-width: 220px;
        }

        .event-action-group {
            display: flex;
            align-items: center;
            flex-wrap: nowrap;
            gap: 4px;
            min-width: max-content;
        }

        .event-toggle-form {
            display: inline-flex;
            align-items: center;
            flex-shrink: 0;
        }

        .event-delete-form {
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
