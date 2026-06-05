@extends('layouts.auth')

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
            verifyForm.addEventListener('submit', function(e) {
                const btn = this.querySelector('.btn-primary');
                const btnText = btn.querySelector('.btn-text');
                btn.classList.add('loading');
                btnText.textContent = 'Sending...';
            });
        });
    </script>
@endsection
