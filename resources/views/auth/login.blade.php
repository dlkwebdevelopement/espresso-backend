@extends('layouts.auth')

@section('title', 'Sign In')

@section('content')
<div class="auth-card">
    <div class="auth-header">
        <div class="auth-logo">ESPRESSO.</div>
        <p style="color: var(--text-muted);">Welcome back! Please sign in.</p>
    </div>

    <div class="tabs">
        <div class="tab active" id="pass-tab" onclick="switchMode('password')">Password</div>
        <div class="tab" id="otp-tab" onclick="switchMode('otp')">Email OTP</div>
    </div>

    <!-- Password Login Form -->
    <form id="password-form" action="{{ route('login') }}" method="POST">
        @csrf
        <div class="form-group">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control" placeholder="john@example.com" required>
            @error('email') <span style="color: #ff6b6b; font-size: 11px;">{{ $message }}</span> @enderror
        </div>
        <div class="form-group">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
        </div>
        <button type="submit" class="btn-auth">Sign In</button>
    </form>

    <!-- OTP Login Form (Hidden initially) -->
    <div id="otp-form" style="display: none;">
        <div id="otp-step-1">
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" id="otp-email" class="form-control" placeholder="john@example.com">
            </div>
            <button type="button" onclick="sendOtp()" class="btn-auth" id="send-otp-btn">Send OTP</button>
        </div>

        <div id="otp-step-2" style="display: none;">
            <div class="form-group">
                <label class="form-label">Enter 6-Digit OTP</label>
                <input type="text" id="otp-code" class="form-control" placeholder="123456" maxlength="6" style="text-align: center; font-size: 24px; letter-spacing: 10px;">
            </div>
            <button type="button" onclick="verifyOtp()" class="btn-auth">Verify & Sign In</button>
            <p style="text-align: center; font-size: 12px; margin-top: 15px;">Didn't receive it? <a href="#" onclick="sendOtp()" style="color: var(--primary);">Resend</a></p>
        </div>
    </div>

    <div class="auth-footer">
        New to Espresso? <a href="{{ route('register') }}" class="auth-link">Join Now</a>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function switchMode(mode) {
        const passForm = document.getElementById('password-form');
        const otpForm = document.getElementById('otp-form');
        const passTab = document.getElementById('pass-tab');
        const otpTab = document.getElementById('otp-tab');

        if (mode === 'password') {
            passForm.style.display = 'block';
            otpForm.style.display = 'none';
            passTab.classList.add('active');
            otpTab.classList.remove('active');
        } else {
            passForm.style.display = 'none';
            otpForm.style.display = 'block';
            passTab.classList.remove('active');
            otpTab.classList.add('active');
        }
    }

    async function sendOtp() {
        const email = document.getElementById('otp-email').value;
        const btn = document.getElementById('send-otp-btn');
        if (!email) return alert('Please enter your email.');

        btn.disabled = true;
        btn.innerText = 'Sending...';

        try {
            const response = await fetch('{{ route("otp.send") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ email })
            });
            const data = await response.json();
            
            if (response.ok) {
                document.getElementById('otp-step-1').style.display = 'none';
                document.getElementById('otp-step-2').style.display = 'block';
                alert('OTP sent! Check your email (or logs).');
            } else {
                alert(data.message || 'Error sending OTP.');
            }
        } catch (e) {
            alert('Something went wrong.');
        } finally {
            btn.disabled = false;
            btn.innerText = 'Send OTP';
        }
    }

    async function verifyOtp() {
        const email = document.getElementById('otp-email').value;
        const otp = document.getElementById('otp-code').value;

        try {
            const response = await fetch('{{ route("otp.verify") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ email, otp })
            });
            const data = await response.json();

            if (data.success) {
                window.location.href = data.redirect;
            } else {
                alert(data.message);
            }
        } catch (e) {
            alert('Verification failed.');
        }
    }
</script>
@endsection
