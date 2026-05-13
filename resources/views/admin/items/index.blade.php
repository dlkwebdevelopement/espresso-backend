@extends('layouts.admin')

@section('title', 'Manage Items')
@section('header', 'Product Catalog')
@section('subheader', 'Manage your menu items and prices')

@section('content')
    <div style="margin-bottom: 30px; display: flex; justify-content: flex-end;">
        <a href="{{ route('admin.items.create') }}" class="btn-primary" style="width: auto; padding: 12px 24px; text-decoration: none;">
            + Add New Item
        </a>
    </div>

    <div class="stat-card" style="padding: 0; overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse; color: var(--text);">
            <thead style="background: rgba(255, 255, 255, 0.05);">
                <tr>
                    <th style="text-align: left; padding: 20px; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted);">Item</th>
                    <th style="text-align: left; padding: 20px; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted);">Category</th>
                    @if(auth()->guard('admin')->user()->role === 'super_admin')
                        <th style="text-align: left; padding: 20px; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted);">Store</th>
                    @endif
                    <th style="text-align: left; padding: 20px; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted);">Base Price</th>
                    <th style="text-align: left; padding: 20px; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted);">Status</th>
                    <th style="text-align: center; padding: 20px; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted);">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                    <tr style="border-bottom: 1px solid var(--border); transition: background 0.3s;" onmouseover="this.style.background='rgba(255, 222, 173, 0.03)'" onmouseout="this.style.background='none'">
                        <td style="padding: 20px;">
                            <div style="display: flex; align-items: center; gap: 15px;">
                                @if($item->image_path)
                                    <img src="{{ asset($item->image_path) }}" style="width: 45px; height: 45px; border-radius: 12px; object-fit: cover; border: 1px solid var(--border);">
                                @else
                                    <div style="width: 45px; height: 45px; border-radius: 12px; background: var(--dark-alt); display: flex; align-items: center; justify-content: center; border: 1px solid var(--border);">
                                        <span class="material-symbols-outlined" style="font-size: 20px; color: var(--text-muted);">image</span>
                                    </div>
                                @endif
                                <div>
                                    <div style="font-weight: 600;">{{ $item->name }}</div>
                                    <div style="font-size: 12px; color: var(--text-muted);">ID: #{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="padding: 20px;">
                            <div style="font-weight: 700; color: var(--text);">{{ $item->name }}</div>
                            <div style="font-size: 11px; color: var(--text-muted);">{{ $item->subCategory->name ?? 'Uncategorized' }}</div>
                            @if($item->is_weekly_favorite)
                                <div style="display: inline-flex; align-items: center; gap: 4px; background: rgba(255, 215, 0, 0.1); color: #ffd700; padding: 2px 8px; border-radius: 6px; font-size: 10px; font-weight: 800; margin-top: 5px; text-transform: uppercase;">
                                    <span class="material-symbols-outlined" style="font-size: 12px;">star</span>
                                    Weekly Favorite
                                </div>
                            @endif
                        </td>
                        @if(auth()->guard('admin')->user()->role === 'super_admin')
                            <td style="padding: 20px; font-size: 13px;">{{ $item->store->name }}</td>
                        @endif
                        <td style="padding: 20px; font-weight: 700; color: var(--primary);">
                            {{ number_format($item->base_price, 3) }} BHD
                        </td>
                        <td style="padding: 20px;">
                            @if($item->is_available)
                                <span style="background: rgba(0, 255, 0, 0.1); color: #00ff00; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; text-transform: uppercase;">Available</span>
                            @else
                                <span style="background: rgba(255, 255, 255, 0.05); color: var(--text-muted); padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; text-transform: uppercase;">Sold Out</span>
                            @endif
                        </td>
                        <td style="padding: 20px; text-align: center;">
                            <div style="display: flex; gap: 10px; justify-content: center;">
                                <a href="{{ route('admin.items.edit', $item) }}" style="color: var(--primary); text-decoration: none;">
                                    <span class="material-symbols-outlined">edit</span>
                                </a>
                                <form action="{{ route('admin.items.destroy', $item) }}" method="POST" onsubmit="return confirm('Delete this item?')">
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
                        <td colspan="6" style="padding: 40px; text-align: center; color: var(--text-muted);">No items found in your catalog.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
