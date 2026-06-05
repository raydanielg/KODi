@extends('layouts.auth')

@push('head')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('form-header')
    <h2 class="auth-form-title">Verify your email</h2>
    <p class="auth-form-subtitle">We've sent a verification link to your email address. Please check your inbox.</p>
@endsection

@section('form-content')
    @if (session('resent'))
        <div class="alert alert-success">A fresh verification link has been sent to your email address.</div>
    @endif

    <div class="alert alert-info">
        Before proceeding, please check your email for a verification link. If you did not receive the email, click below to request another.
    </div>

    <form method="POST" action="{{ route('verification.resend') }}" id="verifyForm">
        @csrf
        <button type="submit" class="btn btn-primary">
            <span class="btn-text">Resend verification email</span>
        </button>
    </form>

    @if (Route::has('login'))
        <div class="auth-footer-link">
            <a href="{{ route('login') }}">Back to sign in</a>
        </div>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const verifyForm = document.getElementById('verifyForm');

            @if(session('resent'))
                Swal.fire({
                    position: 'bottom-end',
                    icon: 'success',
                    title: 'Verification link sent!',
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

            verifyForm.addEventListener('submit', function(e) {
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
