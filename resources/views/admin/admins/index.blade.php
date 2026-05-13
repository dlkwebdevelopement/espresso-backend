@extends('layouts.admin')

@section('title', 'Admin Team')
@section('header', 'Administrative Team')
@section('subheader', 'Manage system access and store managers')

@section('content')
    <div style="margin-bottom: 30px; display: flex; justify-content: flex-end;">
        <a href="{{ route('admin.admins.create') }}" class="btn-primary" style="width: auto; padding: 12px 24px; text-decoration: none;">
            + Invite New Admin
        </a>
    </div>

    <div class="stat-card" style="padding: 0; overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse; color: var(--text);">
            <thead style="background: rgba(255, 255, 255, 0.05);">
                <tr>
                    <th style="text-align: left; padding: 20px; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted);">Name / Email</th>
                    <th style="text-align: left; padding: 20px; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted);">Role</th>
                    <th style="text-align: left; padding: 20px; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted);">Assigned Store</th>
                    <th style="text-align: center; padding: 20px; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted);">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($admins as $admin)
                    <tr style="border-bottom: 1px solid var(--border); transition: background 0.3s;" onmouseover="this.style.background='rgba(255, 222, 173, 0.03)'" onmouseout="this.style.background='none'">
                        <td style="padding: 20px;">
                            <div style="font-weight: 600;">{{ $admin->name }}</div>
                            <div style="font-size: 12px; color: var(--text-muted);">{{ $admin->email }}</div>
                        </td>
                        <td style="padding: 20px;">
                            <span style="background: {{ $admin->role === 'super_admin' ? 'rgba(255, 222, 173, 0.1)' : 'rgba(255, 255, 255, 0.05)' }}; color: {{ $admin->role === 'super_admin' ? 'var(--primary)' : '#fff' }}; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; text-transform: uppercase;">
                                {{ str_replace('_', ' ', $admin->role) }}
                            </span>
                        </td>
                        <td style="padding: 20px; color: var(--text-muted);">
                            {{ $admin->store->name ?? 'Global (All Stores)' }}
                        </td>
                        <td style="padding: 20px; text-align: center;">
                            <div style="display: flex; gap: 10px; justify-content: center;">
                                <a href="{{ route('admin.admins.edit', $admin) }}" style="color: var(--primary); text-decoration: none;">
                                    <span class="material-symbols-outlined">edit</span>
                                </a>
                                @if($admin->id !== auth('admin')->id())
                                    <form action="{{ route('admin.admins.destroy', $admin) }}" method="POST" onsubmit="return confirm('Revoke access for this admin?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" style="background: none; border: none; color: #ff6b6b; cursor: pointer;">
                                            <span class="material-symbols-outlined">delete</span>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
