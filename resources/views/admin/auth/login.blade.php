<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Login | Espresso</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <script>
        // Early theme initialization to prevent flash
        const theme = localStorage.getItem('admin-theme') || 'light';
        document.documentElement.setAttribute('data-theme', theme);
    </script>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h1>Espresso</h1>
                <p>Administrative Control Center</p>
            </div>

            <form action="{{ route('admin.login') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label class="form-label" for="email">Admin Email</label>
                    <input type="email" id="email" name="email" class="form-control" 
                           value="{{ old('email') }}" required autofocus placeholder="admin@espresso.com">
                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Security Key</label>
                    <input type="password" id="password" name="password" class="form-control" 
                           required placeholder="••••••••">
                    @error('password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group" style="margin-bottom: 30px;">
                    <label class="remember-me">
                        <input type="checkbox" name="remember" id="remember">
                        <span class="checkbox-custom"></span>
                        <span class="remember-text">Stay authenticated for 30 days</span>
                    </label>
                </div>

                <button type="submit" class="btn-primary">
                    Enter Dashboard
                </button>
            </form>
        </div>
    </div>
</body>
</html>
