@extends('layouts.admin')

@section('title', 'Manage Customers')
@section('header', 'Customers')
@section('subheader', 'Register and manage customer accounts')

@section('content')
    <div style="margin-bottom: 30px; display: flex; justify-content: flex-end;">
        <a href="{{ route('admin.customers.create') }}" class="btn-primary" style="width: auto; padding: 12px 24px; text-decoration: none;">
            + Register New Customer
        </a>
    </div>

    <div class="stat-card" style="padding: 0; overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse; color: var(--text);">
            <thead style="background: rgba(255, 255, 255, 0.05);">
                <tr>
                    <th style="text-align: left; padding: 20px; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted);">Name</th>
                    <th style="text-align: left; padding: 20px; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted);">Email</th>
                    <th style="text-align: left; padding: 20px; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted);">Mobile</th>
                    <th style="text-align: left; padding: 20px; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted);">D.O.B</th>
                    <th style="text-align: center; padding: 20px; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted);">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                    <tr style="border-bottom: 1px solid var(--border); transition: background 0.3s;" onmouseover="this.style.background='rgba(255, 222, 173, 0.03)'" onmouseout="this.style.background='none'">
                        <td style="padding: 20px; font-weight: 600;">{{ $customer->name }}</td>
                        <td style="padding: 20px; color: var(--text-muted);">{{ $customer->email }}</td>
                        <td style="padding: 20px;">{{ $customer->mobile_number }}</td>
                        <td style="padding: 20px;">{{ $customer->dob ? $customer->dob->format('d M Y') : 'N/A' }}</td>
                        <td style="padding: 20px; text-align: center;">
                            <div style="display: flex; gap: 10px; justify-content: center;">
                                <a href="{{ route('admin.customers.edit', $customer) }}" style="color: var(--primary); text-decoration: none;">
                                    <span class="material-symbols-outlined">edit</span>
                                </a>
                                <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST" onsubmit="return confirm('Delete this customer?')">
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
                        <td colspan="5" style="padding: 40px; text-align: center; color: var(--text-muted);">No customers registered yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $customers->links() }}
    </div>
@endsection
