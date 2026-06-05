@extends('layouts.auth')

@section('form-header')
    <h2 class="auth-form-title">Create your account</h2>
    <p class="auth-form-subtitle">Start your journey today by creating a new account. We're ready to help you achieve your dreams.</p>
@endsection

@section('form-content')
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="form-group">
            <label for="role" class="form-label">I am a</label>
            <div class="input-wrapper">
                <svg class="input-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                <select id="role" name="role" class="form-control @error('role') is-invalid @enderror" required onchange="updateRoleDescription()">
                    <option value="">Select your role</option>
                    <option value="tenant">Tenant (Mpangaji)</option>
                    <option value="landlord">Landlord (Mmiliki wa Nyumba)</option>
                    <option value="agent">Agent (Wakala wa Nyumba)</option>
                    <option value="support">Support Agent</option>
                    <option value="maintenance">Maintenance Staff</option>
                    <option value="accountant">Accountant</option>
                </select>
            </div>
            @error('role')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <div id="role-description" class="role-description"></div>
        </div>

        <div class="form-group">
            <label for="name" class="form-label">Full name</label>
            <div class="input-wrapper">
                <svg class="input-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" placeholder="John Doe">
            </div>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="email" class="form-label">Email address</label>
            <div class="input-wrapper">
                <svg class="input-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="name@company.com">
            </div>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="phone" class="form-label">Phone number</label>
            <div class="input-wrapper">
                <svg class="input-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                <input id="phone" type="tel" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required autocomplete="tel" placeholder="+1 234 567 890">
            </div>
            @error('phone')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="password" class="form-label">Password</label>
            <div class="input-wrapper">
                <svg class="input-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="••••••••">
            </div>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="password-confirm" class="form-label">Confirm password</label>
            <div class="input-wrapper">
                <svg class="input-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••">
            </div>
        </div>

        <div class="form-group">
            <label class="form-check">
                <input type="checkbox" class="form-check-input" name="terms" required>
                <span class="form-check-label">I agree to the <a href="{{ route('terms') }}" target="_blank">Terms of Service</a> and <a href="{{ route('privacy') }}" target="_blank">Privacy Policy</a></span>
            </label>
        </div>

        <button type="submit" class="btn btn-primary btn-block">
            <span class="btn-text">Create account</span>
        </button>
    </form>

    <div class="auth-footer">
        <span>Already have an account?</span>
        <a href="{{ route('login') }}" class="auth-footer-link">Sign in</a>
    </div>
@endsection

@push('scripts')
<script>
    const roleDescriptions = {
        tenant: 'Tenant: Looking for a long-term rental property',
        landlord: 'Landlord: Own properties to rent out',
        agent: 'Agent: Manage properties on behalf of landlords',
        support: 'Support Agent: Help users with their questions',
        maintenance: 'Maintenance Staff: Handle property repairs',
        accountant: 'Accountant: Manage financial records'
    };

    function updateRoleDescription() {
        const roleSelect = document.getElementById('role');
        const descriptionDiv = document.getElementById('role-description');
        const selectedRole = roleSelect.value;
        
        if (roleDescriptions[selectedRole]) {
            descriptionDiv.textContent = roleDescriptions[selectedRole];
            descriptionDiv.style.display = 'block';
        } else {
            descriptionDiv.style.display = 'none';
        }
    }
</script>
@endpush
