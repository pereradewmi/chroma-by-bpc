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

                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Session Image</th>
                                    <th scope="col">Session Name</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Created Date</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sessions as $session)
                                    <tr>
                                        <td>{{ $session->sID }}</td>
                                        <td>
                                            <img src="{{ $session->getSessionImage() }}" alt="Session Image" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                                        </td>
                                        <td><strong>{{ $session->sName }}</strong></td>
                                        <td>
                                            <small>{{ Str::limit($session->sDescription, 50) }}</small>
                                        </td>
                                        <td>{{ $session->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <a href="{{ route('sessions.form', $session->sID) }}" class="btn btn-sm btn-primary">Edit</a>
                                            <form action="{{ route('sessions.destroy', $session->sID) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this session?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No sessions found.</td>
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
@endsection