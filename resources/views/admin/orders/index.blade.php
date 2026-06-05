@extends('layouts.admin')

@section('title', 'Manage Orders')
@section('header', 'Orders List')
@section('subheader', 'Track customer orders, payments, and process digital wallet refunds')

@section('content')
    <!-- Search and Actions Bar -->
    <div style="margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center; gap: 20px; flex-wrap: wrap;">
        <form action="{{ route('admin.orders.index') }}" method="GET" style="display: flex; gap: 12px; flex: 1; max-width: 600px;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by Order ID, customer name or email..." class="form-control" style="border-radius: 30px; height: 48px; border: 1px solid var(--border); padding-left: 20px;">
            <button type="submit" class="btn-primary" style="width: auto; padding: 0 24px; margin-top: 0; border-radius: 30px; display: flex; align-items: center; gap: 8px; height: 48px; white-space: nowrap;">
                <span class="material-symbols-outlined" style="font-size: 20px;">search</span>
                <span>Search</span>
            </button>
            @if(request('search'))
                <a href="{{ route('admin.orders.index') }}" class="btn-primary" style="width: auto; padding: 0 20px; margin-top: 0; border-radius: 30px; display: flex; align-items: center; background: rgba(255,255,255,0.05); color: var(--text); border: 1px solid var(--border); text-decoration: none; height: 48px; white-space: nowrap;">
                    Clear
                </a>
            @endif
        </form>
    </div>

    <!-- Orders Table Container -->
    <div class="stat-card" style="padding: 0; overflow: hidden; border-radius: var(--radius); border: 1px solid var(--border);">
        <table style="width: 100%; border-collapse: collapse; color: var(--text);">
            <thead style="background: rgba(255, 255, 255, 0.02); border-bottom: 1px solid var(--border);">
                <tr>
                    <th style="text-align: left; padding: 20px; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); font-weight: 700;">Order Details</th>
                    <th style="text-align: left; padding: 20px; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); font-weight: 700;">Customer</th>
                    <th style="text-align: left; padding: 20px; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); font-weight: 700; width: 35%;">Items Ordered</th>
                    <th style="text-align: right; padding: 20px; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); font-weight: 700;">Total Amount</th>
                    <th style="text-align: center; padding: 20px; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); font-weight: 700;">Payment Status</th>
                    <th style="text-align: center; padding: 20px; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); font-weight: 700;">Order Status</th>
                    <th style="text-align: center; padding: 20px; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); font-weight: 700;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    @php
                        $items = is_string($order->items) ? json_decode($order->items, true) : $order->items;
                    @endphp
                    <tr style="border-bottom: 1px solid var(--border); transition: background 0.3s;" onmouseover="this.style.background='rgba(255, 222, 173, 0.02)'" onmouseout="this.style.background='none'">
                        <!-- Order ID & Date -->
                        <td style="padding: 20px;">
                            <div style="font-weight: 700; font-size: 14px; color: var(--primary);">#{{ $order->order_id }}</div>
                            <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">
                                {{ $order->created_at->format('M d, Y h:i A') }}
                            </div>
                        </td>
                        
                        <!-- Customer details -->
                        <td style="padding: 20px;">
                            @if($order->user)
                                <div style="font-weight: 600; font-size: 14px;">{{ $order->user->name }}</div>
                                <div style="font-size: 12px; color: var(--text-muted); margin-top: 2px;">{{ $order->user->email }}</div>
                            @else
                                <div style="font-size: 13px; color: var(--text-muted);">Guest User</div>
                            @endif
                        </td>

                        <!-- Items Breakdown -->
                        <td style="padding: 20px;">
                            @if(is_array($items))
                                <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 8px;">
                                    @foreach($items as $item)
                                        @php
                                            $itemName = $item['name'] ?? 'Unknown Item';
                                            $quantity = $item['quantity'] ?? 1;
                                            $price = $item['base_price'] ?? 0;
                                            $options = $item['options'] ?? null;
                                        @endphp
                                        <li style="font-size: 13px; line-height: 1.4;">
                                            <span style="font-weight: 700; color: var(--primary);">{{ $quantity }}x</span> {{ $itemName }}
                                            <span style="color: var(--text-muted); font-size: 11px;">(BD {{ number_format($price, 3) }} each)</span>
                                            
                                            @if($options && is_array($options))
                                                <div style="display: flex; flex-wrap: wrap; gap: 4px; margin-top: 3px; margin-left: 18px;">
                                                    @foreach($options as $key => $val)
                                                        @if(!empty($val))
                                                            <span style="background: rgba(var(--primary-rgb, 93, 64, 55), 0.08); color: var(--primary); padding: 1px 6px; border-radius: 4px; font-size: 10px; font-weight: 600;">
                                                                {{ ucfirst($key) }}: {{ $val }}
                                                            </span>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <span style="color: var(--text-muted); font-size: 13px;">No items breakdown</span>
                            @endif
                        </td>

                        <!-- Total -->
                        <td style="padding: 20px; text-align: right; font-weight: 800; font-size: 14px; color: var(--primary);">
                            BD {{ number_format($order->total, 3) }}
                        </td>

                        <!-- Payment Status -->
                        <td style="padding: 20px; text-align: center;">
                            @if($order->payment_status === 'refunded')
                                <span style="background: rgba(231, 76, 60, 0.1); color: #e74c3c; padding: 6px 14px; border-radius: 20px; font-size: 11px; font-weight: 700; display: inline-block; border: 1px solid rgba(231, 76, 60, 0.2);">
                                    Refunded
                                </span>
                            @elseif($order->payment_status === 'refund_pending')
                                <span style="background: rgba(241, 196, 15, 0.1); color: #f1c40f; padding: 6px 14px; border-radius: 20px; font-size: 11px; font-weight: 700; display: inline-block; border: 1px solid rgba(241, 196, 15, 0.2);">
                                    Refund Pending
                                </span>
                            @elseif($order->payment_status === 'paid')
                                <span style="background: rgba(46, 204, 113, 0.1); color: #2ecc71; padding: 6px 14px; border-radius: 20px; font-size: 11px; font-weight: 700; display: inline-block; border: 1px solid rgba(46, 204, 113, 0.2);">
                                    Paid
                                </span>
                            @else
                                <span style="background: rgba(255, 255, 255, 0.05); color: var(--text-muted); padding: 6px 14px; border-radius: 20px; font-size: 11px; font-weight: 700; display: inline-block; border: 1px solid var(--border);">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            @endif
                        </td>

                        <!-- Order Status -->
                        <td style="padding: 20px; text-align: center;">
                            @if($order->status === 'refunded')
                                <span style="background: rgba(231, 76, 60, 0.1); color: #e74c3c; padding: 6px 14px; border-radius: 20px; font-size: 11px; font-weight: 700; display: inline-block; border: 1px solid rgba(231, 76, 60, 0.2);">
                                    Refunded
                                </span>
                            @elseif($order->status === 'refund_pending')
                                <span style="background: rgba(241, 196, 15, 0.1); color: #f1c40f; padding: 6px 14px; border-radius: 20px; font-size: 11px; font-weight: 700; display: inline-block; border: 1px solid rgba(241, 196, 15, 0.2);">
                                    Refund Pending
                                </span>
                            @else
                                <span style="background: rgba(var(--primary-rgb, 93, 64, 55), 0.1); color: var(--primary); padding: 6px 14px; border-radius: 20px; font-size: 11px; font-weight: 700; display: inline-block; border: 1px solid rgba(var(--primary-rgb, 93, 64, 55), 0.2);">
                                    {{ ucfirst($order->status) }}
                                </span>
                            @endif
                        </td>

                        <!-- Action button -->
                        <td style="padding: 20px; text-align: center;">
                            @if($order->payment_status !== 'refunded')
                                <div style="display: flex; flex-direction: column; gap: 10px; align-items: center;">
                                    <form action="{{ route('admin.orders.status', $order) }}" method="POST" style="display: flex; gap: 5px;">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" onchange="this.form.submit()" style="background: rgba(255,255,255,0.05); color: var(--text); border: 1px solid var(--border); border-radius: 20px; padding: 6px 12px; font-size: 11px; cursor: pointer;">
                                            <option value="ordered" {{ $order->status == 'ordered' ? 'selected' : '' }}>Ordered</option>
                                            <option value="preparing" {{ $order->status == 'preparing' ? 'selected' : '' }}>Preparing</option>
                                            <option value="ready" {{ $order->status == 'ready' ? 'selected' : '' }}>Ready</option>
                                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                        </select>
                                    </form>

                                    <button type="button" class="btn-primary" 
                                        onclick="openRefundModal('{{ $order->order_id }}', '{{ route('admin.orders.refund', $order) }}', '{{ addslashes($order->refund_reason ?? '') }}')" 
                                        style="background: #ff6b6b; color: white; width: auto; font-size: 11px; font-weight: 700; padding: 6px 14px; border-radius: 30px; cursor: pointer; transition: all 0.3s; margin: 0; display: inline-flex; align-items: center; gap: 4px; border: none;">
                                        <span class="material-symbols-outlined" style="font-size: 14px;">keyboard_return</span>
                                        <span>Refund</span>
                                    </button>
                                </div>
                                @if($order->payment_status === 'refund_pending' && $order->refund_reason)
                                    <div style="font-size: 10px; color: var(--text-muted); margin-top: 6px; max-width: 150px; margin-left: auto; margin-right: auto; line-height: 1.2;">
                                        <strong>Requested:</strong><br>
                                        {{ $order->refund_reason }}
                                    </div>
                                @endif
                            @else
                                <div style="font-size: 11px; color: var(--text-muted); text-align: center; max-width: 150px; margin: 0 auto; line-height: 1.3;">
                                    <strong>Refund Reason:</strong><br>
                                    {{ $order->refund_reason ?? 'Not specified' }}
                                </div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="padding: 60px; text-align: center; color: var(--text-muted); font-size: 14px;">
                            <span class="material-symbols-outlined" style="font-size: 48px; color: var(--border); margin-bottom: 10px; display: block;">shopping_cart_off</span>
                            No orders found matching the filter criteria.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        @if($orders->hasPages())
            <div style="padding: 20px; border-top: 1px solid var(--border); display: flex; justify-content: center; background: rgba(0,0,0,0.2);">
                {{ $orders->links('pagination::bootstrap-4') }}
            </div>
        @endif
    </div>

    <!-- Interactive Refund Modal -->
    <div id="refundModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); backdrop-filter: blur(5px); z-index: 1000; align-items: center; justify-content: center; padding: 20px;">
        <div class="stat-card" style="width: 100%; max-width: 500px; padding: 30px; position: relative; animation: slideUp 0.3s ease-out; background: var(--dark-alt); border-radius: var(--radius); border: 1px solid var(--border); box-shadow: var(--shadow);">
            
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 style="font-weight: 800; font-size: 20px; color: var(--primary);">Process Wallet Refund</h2>
                <span class="material-symbols-outlined" style="cursor: pointer; color: var(--text-muted);" onclick="closeRefundModal()">close</span>
            </div>
            
            <p style="color: var(--text-muted); font-size: 13px; line-height: 1.5; margin-bottom: 24px;">
                This action will mark Order <strong id="modalOrderId" style="color: var(--text);">#</strong> as refunded and credit the full purchase amount back to the customer's Espresso digital wallet balance instantly.
            </p>
            
            <form id="refundForm" method="POST" action="">
                @csrf
                <div class="form-group" style="margin-bottom: 20px;">
                    <label class="form-label" for="refund_reason">Refund Reason</label>
                    <select name="refund_reason" id="refund_reason" class="form-control" onchange="toggleCustomReason(this.value)" style="height: 48px; border: 1px solid var(--border); border-radius: 12px; background: var(--dark); color: var(--text);" required>
                        <option value="Incorrect Order Items">Incorrect Order Items</option>
                        <option value="Order Preparation Delayed">Order Preparation Delayed</option>
                        <option value="Quality Issues">Quality Issues</option>
                        <option value="Accidental Double Order">Accidental Double Order</option>
                        <option value="Other">Other (Enter custom reason below)</option>
                    </select>
                </div>
                
                <div class="form-group" id="customReasonGroup" style="display: none; margin-bottom: 20px;">
                    <label class="form-label" for="custom_reason">Custom Details</label>
                    <textarea id="custom_reason" class="form-control" rows="3" placeholder="Explain the reason for refunding this order..." style="border: 1px solid var(--border); border-radius: 12px; background: var(--dark); color: var(--text); padding: 14px; font-family: inherit; font-size: 14px; width: 100%;"></textarea>
                </div>
                
                <div style="display: flex; gap: 12px; justify-content: flex-end; margin-top: 30px;">
                    <button type="button" class="btn-primary" onclick="closeRefundModal()" style="background: rgba(255,255,255,0.05); color: var(--text); border: 1px solid var(--border); margin-top: 0; width: auto; padding: 12px 24px; border-radius: 30px; height: 44px; display: flex; align-items: center;">
                        Cancel
                    </button>
                    <button type="submit" class="btn-primary" style="background: #ff6b6b; margin-top: 0; width: auto; padding: 12px 24px; border-radius: 30px; height: 44px; display: flex; align-items: center; border: none;">
                        Confirm Refund
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal animations style -->
    <style>
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
@endsection

