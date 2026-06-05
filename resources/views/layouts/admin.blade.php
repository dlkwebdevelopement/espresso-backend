<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | Espresso Admin</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0,1" />
    <script>
        // Early theme initialization to prevent flash
        const theme = localStorage.getItem('admin-theme') || 'light';
        document.documentElement.setAttribute('data-theme', theme);
    </script>
    @yield('styles')
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-logo">ESPRESSO.</div>
            
            <nav class="nav-menu">
                @if(auth('admin')->user()->role === 'super_admin')
                    <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <span class="material-symbols-outlined">dashboard</span>
                        <span>Overview</span>
                    </a>
                    <a href="{{ route('admin.categories.index') }}" class="nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                        <span class="material-symbols-outlined">category</span>
                        <span>Categories</span>
                    </a>
                    <a href="{{ route('admin.sub_categories.index') }}" class="nav-item {{ request()->routeIs('admin.sub_categories.*') ? 'active' : '' }}">
                        <span class="material-symbols-outlined">account_tree</span>
                        <span>Sub-Categories</span>
                    </a>
                    <a href="{{ route('admin.items.index') }}" class="nav-item {{ request()->routeIs('admin.items.*') ? 'active' : '' }}">
                        <span class="material-symbols-outlined">shopping_bag</span>
                        <span>Products</span>
                    </a>
                    <a href="{{ route('admin.stores.index') }}" class="nav-item {{ request()->routeIs('admin.stores.*') ? 'active' : '' }}">
                        <span class="material-symbols-outlined">store</span>
                        <span>Manage Stores</span>
                    </a>
                    <a href="{{ route('admin.admins.index') }}" class="nav-item {{ request()->routeIs('admin.admins.*') ? 'active' : '' }}">
                        <span class="material-symbols-outlined">group</span>
                        <span>Admin Team</span>
                    </a>
                    <a href="{{ route('admin.customers.index') }}" class="nav-item {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                        <span class="material-symbols-outlined">person</span>
                        <span>Customers</span>
                    </a>
                    <a href="{{ route('admin.orders.index') }}" class="nav-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                        <span class="material-symbols-outlined">shopping_cart</span>
                        <span>Orders</span>
                    </a>
                    <a href="{{ route('admin.weekly-favorites.index') }}" class="nav-item {{ request()->routeIs('admin.weekly-favorites.*') ? 'active' : '' }}">
                        <span class="material-symbols-outlined">star</span>
                        <span>Weekly Favorites</span>
                    </a>
                    <a href="{{ route('admin.popular-items.index') }}" class="nav-item {{ request()->routeIs('admin.popular-items.*') ? 'active' : '' }}">
                        <span class="material-symbols-outlined">trending_up</span>
                        <span>Popular Items</span>
                    </a>
                    <a href="{{ route('admin.free-drinks.index') }}" class="nav-item {{ request()->routeIs('admin.free-drinks.*') ? 'active' : '' }}">
                        <span class="material-symbols-outlined">local_cafe</span>
                        <span>Free Drinks</span>
                    </a>
                @else
                    <a href="{{ route('admin.store-dashboard') }}" class="nav-item {{ request()->routeIs('admin.store-dashboard') ? 'active' : '' }}">
                        <span class="material-symbols-outlined">dashboard</span>
                        <span>My Store</span>
                    </a>
                    <a href="{{ route('admin.categories.index') }}" class="nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                        <span class="material-symbols-outlined">category</span>
                        <span>Categories</span>
                    </a>
                    <a href="{{ route('admin.sub_categories.index') }}" class="nav-item {{ request()->routeIs('admin.sub_categories.*') ? 'active' : '' }}">
                        <span class="material-symbols-outlined">account_tree</span>
                        <span>Sub-Categories</span>
                    </a>
                    <a href="{{ route('admin.items.index') }}" class="nav-item {{ request()->routeIs('admin.items.*') ? 'active' : '' }}">
                        <span class="material-symbols-outlined">shopping_bag</span>
                        <span>Manage Items</span>
                    </a>
                    <a href="{{ route('admin.customers.index') }}" class="nav-item {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                        <span class="material-symbols-outlined">person</span>
                        <span>Manage Customers</span>
                    </a>
                    <a href="{{ route('admin.weekly-favorites.index') }}" class="nav-item {{ request()->routeIs('admin.weekly-favorites.*') ? 'active' : '' }}">
                        <span class="material-symbols-outlined">star</span>
                        <span>Weekly Favorites</span>
                    </a>
                    <a href="{{ route('admin.popular-items.index') }}" class="nav-item {{ request()->routeIs('admin.popular-items.*') ? 'active' : '' }}">
                        <span class="material-symbols-outlined">trending_up</span>
                        <span>Popular Items</span>
                    </a>
                    <a href="{{ route('admin.free-drinks.index') }}" class="nav-item {{ request()->routeIs('admin.free-drinks.*') ? 'active' : '' }}">
                        <span class="material-symbols-outlined">local_cafe</span>
                        <span>Free Drinks</span>
                    </a>
                    <a href="{{ route('admin.orders.index') }}" class="nav-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                        <span class="material-symbols-outlined">shopping_cart</span>
                        <span>Orders</span>
                    </a>
                @endif
                
                <div class="theme-toggle" onclick="toggleTheme()">
                    <span class="material-symbols-outlined" id="theme-icon">dark_mode</span>
                    <span id="theme-text">Switch to Dark Mode</span>
                </div>

                <a href="{{ route('admin.settings.index') }}" class="nav-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">settings</span>
                    <span>Settings</span>
                </a>
            </nav>

            <div class="sidebar-footer">
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="nav-item" style="width: 100%; background: none; border: none; cursor: pointer; text-align: left;">
                        <span class="material-symbols-outlined">logout</span>
                        <span>Sign Out</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="dashboard-header">
                <div class="header-text">
                    <h1>@yield('header')</h1>
                    <p style="color: var(--text-muted);">@yield('subheader')</p>
                </div>
                
                <div class="user-profile">
                    <div class="avatar"></div>
                    <div style="display: flex; flex-direction: column;">
                        <span style="font-weight: 600; font-size: 14px;">{{ auth('admin')->user()->name }}</span>
                        <span style="font-size: 11px; color: var(--primary);">{{ ucfirst(str_replace('_', ' ', auth('admin')->user()->role)) }}</span>
                    </div>
                </div>
            </header>

            @if(session('success'))
                <div style="background: rgba(0, 255, 0, 0.1); border: 1px solid rgba(0, 255, 0, 0.2); color: #00ff00; padding: 15px; border-radius: 12px; margin-bottom: 20px; font-size: 14px;">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div style="background: rgba(255, 0, 0, 0.1); border: 1px solid rgba(255, 0, 0, 0.2); color: #ff6b6b; padding: 15px; border-radius: 12px; margin-bottom: 20px; font-size: 14px;">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script>
        function toggleTheme() {
            const currentTheme = document.documentElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            
            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('admin-theme', newTheme);
            updateThemeUI(newTheme);
        }

        function updateThemeUI(theme) {
            const icon = document.getElementById('theme-icon');
            const text = document.getElementById('theme-text');
            
            if (theme === 'dark') {
                icon.textContent = 'light_mode';
                text.textContent = 'Switch to Light Mode';
            } else {
                icon.textContent = 'dark_mode';
                text.textContent = 'Switch to Dark Mode';
            }
        }

        // Initialize UI on load
        updateThemeUI(localStorage.getItem('admin-theme') || 'light');
    </script>
    @yield('scripts')
</body>
</html>
