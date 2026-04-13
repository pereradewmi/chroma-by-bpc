@extends('layouts.app', ['class' => 'bg-default'])

@section('content')

    <div class="container mt-8 pb-5">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="card bg-secondary shadow border-0 login-card">
                    <!-- Professional Logo Section -->
                    <div class="card-header bg-transparent text-center py-4">
                        <div class="logo-section">
                            <!-- Chroma Logo -->
                            <img src="{{ asset('front-assets/img/logo.png') }}"
                                 alt="Chroma Logo"
                                 class="logo-img mb-3">
                            
                            <small class="text-muted">Admin Portal</small>
                        </div>
                    </div>
                    
                    <div class="card-body px-4 py-4">
                        <!-- Success/Error Messages -->
                        @if (session('message'))
                            <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                                <i class="fas fa-check-circle mr-2"></i>{{ session('message') }}
                                <button type="button" class="close" data-dismiss="alert">
                                    <span>&times;</span>
                                </button>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                @foreach ($errors->all() as $error)
                                    {{ $error }}
                                @endforeach
                                <button type="button" class="close" data-dismiss="alert">
                                    <span>&times;</span>
                                </button>
                            </div>
                        @endif
                        <form role="form" method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="form-group mb-3">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fas fa-envelope text-primary"></i>
                                        </span>
                                    </div>
                                    <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" 
                                           placeholder="Email Address" 
                                           type="email" 
                                           name="email" 
                                           value="{{ old('email') }}" 
                                           required 
                                           autofocus>
                                </div>
                                @if ($errors->has('email'))
                                    <small class="text-danger">{{ $errors->first('email') }}</small>
                                @endif
                            </div>

                            <div class="form-group mb-3">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fas fa-lock text-primary"></i>
                                        </span>
                                    </div>
                                    <input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" 
                                           name="password" 
                                           placeholder="Password" 
                                           type="password" 
                                           required>
                                </div>
                                @if ($errors->has('password'))
                                    <small class="text-danger">{{ $errors->first('password') }}</small>
                                @endif
                            </div>

                            <div class="form-group mb-4">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" 
                                           name="remember" 
                                           id="customCheckLogin" 
                                           type="checkbox" 
                                           {{ old('remember') ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="customCheckLogin">
                                        <span class="text-muted">Remember me</span>
                                    </label>
                                </div>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary btn-block py-2 font-weight-bold">
                                    <i class="fas fa-sign-in-alt mr-2"></i>Sign In
                                </button>
                            </div>
                        </form>
                        
                        <!-- <div class="text-center mt-3">
                            <a href="#" class="text-primary">
                                <small>Forgot your password?</small>
                            </a>
                        </div> -->
                    </div>
                    
                    <!-- Professional Footer -->
                    <div class="card-footer bg-transparent text-center py-3">
                        <small class="text-muted">© {{ date('Y') }} Chroma. All rights reserved.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Professional Styling -->
    <style>
        .login-card {
            border-radius: 12px;
            overflow: hidden;
        }
        
        .logo-section {
            padding: 1rem 0;
        }
        
        .logo-img {
            max-height: 60px;
            width: auto;
            object-fit: contain;
        }
        
        .form-control {
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 12px 15px;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #04415f;
            box-shadow: 0 0 0 0.2rem rgba(4, 65, 95, 0.15);
        }

        .input-group-text {
            border: 1px solid #dee2e6;
            background: #f8f9fe;
            border-right: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, #04415f 0%, #064d7a 100%);
            border: none;
            border-radius: 6px;
            padding: 12px 30px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 5px 15px rgba(4, 65, 95, 0.4);
        }

        /* .text-primary {
            color: #04415f !important;
        } */

        .bg-default {
            background: linear-gradient(135deg, #04415f 0%, #064d7a 100%) !important;
            min-height: 100vh;
        }
        
        .card {
            box-shadow: 0 15px 35px rgba(50, 50, 93, 0.1), 0 5px 15px rgba(0, 0, 0, 0.07);
        }
        
        .alert {
            border-radius: 6px;
            font-size: 13px;
        }
        
        @media (max-width: 576px) {
            .logo-img {
                max-height: 50px;
            }
            
            .card {
                margin: 0 10px;
            }
        }
    </style>
@endsection
