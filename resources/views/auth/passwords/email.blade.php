@extends('layouts.auth')

@push('head')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('form-header')
    <h2 class="auth-form-title">Forgot your password?</h2>
    <p class="auth-form-subtitle">No worries. Enter your email and we'll send you reset instructions.</p>
@endsection

@section('form-content')
    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" id="forgotPasswordForm">
        @csrf

        <div class="form-group">
            <label for="email" class="form-label">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                Email address
            </label>
            <div class="input-icon-wrap">
                <svg class="input-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                <input id="email" type="email" class="form-input @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="you@example.com">
            </div>
            @error('email')
                <div class="invalid-feedback">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ $message }}
                </div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">
            <span class="btn-text">Send reset link</span>
        </button>
    </form>

    @if (Route::has('login'))
        <div class="divider">Remember your password?</div>
        <a href="{{ route('login') }}" class="btn btn-link">Back to sign in</a>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const forgotPasswordForm = document.getElementById('forgotPasswordForm');

            @if(session('status'))
                Swal.fire({
                    position: 'bottom-end',
                    icon: 'success',
                    title: '{{ session('status') }}',
                    showConfirmButton: false,
                    timer: 4000,
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

            forgotPasswordForm.addEventListener('submit', function(e) {
                const btn = this.querySelector('.btn-primary');
                const btnText = btn.querySelector('.btn-text');
                btn.classList.add('loading');
                btnText.textContent = 'Sending...';
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
