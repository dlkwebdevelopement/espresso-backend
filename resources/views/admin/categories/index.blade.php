@extends('layouts.admin')

@section('title', 'Manage Categories')
@section('header', 'Product Categories')
@section('subheader', 'Top-level groups for your products')

@section('content')
    @if(auth('admin')->user()->role === 'super_admin')
    <div style="margin-bottom: 30px; display: flex; justify-content: flex-end;">
        <a href="{{ route('admin.categories.create') }}" class="btn-primary" style="width: auto; padding: 12px 24px; text-decoration: none;">
            + Add New Category
        </a>
    </div>
    @endif

    <div class="stat-card" style="padding: 0; overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse; color: var(--text);">
            <thead style="background: rgba(255, 255, 255, 0.05);">
                <tr>
                    <th style="text-align: left; padding: 20px; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted);">Name</th>
                    <th style="text-align: left; padding: 20px; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted);">Slug</th>
                    <th style="text-align: left; padding: 20px; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted);">Sub-Categories</th>
                    <th style="text-align: left; padding: 20px; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted);">Status</th>
                    @if(auth('admin')->user()->role === 'super_admin')
                        <th style="text-align: center; padding: 20px; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted);">Actions</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr style="border-bottom: 1px solid var(--border); transition: background 0.3s;" onmouseover="this.style.background='rgba(255, 222, 173, 0.03)'" onmouseout="this.style.background='none'">
                        <td style="padding: 20px; font-weight: 600;">{{ $category->name }}</td>
                        <td style="padding: 20px; color: var(--text-muted);">{{ $category->slug }}</td>
                        <td style="padding: 20px;">
                            <span style="background: rgba(var(--primary-rgb, 93, 64, 55), 0.1); color: var(--primary); padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                {{ $category->sub_categories_count }} Items
                            </span>
                        </td>
                        <td style="padding: 20px;">
                            @if($category->is_active)
                                <span style="background: rgba(0, 255, 0, 0.1); color: #00ff00; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Active</span>
                            @else
                                <span style="background: rgba(255, 255, 255, 0.05); color: var(--text-muted); padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Inactive</span>
                            @endif
                        </td>
                        @if(auth('admin')->user()->role === 'super_admin')
                        <td style="padding: 20px; text-align: center;">
                            <div style="display: flex; gap: 10px; justify-content: center;">
                                <a href="{{ route('admin.categories.edit', $category) }}" style="color: var(--primary); text-decoration: none;">
                                    <span class="material-symbols-outlined">edit</span>
                                </a>
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Delete this category? This will also remove all its sub-categories!')">
                                    @csrf @method('DELETE')
                                    <button type="submit" style="background: none; border: none; color: #ff6b6b; cursor: pointer;">
                                        <span class="material-symbols-outlined">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding: 40px; text-align: center; color: var(--text-muted);">No categories found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
