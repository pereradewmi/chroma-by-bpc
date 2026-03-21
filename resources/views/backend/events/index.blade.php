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

                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Event Image</th>
                                    <th scope="col">Event Name</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Created Date</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($events as $event)
                                    <tr>
                                        <td>{{ $event->eID }}</td>
                                        <td>
                                            <img src="{{ $event->getEventImage() }}" alt="Event Image" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                                        </td>
                                        <td><strong>{{ $event->eName }}</strong></td>
                                        <td>
                                            <small>{{ Str::limit($event->eDescription, 50) }}</small>
                                        </td>
                                        <td>
                                            <small>{{ $event->created_at->format('M d, Y') }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('events.form', $event->eID) }}" class="btn btn-sm btn-primary" title="Edit" aria-label="Edit">
                                                    <i class="fas fa-edit" aria-hidden="true"></i>
                                                </a>
                                                <form action="{{ route('events.destroy', $event->eID) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')" title="Delete" aria-label="Delete">
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
@endsection
