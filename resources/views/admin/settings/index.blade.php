@extends('layouts.admin')

@section('title', 'Global Settings')
@section('header', 'Settings')
@section('subheader', 'Configure global application parameters')

@section('content')
    <div class="login-card" style="max-width: 800px; margin: 0 auto; animation: none;">
        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf

            <!-- General Settings -->
            <h3 style="margin-bottom: 20px; color: var(--primary); border-bottom: 1px solid var(--border); padding-bottom: 10px;">General Settings</h3>
            
            <div class="form-group">
                <label class="form-label">Application Name</label>
                <input type="text" name="app_name" value="{{ $settings['app_name'] ?? config('app.name') }}" class="form-control" required>
            </div>

            <div class="form-group" style="margin-bottom: 40px;">
                <label class="form-label">Currency Symbol</label>
                <input type="text" name="currency_symbol" value="{{ $settings['currency_symbol'] ?? 'BHD' }}" class="form-control" required placeholder="e.g. BHD, $, €">
            </div>

            <!-- Financial Settings -->
            <h3 style="margin-bottom: 20px; color: var(--primary); border-bottom: 1px solid var(--border); padding-bottom: 10px;">Financial Configuration</h3>
            
            <div style="display: flex; gap: 20px; margin-bottom: 40px;">
                <div class="form-group" style="flex: 1;">
                    <label class="form-label">Global VAT (%)</label>
                    <input type="number" step="0.01" name="vat_rate" value="{{ $settings['vat_rate'] ?? '0' }}" class="form-control" required>
                </div>
                
                <div class="form-group" style="flex: 1;">
                    <label class="form-label">Service Charge (%)</label>
                    <input type="number" step="0.01" name="service_charge_rate" value="{{ $settings['service_charge_rate'] ?? '0' }}" class="form-control" required>
                </div>
            </div>

            <!-- Contact Settings -->
            <h3 style="margin-bottom: 20px; color: var(--primary); border-bottom: 1px solid var(--border); padding-bottom: 10px;">Contact Information</h3>

            <div class="form-group">
                <label class="form-label">Support Email</label>
                <input type="email" name="support_email" value="{{ $settings['support_email'] ?? '' }}" class="form-control" placeholder="support@espresso.com">
            </div>
            
            <div class="form-group">
                <label class="form-label">Contact Phone</label>
                <input type="text" name="support_phone" value="{{ $settings['support_phone'] ?? '' }}" class="form-control" placeholder="+1 (555) 000-0000">
            </div>

            <div style="display: flex; justify-content: flex-end; margin-top: 40px;">
                <button type="submit" class="btn-primary" style="width: auto; padding: 12px 30px;">Save Changes</button>
            </div>
        </form>
    </div>
@endsection
