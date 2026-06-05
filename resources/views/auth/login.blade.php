@extends('layouts.auth')

@push('head')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('form-header')
    <h2 class="auth-form-title">Welcome back</h2>
    <p class="auth-form-subtitle">Sign in to your account to continue your journey with us.</p>
@endsection

@section('form-content')
    <form method="POST" action="{{ route('login') }}" id="loginForm">
        @csrf

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <div class="form-group">
            <label for="email" class="form-label">Email address</label>
            <div class="input-wrapper">
                <svg class="input-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="name@company.com">
            </div>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="password" class="form-label">Password</label>
            <div class="input-wrapper">
                <svg class="input-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="••••••••">
            </div>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-check">
                <input type="checkbox" class="form-check-input" name="remember" {{ old('remember') ? 'checked' : '' }}>
                <span class="form-check-label">Remember me</span>
            </label>
        </div>

        <button type="submit" class="btn btn-primary btn-block">
            <span class="btn-text">Sign in</span>
        </button>
    </form>

    <div class="auth-footer">
        <span>Don't have an account?</span>
        <a href="{{ route('register') }}" class="auth-footer-link">Create account</a>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.getElementById('loginForm');

            // Show success message if exists
            @if(session('status'))
                Swal.fire({
                    position: 'bottom-end',
                    icon: 'success',
                    title: '{{ session('status') }}',
                    showConfirmButton: false,
                    timer: 3000,
                    toast: true,
                    background: '#ecfdf5',
                    color: '#065f46',
                    iconColor: '#10B981',
                    customClass: {
                        popup: 'swal2-popup-custom',
                        title: 'swal2-title-custom'
                    }
                });
            @endif

            // Show error messages
            @if($errors->any())
                @foreach($errors->all() as $error)
                    Swal.fire({
                        position: 'bottom-end',
                        icon: 'error',
                        title: '{{ $error }}',
                        showConfirmButton: false,
                        timer: 4000,
                        toast: true,
                        background: '#fef2f2',
                        color: '#991b1b',
                        iconColor: '#ef4444',
                        customClass: {
                            popup: 'swal2-popup-custom',
                            title: 'swal2-title-custom'
                        }
                    });
                @endforeach
            @endif

            // Handle form submission with loading state
            loginForm.addEventListener('submit', function(e) {
                const btn = this.querySelector('.btn-primary');
                const btnText = btn.querySelector('.btn-text');

                btn.classList.add('loading');
                btnText.textContent = 'Signing in...';
            });
        });
    </script>

    <style>
        .swal2-popup-custom {
            border-radius: 12px !important;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15) !important;
            border: 1px solid rgba(0,0,0,0.1) !important;
            backdrop-filter: blur(10px) !important;
        }

        .swal2-title-custom {
            font-size: 0.95rem !important;
            font-weight: 600 !important;
        }

        .swal2-icon {
            border: none !important;
        }

        .swal2-toast {
            padding: 1rem 1.5rem !important;
        }

        .swal2-toast .swal2-icon {
            width: 2.5em !important;
            height: 2.5em !important;
        }
    </style>
@endsection
