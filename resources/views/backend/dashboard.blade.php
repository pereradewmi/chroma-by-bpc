@extends('backend.layouts.app')

@section('content')
    @include('backend.layouts.headers.cards')
    
    <div class="container-fluid mt--7">
        <!-- School Management Header -->
        <div class="row mb-4">
            <div class="col">
                <div class="card shadow">
                    <div class="card-header border-0">
                        <h3 class="mb-0">School Management System</h3>
                        <p class="text-muted mb-0">Manage students, teachers, classes, and sessions</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Management Cards -->
        <div class="row mb-4">
            <!-- Students Card -->
            <div class="col-xl-3 col-lg-6">
                <div class="card card-stats mb-4 mb-xl-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase text-muted mb-0">Students</h5>
                                <span class="h2 font-weight-bold mb-0">24</span>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                                    <i class="fas fa-user-graduate"></i>
                                </div>
                            </div>
                        </div>
                        <p class="mt-3 mb-0 text-muted text-sm">
                            <a href="{{ route('students.index') }}" class="btn btn-sm btn-outline-primary mr-2">View All</a>
                            <a href="{{ route('students.form') }}" class="btn btn-sm btn-primary">Add New</a>
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Teachers Card -->
            <div class="col-xl-3 col-lg-6">
                <div class="card card-stats mb-4 mb-xl-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase text-muted mb-0">Teachers</h5>
                                <span class="h2 font-weight-bold mb-0">12</span>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                    <i class="fas fa-chalkboard-teacher"></i>
                                </div>
                            </div>
                        </div>
                        <p class="mt-3 mb-0 text-muted text-sm">
                            <a href="{{ route('teachers.index') }}" class="btn btn-sm btn-outline-primary mr-2">View All</a>
                            <a href="{{ route('teachers.form') }}" class="btn btn-sm btn-primary">Add New</a>
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Classes Card -->
            <div class="col-xl-3 col-lg-6">
                <div class="card card-stats mb-4 mb-xl-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase text-muted mb-0">Classes</h5>
                                <span class="h2 font-weight-bold mb-0">8</span>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                                    <i class="fas fa-school"></i>
                                </div>
                            </div>
                        </div>
                        <p class="mt-3 mb-0 text-muted text-sm">
                            <a href="{{ route('classes.index') }}" class="btn btn-sm btn-outline-primary mr-2">View All</a>
                            <a href="{{ route('classes.form') }}" class="btn btn-sm btn-primary">Add New</a>
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Sessions Card -->
            <div class="col-xl-3 col-lg-6">
                <div class="card card-stats mb-4 mb-xl-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h5 class="card-title text-uppercase text-muted mb-0">Sessions</h5>
                                <span class="h2 font-weight-bold mb-0">16</span>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape bg-success text-white rounded-circle shadow">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                            </div>
                        </div>
                        <p class="mt-3 mb-0 text-muted text-sm">
                            <a href="{{ route('sessions.index') }}" class="btn btn-sm btn-outline-primary mr-2">View All</a>
                            <a href="{{ route('sessions.form') }}" class="btn btn-sm btn-primary">Add New</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Welcome Content -->
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-0">Welcome to CROMA School Management</h3>
                                <p class="text-muted mb-0">Professional school management made simple</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <div class="alert alert-info">
                            <h4 class="alert-heading">Getting Started</h4>
                            <p>Click on any management card above to:</p>
                            <hr>
                            <ul class="mb-0">
                                <li><strong>Students:</strong> Register and manage student records</li>
                                <li><strong>Teachers:</strong> Add and organize teacher information</li>
                                <li><strong>Classes:</strong> Create and assign classes</li>
                                <li><strong>Sessions:</strong> Schedule and manage sessions</li>
                            </ul>
                        </div>
                        
                        <div class="row mt-4">
                            <div class="col-md-4">
                                <div class="card bg-gradient-info text-white">
                                    <div class="card-body text-center">
                                        <div class="icon icon-shape bg-white text-info rounded-circle shadow mx-auto mb-3">
                                            <i class="fas fa-chart-bar"></i>
                                        </div>
                                        <h3 class="text-white">60</h3>
                                        <p class="text-white-50 mb-0">Total Records</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-gradient-success text-white">
                                    <div class="card-body text-center">
                                        <div class="icon icon-shape bg-white text-success rounded-circle shadow mx-auto mb-3">
                                            <i class="fas fa-graduation-cap"></i>
                                        </div>
                                        <h3 class="text-white">8</h3>
                                        <p class="text-white-50 mb-0">Active Classes</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-gradient-warning text-white">
                                    <div class="card-body text-center">
                                        <div class="icon icon-shape bg-white text-warning rounded-circle shadow mx-auto mb-3">
                                            <i class="fas fa-users"></i>
                                        </div>
                                        <h3 class="text-white">36</h3>
                                        <p class="text-white-50 mb-0">Total Users</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('backend.layouts.footers.auth')
    </div>
@endsection