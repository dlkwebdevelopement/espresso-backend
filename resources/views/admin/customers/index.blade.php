@extends('layouts.admin')

@section('title', 'Manage Customers')
@section('header', 'Customers')
@section('subheader', 'Register and manage customer accounts')

@section('content')
    <div
        style="margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center; gap: 20px; flex-wrap: wrap;">
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('admin.customers.index') }}" class="btn-primary"
                style="background: {{ request('filter') === null ? 'var(--primary)' : 'rgba(255, 255, 255, 0.05)' }}; color: {{ request('filter') === null ? '#FFF' : 'var(--text)' }}; padding: 8px 16px; border-radius: 8px; text-decoration: none; font-size: 13px; font-weight: 600; width: auto;">
                All Customers
            </a>
            <a href="{{ route('admin.customers.index', ['filter' => 'deletion_requested']) }}" class="btn-primary"
                style="background: {{ request('filter') === 'deletion_requested' ? '#ff4d4d' : 'rgba(255, 77, 77, 0.1)' }}; color: {{ request('filter') === 'deletion_requested' ? '#FFF' : '#ff4d4d' }}; border: 1px solid {{ request('filter') === 'deletion_requested' ? '#ff4d4d' : 'rgba(255, 77, 77, 0.3)' }}; padding: 8px 16px; border-radius: 8px; text-decoration: none; font-size: 13px; font-weight: 600; width: auto;">
                Deletion Requests
            </a>
        </div>
        <a href="{{ route('admin.customers.create') }}" class="btn-primary"
            style="width: auto; padding: 12px 24px; text-decoration: none;">
            + Register New Customer
        </a>
    </div>

    <div class="stat-card" style="padding: 0; overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse; color: var(--text);">
            <thead style="background: rgba(255, 255, 255, 0.05);">
                <tr>
                    <th
                        style="text-align: left; padding: 20px; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted);">
                        Name</th>
                    <th
                        style="text-align: left; padding: 20px; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted);">
                        Email</th>
                    <th
                        style="text-align: left; padding: 20px; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted);">
                        Mobile</th>
                    <th
                        style="text-align: left; padding: 20px; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted);">
                        D.O.B</th>
                    <th
                        style="text-align: center; padding: 20px; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted);">
                        Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                    <tr style="border-bottom: 1px solid var(--border); transition: background 0.3s;"
                        onmouseover="this.style.background='rgba(255, 222, 173, 0.03)'"
                        onmouseout="this.style.background='none'">
                        <td style="padding: 20px; font-weight: 600;">{{ $customer->name }}</td>
                        <td style="padding: 20px; color: var(--text-muted); vertical-align: top;">
                            <div>{{ $customer->email }}</div>
                            @if ($customer->deletion_requested)
                                <div
                                    style="margin-top: 8px; display: inline-flex; align-items: center; gap: 4px; background: rgba(255, 77, 77, 0.15); color: #ff4d4d; border: 1px solid rgba(255, 77, 77, 0.3); padding: 4px 8px; border-radius: 6px; font-size: 10px; font-weight: bold; text-transform: uppercase;">
                                    🛑 Deletion Requested
                                </div>
                                <div
                                    style="font-size: 11px; color: #ffbcbc; font-style: italic; max-width: 250px; margin-top: 6px; line-height: 1.4; padding: 6px 10px; background: rgba(255, 255, 255, 0.02); border-radius: 8px; border-left: 3px solid #ff4d4d;">
                                    "{{ $customer->deletion_reason }}"
                                </div>
                                <div style="font-size: 10px; color: var(--text-muted); margin-top: 4px;">
                                    Requested:
                                    {{ $customer->deletion_requested_at ? $customer->deletion_requested_at->format('d M Y H:i') : 'N/A' }}
                                </div>
                            @endif
                        </td>
                        <td style="padding: 20px;">{{ $customer->mobile_number }}</td>
                        <td style="padding: 20px;">{{ $customer->dob ? $customer->dob->format('d M Y') : 'N/A' }}</td>
                        <td style="padding: 20px; text-align: center;">
                            <div style="display: flex; gap: 10px; justify-content: center;">
                                <a href="{{ route('admin.customers.edit', $customer) }}"
                                    style="color: var(--primary); text-decoration: none;">
                                    <span class="material-symbols-outlined">edit</span>
                                </a>
                                <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST"
                                    onsubmit="return confirm('Delete this customer?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        style="background: none; border: none; color: #ff6b6b; cursor: pointer;">
                                        <span class="material-symbols-outlined">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding: 40px; text-align: center; color: var(--text-muted);">No customers
                            registered yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        @if ($customers->hasPages())
            <div
                style="padding: 20px; border-top: 1px solid var(--border); display: flex; justify-content: center; background: rgba(0,0,0,0.2);">
                {{ $customers->links('pagination::bootstrap-4') }}
            </div>
        @endif
    </div>
@endsection
