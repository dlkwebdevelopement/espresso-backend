@extends('layouts.admin')
@section('title', 'Manage Free Drinks')
@section('header', 'Free Drinks')
@section('subheader', 'Configure and manage complimentary drinks')

@section('content')
    <div style="margin-bottom: 30px; display: flex; justify-content: flex-end;">
        <a href="{{ route('admin.free-drinks.create') }}" class="btn-primary" style="width: auto; padding: 12px 24px; text-decoration: none; margin-top: 0;">
            + Add Free Drink
        </a>
    </div>

    <div class="stat-card" style="padding: 0; overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse; color: var(--text);">
            <thead style="background: rgba(255, 255, 255, 0.05);">
                <tr>
                    <th style="text-align: left; padding: 20px; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted);">Name</th>
                    <th style="text-align: left; padding: 20px; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted);">Status</th>
                    <th style="text-align: center; padding: 20px; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted);">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($freeDrinks as $drink)
                    <tr style="border-bottom: 1px solid var(--border); transition: background 0.3s;" onmouseover="this.style.background='rgba(var(--primary-rgb, 255, 222, 173), 0.03)'" onmouseout="this.style.background='none'">
                        <td style="padding: 20px; font-weight: 600;">{{ $drink->name }}</td>
                        <td style="padding: 20px;">
                            @if($drink->is_active)
                                <span style="background: rgba(0, 255, 0, 0.1); color: #00ff00; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Active</span>
                            @else
                                <span style="background: rgba(255, 255, 255, 0.05); color: var(--text-muted); padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Inactive</span>
                            @endif
                        </td>
                        <td style="padding: 20px; text-align: center;">
                            <div style="display: flex; gap: 10px; justify-content: center;">
                                <a href="{{ route('admin.free-drinks.edit', $drink->id) }}" style="color: var(--primary); text-decoration: none;">
                                    <span class="material-symbols-outlined">edit</span>
                                </a>
                                <form action="{{ route('admin.free-drinks.destroy', $drink->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this free drink?');">
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
                        <td colspan="3" style="padding: 40px; text-align: center; color: var(--text-muted);">No free drinks configured.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
