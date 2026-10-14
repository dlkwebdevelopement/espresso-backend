@extends('layouts.admin')

@section('title', 'Manage Sub-Categories')
@section('header', 'Sub-Categories')
@section('subheader', 'Detailed product groupings')

@section('content')
    @if(auth('admin')->user()->role === 'super_admin')
    <div style="margin-bottom: 30px; display: flex; justify-content: flex-end;">
        <a href="{{ route('admin.sub_categories.create') }}" class="btn-primary" style="width: auto; padding: 12px 24px; text-decoration: none;">
            + Add New Sub-Category
        </a>
    </div>
    @endif

    <div class="stat-card" style="padding: 0; overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse; color: var(--text);">
            <thead style="background: rgba(255, 255, 255, 0.05);">
                <tr>
                    <th style="text-align: left; padding: 20px; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted);">Image</th>
                    <th style="text-align: left; padding: 20px; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted);">Name</th>
                    <th style="text-align: left; padding: 20px; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted);">Parent Category</th>
                    <th style="text-align: left; padding: 20px; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted);">Slug</th>
                    <th style="text-align: left; padding: 20px; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted);">Status</th>
                    @if(auth('admin')->user()->role === 'super_admin')
                        <th style="text-align: center; padding: 20px; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted);">Actions</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($subCategories as $subCategory)
                    <tr style="border-bottom: 1px solid var(--border); transition: background 0.3s;" onmouseover="this.style.background='rgba(255, 222, 173, 0.03)'" onmouseout="this.style.background='none'">
                        <!-- Image Column - FIXED -->
                        <td style="padding: 20px;">
                            @if($subCategory->image)
                                <img src="{{ $subCategory->image_url }}" 
                                     alt="{{ $subCategory->name }}" 
                                     style="width: 40px; height: 40px; object-fit: cover; border-radius: 8px; display: block;"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div style="width: 40px; height: 40px; background: rgba(255,255,255,0.1); border-radius: 8px; display: none; align-items: center; justify-content: center;">
                                    <span class="material-symbols-outlined" style="font-size: 20px; color: var(--text-muted);">image</span>
                                </div>
                            @else
                                <div style="width: 40px; height: 40px; background: rgba(255,255,255,0.1); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                    <span class="material-symbols-outlined" style="font-size: 20px; color: var(--text-muted);">image</span>
                                </div>
                            @endif
                        </td>

                        <!-- Name Column -->
                        <td style="padding: 20px; font-weight: 600;">{{ $subCategory->name }}</td>

                        <!-- Parent Category Column -->
                        <td style="padding: 20px;">
                            <span style="color: var(--primary); font-weight: 500;">
                                {{ $subCategory->category->name ?? 'N/A' }}
                            </span>
                        </td>

                        <!-- Slug Column -->
                        <td style="padding: 20px; color: var(--text-muted);">{{ $subCategory->slug }}</td>

                        <!-- Status Column -->
                        <td style="padding: 20px;">
                            @if($subCategory->is_active)
                                <span style="background: rgba(0, 255, 0, 0.1); color: #00ff00; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Active</span>
                            @else
                                <span style="background: rgba(255, 255, 255, 0.05); color: var(--text-muted); padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Inactive</span>
                            @endif
                        </td>

                        <!-- Actions Column -->
                        @if(auth('admin')->user()->role === 'super_admin')
                        <td style="padding: 20px; text-align: center;">
                            <div style="display: flex; gap: 10px; justify-content: center;">
                                <a href="{{ route('admin.sub_categories.edit', $subCategory) }}" style="color: var(--primary); text-decoration: none; cursor: pointer;" title="Edit">
                                    <span class="material-symbols-outlined">edit</span>
                                </a>
                                <form action="{{ route('admin.sub_categories.destroy', $subCategory) }}" method="POST" onsubmit="return confirm('Delete this sub-category?')" style="margin: 0;">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="submit" style="background: none; border: none; color: #ff6b6b; cursor: pointer; padding: 0; display: flex; align-items: center;" title="Delete">
                                        <span class="material-symbols-outlined">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding: 40px; text-align: center; color: var(--text-muted);">No sub-categories found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        @if($subCategories->hasPages())
            <div style="padding: 20px; border-top: 1px solid var(--border); display: flex; justify-content: center; background: rgba(0,0,0,0.2);">
                {{ $subCategories->links('pagination::bootstrap-4') }}
            </div>
        @endif
    </div>
@endsection