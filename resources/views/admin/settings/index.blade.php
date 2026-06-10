@extends('layouts.admin')
@section('title', 'Settings')

@section('content')
<div class="d-flex align-items-start justify-content-between mb-4 flex-wrap gap-3 fade-up">
    <div>
        <h1 class="page-title">System Settings</h1>
        <p class="page-subtitle">Configure platform preferences and system behaviour</p>
    </div>
</div>

<!-- Tab Nav -->
<div style="display:flex;gap:4px;margin-bottom:24px;border-bottom:2px solid var(--border);padding-bottom:0;" id="settingsTabs">
    @foreach(['general'=>'General','notifications'=>'Notifications','payments'=>'Payments','sms'=>'SMS','security'=>'Security'] as $tab => $label)
    <button onclick="switchTab('{{ $tab }}')" id="tab-{{ $tab }}"
        style="padding:10px 20px;border:none;background:transparent;font-size:0.875rem;font-weight:600;cursor:pointer;color:var(--text-muted);border-bottom:2px solid transparent;margin-bottom:-2px;transition:all 0.2s;">
        {{ $label }}
    </button>
    @endforeach
</div>

<!-- GENERAL -->
<div id="section-general" class="settings-section fade-up">
    <form class="k-card mb-4">
        <div class="k-card-header">
            <div class="k-card-title"><i class="ri-settings-3-line"></i> Platform Settings</div>
        </div>
        <div class="k-card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label style="font-size:0.8rem;font-weight:700;color:var(--text-sub);margin-bottom:6px;display:block;">App Name</label>
                    <input type="text" value="{{ config('app.name') }}" class="form-control form-control-sm">
                </div>
                <div class="col-md-6">
                    <label style="font-size:0.8rem;font-weight:700;color:var(--text-sub);margin-bottom:6px;display:block;">App URL</label>
                    <input type="url" value="{{ config('app.url') }}" class="form-control form-control-sm">
                </div>
                <div class="col-md-6">
                    <label style="font-size:0.8rem;font-weight:700;color:var(--text-sub);margin-bottom:6px;display:block;">Default Currency</label>
                    <select class="form-select form-select-sm">
                        <option selected>TZS — Tanzanian Shilling</option>
                        <option>USD — US Dollar</option>
                        <option>KES — Kenyan Shilling</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label style="font-size:0.8rem;font-weight:700;color:var(--text-sub);margin-bottom:6px;display:block;">Default Language</label>
                    <select class="form-select form-select-sm">
                        <option selected>English</option>
                        <option>Swahili</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label style="font-size:0.8rem;font-weight:700;color:var(--text-sub);margin-bottom:6px;display:block;">Commission Rate (%)</label>
                    <input type="number" value="5" step="0.1" class="form-control form-control-sm">
                </div>
                <div class="col-md-6">
                    <label style="font-size:0.8rem;font-weight:700;color:var(--text-sub);margin-bottom:6px;display:block;">Late Payment Fee (TZS)</label>
                    <input type="number" value="10000" class="form-control form-control-sm">
                </div>
            </div>
            <div class="mt-4">
                <button type="button" class="btn-brand" onclick="Swal.fire({title:'Saved!',text:'Settings saved successfully.',icon:'success',confirmButtonColor:'#B44040'})">
                    <i class="ri-save-line"></i> Save Changes
                </button>
            </div>
        </div>
    </form>

    <div class="k-card">
        <div class="k-card-header">
            <div class="k-card-title"><i class="ri-information-line"></i> System Information</div>
        </div>
        <div class="k-card-body">
            <div class="row g-3">
                @php $info = [
                    'PHP Version'     => PHP_VERSION,
                    'Laravel Version' => app()->version(),
                    'Environment'     => app()->environment(),
                    'Debug Mode'      => config('app.debug') ? 'Enabled' : 'Disabled',
                    'Timezone'        => config('app.timezone'),
                    'Database'        => config('database.default'),
                ]; @endphp
                @foreach($info as $k => $v)
                <div class="col-md-4">
                    <div style="background:var(--body-bg);padding:12px 16px;border-radius:8px;">
                        <div style="font-size:0.72rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.5px;margin-bottom:4px;">{{ $k }}</div>
                        <div style="font-size:0.875rem;font-weight:700;color:var(--text-primary);">{{ $v }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- NOTIFICATIONS -->
<div id="section-notifications" class="settings-section" style="display:none;">
    <div class="k-card">
        <div class="k-card-header">
            <div class="k-card-title"><i class="ri-notification-3-line"></i> Notification Preferences</div>
        </div>
        <div class="k-card-body">
            @foreach([
                ['New User Registration','Notify admin when a new user signs up','1'],
                ['New Lease Created','Notify when a new lease is created','1'],
                ['Payment Received','Notify admin of every payment','1'],
                ['Maintenance Requests','Alert on new maintenance requests','1'],
                ['Lease Expiry (30 days)','Warn 30 days before lease expires','0'],
                ['Failed Payments','Alert when payment fails','1'],
            ] as [$title,$desc,$checked])
            <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 0;border-bottom:1px solid var(--border);">
                <div>
                    <div style="font-weight:600;font-size:0.875rem;color:var(--text-primary);">{{ $title }}</div>
                    <div style="font-size:0.78rem;color:var(--text-muted);">{{ $desc }}</div>
                </div>
                <label style="display:flex;align-items:center;cursor:pointer;">
                    <input type="checkbox" {{ $checked ? 'checked' : '' }} style="display:none;" id="notif-{{ Str::slug($title) }}">
                    <div onclick="toggleSwitch(this)" style="width:44px;height:24px;border-radius:12px;background:{{ $checked ? 'var(--brand)' : '#d1d5db' }};position:relative;transition:all 0.3s;cursor:pointer;">
                        <div style="width:18px;height:18px;border-radius:50%;background:#fff;position:absolute;top:3px;{{ $checked ? 'right:3px' : 'left:3px' }};transition:all 0.3s;box-shadow:0 1px 3px rgba(0,0,0,0.2);"></div>
                    </div>
                </label>
            </div>
            @endforeach
            <div class="mt-4">
                <button type="button" class="btn-brand" onclick="Swal.fire({title:'Saved!',text:'Notification settings saved.',icon:'success',confirmButtonColor:'#B44040'})">
                    <i class="ri-save-line"></i> Save Preferences
                </button>
            </div>
        </div>
    </div>
</div>

<!-- PAYMENTS -->
<div id="section-payments" class="settings-section" style="display:none;">
    <div class="k-card">
        <div class="k-card-header">
            <div class="k-card-title"><i class="ri-bank-card-line"></i> Payment Configuration</div>
        </div>
        <div class="k-card-body">
            <div class="row g-3">
                <div class="col-12">
                    <div style="background:var(--body-bg);border-radius:10px;padding:16px;margin-bottom:16px;">
                        <div style="font-weight:700;color:var(--text-primary);margin-bottom:4px;">M-Pesa Integration</div>
                        <div style="font-size:0.82rem;color:var(--text-muted);margin-bottom:12px;">Configure M-Pesa paybill and API credentials</div>
                        <span class="k-badge badge-success"><i class="ri-checkbox-circle-line"></i> Connected</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <label style="font-size:0.8rem;font-weight:700;color:var(--text-sub);margin-bottom:6px;display:block;">M-Pesa Paybill Number</label>
                    <input type="text" value="123456" class="form-control form-control-sm">
                </div>
                <div class="col-md-6">
                    <label style="font-size:0.8rem;font-weight:700;color:var(--text-sub);margin-bottom:6px;display:block;">Consumer Key</label>
                    <input type="password" value="••••••••••••" class="form-control form-control-sm">
                </div>
                <div class="col-md-6">
                    <label style="font-size:0.8rem;font-weight:700;color:var(--text-sub);margin-bottom:6px;display:block;">Callback URL</label>
                    <input type="url" value="{{ config('app.url') }}/api/mpesa/callback" class="form-control form-control-sm" readonly style="background:#f9fafb;">
                </div>
                <div class="col-md-6">
                    <label style="font-size:0.8rem;font-weight:700;color:var(--text-sub);margin-bottom:6px;display:block;">Environment</label>
                    <select class="form-select form-select-sm">
                        <option>Sandbox</option>
                        <option>Production</option>
                    </select>
                </div>
            </div>
            <div class="mt-4">
                <button type="button" class="btn-brand" onclick="Swal.fire({title:'Saved!',text:'Payment settings updated.',icon:'success',confirmButtonColor:'#B44040'})">
                    <i class="ri-save-line"></i> Save
                </button>
            </div>
        </div>
    </div>
</div>

<!-- SMS -->
<div id="section-sms" class="settings-section" style="display:none;">
    <div class="k-card">
        <div class="k-card-header">
            <div class="k-card-title"><i class="ri-message-3-line"></i> SMS Gateway</div>
        </div>
        <div class="k-card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label style="font-size:0.8rem;font-weight:700;color:var(--text-sub);margin-bottom:6px;display:block;">SMS Provider</label>
                    <select class="form-select form-select-sm">
                        <option>Africa's Talking</option>
                        <option>Twilio</option>
                        <option>Vonage</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label style="font-size:0.8rem;font-weight:700;color:var(--text-sub);margin-bottom:6px;display:block;">Sender ID</label>
                    <input type="text" value="KODI" class="form-control form-control-sm">
                </div>
                <div class="col-md-6">
                    <label style="font-size:0.8rem;font-weight:700;color:var(--text-sub);margin-bottom:6px;display:block;">API Key</label>
                    <input type="password" placeholder="••••••••••••" class="form-control form-control-sm">
                </div>
                <div class="col-md-6">
                    <label style="font-size:0.8rem;font-weight:700;color:var(--text-sub);margin-bottom:6px;display:block;">Username</label>
                    <input type="text" placeholder="API username" class="form-control form-control-sm">
                </div>
                <div class="col-12">
                    <label style="font-size:0.8rem;font-weight:700;color:var(--text-sub);margin-bottom:6px;display:block;">Payment Reminder Template</label>
                    <textarea class="form-control form-control-sm" rows="3" placeholder="Hi {name}, your rent of TZS {amount} is due on {date}. Pay via M-Pesa..."></textarea>
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="button" class="btn-ghost" onclick="Swal.fire('Test SMS','Sending test to admin phone...','info')"><i class="ri-send-plane-line"></i> Send Test</button>
                <button type="button" class="btn-brand" onclick="Swal.fire({title:'Saved!',text:'SMS settings updated.',icon:'success',confirmButtonColor:'#B44040'})">
                    <i class="ri-save-line"></i> Save
                </button>
            </div>
        </div>
    </div>
</div>

<!-- SECURITY -->
<div id="section-security" class="settings-section" style="display:none;">
    <div class="k-card">
        <div class="k-card-header">
            <div class="k-card-title"><i class="ri-shield-keyhole-line"></i> Security Settings</div>
        </div>
        <div class="k-card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label style="font-size:0.8rem;font-weight:700;color:var(--text-sub);margin-bottom:6px;display:block;">Session Timeout (minutes)</label>
                    <input type="number" value="120" class="form-control form-control-sm">
                </div>
                <div class="col-md-6">
                    <label style="font-size:0.8rem;font-weight:700;color:var(--text-sub);margin-bottom:6px;display:block;">Max Login Attempts</label>
                    <input type="number" value="5" class="form-control form-control-sm">
                </div>
                <div class="col-12">
                    @foreach([
                        ['Two-Factor Authentication','Require 2FA for all admin accounts'],
                        ['Force HTTPS','Redirect all HTTP to HTTPS'],
                        ['IP Whitelist','Only allow admin access from specific IPs'],
                        ['Activity Logging','Log all admin actions to audit trail'],
                    ] as [$title,$desc])
                    <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 0;border-bottom:1px solid var(--border);">
                        <div>
                            <div style="font-weight:600;font-size:0.875rem;color:var(--text-primary);">{{ $title }}</div>
                            <div style="font-size:0.78rem;color:var(--text-muted);">{{ $desc }}</div>
                        </div>
                        <div onclick="toggleSwitch(this)" style="width:44px;height:24px;border-radius:12px;background:#d1d5db;position:relative;transition:all 0.3s;cursor:pointer;">
                            <div style="width:18px;height:18px;border-radius:50%;background:#fff;position:absolute;top:3px;left:3px;transition:all 0.3s;box-shadow:0 1px 3px rgba(0,0,0,0.2);"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="mt-4">
                <button type="button" class="btn-brand" onclick="Swal.fire({title:'Saved!',text:'Security settings saved.',icon:'success',confirmButtonColor:'#B44040'})">
                    <i class="ri-save-line"></i> Save
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function switchTab(tab) {
    document.querySelectorAll('.settings-section').forEach(el => el.style.display = 'none');
    document.querySelectorAll('#settingsTabs button').forEach(btn => {
        btn.style.color = 'var(--text-muted)';
        btn.style.borderBottomColor = 'transparent';
    });
    document.getElementById('section-' + tab).style.display = 'block';
    const btn = document.getElementById('tab-' + tab);
    btn.style.color = 'var(--brand)';
    btn.style.borderBottomColor = 'var(--brand)';
}

function toggleSwitch(el) {
    const isOn = el.style.background === 'rgb(180, 64, 64)' || el.style.background.includes('#B44040') || el.dataset.on === '1';
    if (isOn) {
        el.style.background = '#d1d5db';
        el.querySelector('div').style.left = '3px';
        el.querySelector('div').style.right = '';
        el.dataset.on = '0';
    } else {
        el.style.background = 'var(--brand)';
        el.querySelector('div').style.left = '';
        el.querySelector('div').style.right = '3px';
        el.dataset.on = '1';
    }
}

// Init active tab
switchTab('general');
</script>
@endpush
@endsection
