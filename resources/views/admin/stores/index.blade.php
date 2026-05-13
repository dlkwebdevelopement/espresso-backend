@extends('layouts.admin')

@section('title', 'Manage Stores')
@section('header', 'Store Directory')
@section('subheader', 'Manage your cafe locations and franchises')

@section('content')
    <div style="margin-bottom: 30px; display: flex; justify-content: flex-end;">
        <a href="{{ route('admin.stores.create') }}" class="btn-primary" style="width: auto; padding: 12px 24px; text-decoration: none;">
            + Register New Store
        </a>
    </div>

    <div class="stat-card" style="padding: 0; overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse; color: var(--text);">
            <thead style="background: rgba(255, 255, 255, 0.05);">
                <tr>
                    <th style="text-align: left; padding: 20px; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted);">Name</th>
                    <th style="text-align: left; padding: 20px; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted);">Location</th>
                    <th style="text-align: left; padding: 20px; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted);">QR Code</th>
                    <th style="text-align: left; padding: 20px; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted);">Status</th>
                    <th style="text-align: center; padding: 20px; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted);">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stores as $store)
                    <tr style="border-bottom: 1px solid var(--border); transition: background 0.3s;" onmouseover="this.style.background='rgba(255, 222, 173, 0.03)'" onmouseout="this.style.background='none'">
                        <td style="padding: 20px; font-weight: 600;">{{ $store->name }}</td>
                        <td style="padding: 20px; color: var(--text-muted);">{{ $store->location }}</td>
                        <td style="padding: 20px;">
                            <div style="background: #fff; padding: 5px; border-radius: 8px; display: inline-block; cursor: pointer;" 
                                 onclick="showQR('{{ $store->name }}', '{{ route('stores.public_profile', $store->id) }}')">
                                {!! SimpleSoftwareIO\QrCode\Facades\QrCode::size(40)->generate(route('stores.public_profile', $store->id)) !!}
                            </div>
                        </td>
                        <td style="padding: 20px;">
                            <span style="background: rgba(0, 255, 0, 0.1); color: #00ff00; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                Active
                            </span>
                        </td>
                        <td style="padding: 20px; text-align: center;">
                            <div style="display: flex; gap: 10px; justify-content: center;">
                                <a href="{{ route('admin.stores.edit', $store) }}" style="color: var(--primary); text-decoration: none;">
                                    <span class="material-symbols-outlined">edit</span>
                                </a>
                                <form action="{{ route('admin.stores.destroy', $store) }}" method="POST" onsubmit="return confirm('Delete this store?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" style="background: none; border: none; color: #ff6b6b; cursor: pointer;">
                                        <span class="material-symbols-outlined">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding: 40px; text-align: center; color: var(--text-muted);">No stores registered yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

@section('scripts')
<div id="qr-modal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.8); backdrop-filter: blur(8px); z-index: 9999; align-items: center; justify-content: center; padding: 20px;">
    <div style="background: var(--dark); border: 1px solid var(--border); border-radius: 32px; padding: 40px; width: 100%; max-width: 400px; text-align: center; position: relative;">
        <button onclick="closeQR()" style="position: absolute; top: 20px; right: 20px; background: none; border: none; color: var(--text-muted); cursor: pointer;">
            <span class="material-symbols-outlined">close</span>
        </button>
        
        <h2 id="modal-store-name" style="margin-bottom: 30px; font-weight: 800; color: var(--text);"></h2>
        
        <div id="modal-qr-container" style="background: #fff; padding: 20px; border-radius: 20px; display: inline-block; margin-bottom: 30px; box-shadow: 0 10px 40px rgba(0,0,0,0.2);">
            <!-- QR Generated Here -->
        </div>
        
        <p style="color: var(--text-muted); font-size: 14px; margin-bottom: 30px;">
            Scan this code to view the public store profile and location details.
        </p>
        
        <div style="display: flex; gap: 10px;">
            <button onclick="window.print()" class="btn-primary" style="margin-top: 0; display: flex; align-items: center; justify-content: center; gap: 8px;">
                <span class="material-symbols-outlined" style="font-size: 20px;">print</span>
                Print QR
            </button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.1/build/qrcode.min.js"></script>
<script>
    function showQR(name, url) {
        document.getElementById('modal-store-name').textContent = name;
        const container = document.getElementById('modal-qr-container');
        container.innerHTML = '';
        
        const canvas = document.createElement('canvas');
        QRCode.toCanvas(canvas, url, { 
            width: 250,
            margin: 1,
            color: {
                dark: '#2C1810',
                light: '#ffffff'
            }
        }, function (error) {
            if (error) console.error(error);
        });
        
        container.appendChild(canvas);
        document.getElementById('qr-modal').style.display = 'flex';
    }

    function closeQR() {
        document.getElementById('qr-modal').style.display = 'none';
    }
</script>
@endsection