@section('scripts')
    <script>
        function openRefundModal(orderId, refundRoute, refundReason = '') {
            document.getElementById('modalOrderId').textContent = '#' + orderId;
            const modal = document.getElementById('refundModal');
            const form = document.getElementById('refundForm');
            form.action = refundRoute;
            
            const selectReason = document.getElementById('refund_reason');
            const customGroup = document.getElementById('customReasonGroup');
            const customInput = document.getElementById('custom_reason');
            
            if (refundReason) {
                selectReason.value = 'Other';
                customGroup.style.display = 'block';
                customInput.required = true;
                customInput.name = 'refund_reason';
                selectReason.removeAttribute('name');
                customInput.value = refundReason;
            } else {
                selectReason.value = 'Incorrect Order Items';
                customGroup.style.display = 'none';
                customInput.required = false;
                customInput.value = '';
                selectReason.name = 'refund_reason';
                customInput.removeAttribute('name');
            }
            
            modal.style.display = 'flex';
        }

        function closeRefundModal() {
            const modal = document.getElementById('refundModal');
            modal.style.display = 'none';
        }

        function toggleCustomReason(value) {
            const customGroup = document.getElementById('customReasonGroup');
            const customInput = document.getElementById('custom_reason');
            const selectReason = document.getElementById('refund_reason');
            
            if (value === 'Other') {
                customGroup.style.display = 'block';
                customInput.required = true;
                customInput.name = 'refund_reason';
                selectReason.removeAttribute('name');
                customInput.focus();
            } else {
                customGroup.style.display = 'none';
                customInput.required = false;
                customInput.removeAttribute('name');
                selectReason.name = 'refund_reason';
            }
        }

        // Close modal if user clicks outside the modal content card
        window.onclick = function(event) {
            const modal = document.getElementById('refundModal');
            if (event.target === modal) {
                closeRefundModal();
            }
        }
    </script>
@endsection
