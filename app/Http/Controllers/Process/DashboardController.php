<?php

namespace App\Http\Controllers\Process;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class DashboardController extends Controller
{
    /**
     * Update order status
     */
    public function updateOrderStatus(Request $request, $order)
    {
        $order = Order::findOrFail($order);
        
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,completed,cancelled,refunded',
            'seller_message' => 'nullable|string|max:255',
        ]);
        
        $order->update($validated);
        
        return redirect()->route('admin.orders.show', $order->order_id)
            ->with('success', 'Order status updated successfully');
    }
}