@extends('layouts.auth')

@push('head')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('form-header')
    <h2 class="auth-form-title">Confirm your password</h2>
    <p class="auth-form-subtitle">For security, please confirm your password to continue. This helps protect your account.</p>
@endsection

@section('form-content')
    <form method="POST" action="{{ route('password.confirm') }}" id="confirmPasswordForm">
        @csrf

        <div class="form-group">
            <label for="password" class="form-label">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                Password
            </label>
            <div class="input-icon-wrap">
                <svg class="input-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                <input id="password" type="password" class="form-input @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Enter your password">
            </div>
            @error('password')
                <div class="invalid-feedback">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ $message }}
                </div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">
            <span class="btn-text">Confirm password</span>
        </button>
    </form>

    @if (Route::has('password.request'))
        <div class="auth-footer-link">
            <a href="{{ route('password.request') }}">Forgot your password?</a>
        </div>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const confirmPasswordForm = document.getElementById('confirmPasswordForm');

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

            confirmPasswordForm.addEventListener('submit', function(e) {
                const btn = this.querySelector('.btn-primary');
                const btnText = btn.querySelector('.btn-text');
                btn.classList.add('loading');
                btnText.textContent = 'Confirming...';
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
