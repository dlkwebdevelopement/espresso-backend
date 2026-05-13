<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $store->name }} | Espresso</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0,1" />
    <style>
        body {
            background: var(--dark-alt);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .profile-card {
            background: var(--dark);
            border-radius: 32px;
            padding: 40px;
            width: 100%;
            max-width: 500px;
            box-shadow: var(--shadow);
            text-align: center;
            border: 1px solid var(--border);
        }
        .store-icon {
            width: 80px;
            height: 80px;
            background: var(--primary);
            color: #fff;
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
        }
        .store-icon span { font-size: 40px; }
        .store-name {
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 10px;
            color: var(--text);
        }
        .store-location {
            color: var(--text-muted);
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .info-grid {
            display: grid;
            gap: 16px;
            margin-bottom: 40px;
            text-align: left;
        }
        .info-item {
            background: var(--dark-alt);
            padding: 20px;
            border-radius: 20px;
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .info-item .icon { color: var(--primary); }
        .info-label { font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); margin-bottom: 4px; }
        .info-value { font-weight: 600; color: var(--text); }
        .action-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            background: var(--primary);
            color: #fff;
            text-decoration: none;
            padding: 18px;
            border-radius: 16px;
            font-weight: 700;
            transition: all 0.3s;
        }
        .action-btn:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(var(--primary-rgb, 93, 64, 55), 0.2); }
    </style>
    <script>
        // Respect theme if scanned from a device that has it set
        const theme = localStorage.getItem('admin-theme') || 'light';
        document.documentElement.setAttribute('data-theme', theme);
    </script>
</head>
<body>
    <div class="profile-card">
        <div class="store-icon">
            <span class="material-symbols-outlined">coffee</span>
        </div>
        
        <h1 class="store-name">{{ $store->name }}</h1>
        <div class="store-location">
            <span class="material-symbols-outlined" style="font-size: 18px;">location_on</span>
            <span>{{ $store->location }}</span>
        </div>

        <div class="info-grid">
            <div class="info-item">
                <div class="icon"><span class="material-symbols-outlined">call</span></div>
                <div>
                    <div class="info-label">Contact</div>
                    <div class="info-value">{{ $store->contact_number ?? 'N/A' }}</div>
                </div>
            </div>
            <div class="info-item">
                <div class="icon"><span class="material-symbols-outlined">schedule</span></div>
                <div>
                    <div class="info-label">Status</div>
                    <div class="info-value" style="color: #00ff00;">Open Now</div>
                </div>
            </div>
        </div>

        @if($favorites->count() > 0)
            <div style="text-align: left; margin-bottom: 30px;">
                <h3 style="font-size: 18px; margin-bottom: 15px; display: flex; align-items: center; gap: 8px;">
                    <span class="material-symbols-outlined" style="color: #ffd700;">star</span>
                    Weekly Favorites
                </h3>
                <div style="display: flex; gap: 15px; overflow-x: auto; padding-bottom: 10px; scrollbar-width: none;">
                    @foreach($favorites as $item)
                        <div style="min-width: 140px; background: var(--dark-alt); border-radius: 20px; padding: 12px; border: 1px solid var(--border);">
                            <img src="{{ asset($item->image_path) }}" style="width: 100%; height: 100px; border-radius: 12px; object-fit: cover; margin-bottom: 10px;">
                            <div style="font-size: 13px; font-weight: 700; margin-bottom: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $item->name }}</div>
                            <div style="font-size: 12px; color: var(--primary); font-weight: 800;">{{ number_format($item->base_price, 3) }} BHD</div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if($popularItems->count() > 0)
            <div style="text-align: left; margin-bottom: 30px;">
                <h3 style="font-size: 18px; margin-bottom: 15px; display: flex; align-items: center; gap: 8px;">
                    <span class="material-symbols-outlined" style="color: #ff9800;">trending_up</span>
                    Popular Items
                </h3>
                <div style="display: flex; gap: 15px; overflow-x: auto; padding-bottom: 10px; scrollbar-width: none;">
                    @foreach($popularItems as $item)
                        <div style="min-width: 140px; background: var(--dark-alt); border-radius: 20px; padding: 12px; border: 1px solid var(--border);">
                            <img src="{{ asset($item->image_path) }}" style="width: 100%; height: 100px; border-radius: 12px; object-fit: cover; margin-bottom: 10px;">
                            <div style="font-size: 13px; font-weight: 700; margin-bottom: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $item->name }}</div>
                            <div style="font-size: 12px; color: var(--primary); font-weight: 800;">{{ number_format($item->base_price, 3) }} BHD</div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <a href="tel:{{ $store->contact_number }}" class="action-btn">
            <span class="material-symbols-outlined">call</span>
            Call to Order
        </a>
    </div>
</body>
</html>
