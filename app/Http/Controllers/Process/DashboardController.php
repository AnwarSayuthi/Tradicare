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
            'tracking_number' => 'nullable|string|max:100',
        ]);
        
        // Update order status
        $order->update([
            'status' => $validated['status'],
            'seller_message' => $validated['seller_message'] ?? null,
        ]);
        
        // Update or create tracking information when status is shipped or delivered
        if (in_array($validated['status'], ['shipped', 'delivered']) && !empty($validated['tracking_number'])) {
            $order->tracking()->updateOrCreate(
                ['order_id' => $order->order_id],
                ['tracking_number' => $validated['tracking_number']]
            );
        }
        
        return redirect()->route('admin.orders.show', $order->order_id)
            ->with('success', 'Order status updated successfully');
    }
}