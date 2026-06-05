@extends('layouts.admin')

@section('title', 'Weekly Favorites')
@section('header', 'Weekly Favorites')
@section('subheader', 'Manage featured products for this store')

@section('content')
    <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 30px;">
        
        <!-- Left Side: List of Current Favorites -->
        <div>
            <div class="stat-card" style="padding: 0; overflow: hidden;">
                <h3 style="padding: 25px; background: rgba(255,255,255,0.02); border-bottom: 1px solid var(--border); font-weight: 700;">Current Favorites</h3>
                <table style="width: 100%; border-collapse: collapse; color: var(--text);">
                    <thead style="background: rgba(255, 255, 255, 0.05);">
                        <tr>
                            <th style="text-align: left; padding: 15px 25px; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted);">Product</th>
                            <th style="text-align: left; padding: 15px; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted);">Category</th>
                            <th style="text-align: center; padding: 15px; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted);">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($favorites as $fav)
                            <tr style="border-bottom: 1px solid var(--border);">
                                <td style="padding: 15px 25px;">
                                    <div style="display: flex; align-items: center; gap: 15px;">
                                        <img src="{{ asset($fav->image_path) }}" style="width: 40px; height: 40px; border-radius: 10px; object-fit: cover;">
                                        <div style="font-weight: 600;">{{ $fav->name }}</div>
                                    </div>
                                </td>
                                <td style="padding: 15px; font-size: 13px; color: var(--text-muted);">
                                    {{ $fav->subCategory->name }}
                                </td>
                                <td style="padding: 15px; text-align: center;">
                                    <form action="{{ route('admin.weekly-favorites.destroy', $fav->id) }}" method="POST" onsubmit="return confirm('Remove from favorites?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" style="background: none; border: none; color: #ff6b6b; cursor: pointer;">
                                            <span class="material-symbols-outlined">delete</span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" style="padding: 40px; text-align: center; color: var(--text-muted);">No products favorited yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Pagination -->
                @if($favorites->hasPages())
                    <div style="padding: 20px; border-top: 1px solid var(--border); display: flex; justify-content: center; background: rgba(0,0,0,0.2);">
                        {{ $favorites->links('pagination::bootstrap-4') }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Side: Assign New Product -->
        <div>
            <div class="stat-card">
                <h3 style="margin-bottom: 25px; font-weight: 700;">Assign Product</h3>
                <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 25px;">Choose a product from your menu to highlight as a weekly favorite.</p>
                
                <form action="{{ route('admin.weekly-favorites.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Select Product</label>
                        <select name="item_id" class="form-control" required style="height: 50px;">
                            <option value="">-- Choose an item --</option>
                            @foreach($availableItems as $item)
                                <option value="{{ $item->id }}">{{ $item->name }} ({{ $item->subCategory->name }})</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn-primary" style="margin-top: 10px;">
                        <span class="material-symbols-outlined" style="font-size: 18px; vertical-align: middle;">add</span>
                        Add to Favorites
                    </button>
                </form>

                <div style="margin-top: 30px; padding: 20px; background: rgba(255, 215, 0, 0.05); border-radius: 16px; border: 1px solid rgba(255, 215, 0, 0.1);">
                    <div style="display: flex; gap: 10px; color: #ffd700;">
                        <span class="material-symbols-outlined">lightbulb</span>
                        <div style="font-size: 12px; line-height: 1.5;">
                            <strong>Tip:</strong> Weekly favorites are featured at the top of the customer shop page to increase sales.
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
