<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'shop', 'orderItems', 'coupon'])
            ->latest();

        // Filter by shop if user is shop owner and has a shop
        if (auth()->user()->hasRole('shop-owner')) {
            $shop = auth()->user()->shop;

            // Check if user has a shop
            if (!$shop) {
                // If shop owner doesn't have a shop, return empty results with a message
                $orders = collect([]);
                $shops = collect([]);
                $orderStats = $this->getEmptyOrderStats();

                return view('dashboard.orders.index', compact('orders', 'shops', 'orderStats'))
                    ->with('warning', 'You need to create a shop first to manage orders.');
            }

            $query->where('shop_id', $shop->id);
        }

        // Apply filters
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('payment_status') && $request->payment_status) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->has('shop_id') && $request->shop_id && auth()->user()->hasRole('super-admin')) {
            $query->where('shop_id', $request->shop_id);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('shipping_email', 'like', "%{$search}%")
                    ->orWhere('shipping_phone', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $orders = $query->paginate(20);

        $shops = Shop::where('is_active', true)->get();

        $orderStats = $this->getOrderStats();

        return view('dashboard.orders.index', compact('orders', 'shops', 'orderStats'));
    }

    public function show(Order $order)
    {
        // Authorization check for shop owners
        if (auth()->user()->hasRole('shop-owner')) {
            $shop = auth()->user()->shop;

            if (!$shop) {
                abort(403, 'You need to create a shop first to view orders.');
            }

            if ($order->shop_id !== $shop->id) {
                abort(403, 'Unauthorized action.');
            }
        }

        $order->load(['user', 'shop', 'orderItems.product', 'coupon', 'couponUsage']);

        return view('dashboard.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
            'notes' => 'nullable|string|max:500'
        ]);

        // Authorization check for shop owners
        if (auth()->user()->hasRole('shop-owner')) {
            $shop = auth()->user()->shop;

            if (!$shop) {
                return response()->json(['success' => false, 'message' => 'You need to create a shop first to manage orders.'], 403);
            }

            if ($order->shop_id !== $shop->id) {
                return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
            }
        }

        try {
            DB::beginTransaction();

            $oldStatus = $order->status;
            $newStatus = $request->status;

            $order->update([
                'status' => $newStatus,
                'admin_notes' => $request->notes ?: $order->admin_notes
            ]);

            // Update timestamps based on status
            $timestampField = $newStatus . '_at';
            if (!$order->$timestampField) {
                $order->update([$timestampField => now()]);
            }

            // If order is cancelled and payment was made, handle refund
            if ($newStatus === 'cancelled' && $order->payment_status === 'paid') {
                $order->update(['payment_status' => 'refunded']);
            }

            // Log status change
            activity()
                ->performedOn($order)
                ->causedBy(auth()->user())
                ->withProperties([
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'notes' => $request->notes
                ])
                ->log('order_status_updated');

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order status updated successfully.',
                'order' => $order->fresh()
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update order status: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updatePaymentStatus(Request $request, Order $order)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,paid,failed,refunded,partially_refunded'
        ]);

        // Authorization check for shop owners
        if (auth()->user()->hasRole('shop-owner')) {
            $shop = auth()->user()->shop;

            if (!$shop) {
                return response()->json(['success' => false, 'message' => 'You need to create a shop first to manage orders.'], 403);
            }

            if ($order->shop_id !== $shop->id) {
                return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
            }
        }

        try {
            $oldStatus = $order->payment_status;
            $newStatus = $request->payment_status;

            $updateData = ['payment_status' => $newStatus];

            if ($newStatus === 'paid' && !$order->paid_at) {
                $updateData['paid_at'] = now();
            }

            $order->update($updateData);

            // Log payment status change
            activity()
                ->performedOn($order)
                ->causedBy(auth()->user())
                ->withProperties([
                    'old_payment_status' => $oldStatus,
                    'new_payment_status' => $newStatus
                ])
                ->log('payment_status_updated');

            return response()->json([
                'success' => true,
                'message' => 'Payment status updated successfully.',
                'order' => $order->fresh()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update payment status: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateShippingInfo(Request $request, Order $order)
    {
        $request->validate([
            'tracking_number' => 'nullable|string|max:100',
            'shipping_carrier' => 'nullable|string|max:100',
            'estimated_delivery_date' => 'nullable|date'
        ]);

        // Authorization check for shop owners
        if (auth()->user()->hasRole('shop-owner')) {
            $shop = auth()->user()->shop;

            if (!$shop) {
                return response()->json(['success' => false, 'message' => 'You need to create a shop first to manage orders.'], 403);
            }

            if ($order->shop_id !== $shop->id) {
                return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
            }
        }

        try {
            $order->update([
                'tracking_number' => $request->tracking_number,
                'shipping_carrier' => $request->shipping_carrier,
                'estimated_delivery_date' => $request->estimated_delivery_date,
                'metadata' => array_merge($order->metadata ?? [], [
                    'shipping_updated_at' => now(),
                    'updated_by' => auth()->id()
                ])
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Shipping information updated successfully.',
                'order' => $order->fresh()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update shipping information: ' . $e->getMessage()
            ], 500);
        }
    }

    public function addAdminNotes(Request $request, Order $order)
    {
        $request->validate([
            'admin_notes' => 'required|string|max:1000'
        ]);

        // Authorization check for shop owners
        if (auth()->user()->hasRole('shop-owner')) {
            $shop = auth()->user()->shop;

            if (!$shop) {
                return response()->json(['success' => false, 'message' => 'You need to create a shop first to manage orders.'], 403);
            }

            if ($order->shop_id !== $shop->id) {
                return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
            }
        }

        try {
            $order->update(['admin_notes' => $request->admin_notes]);

            activity()
                ->performedOn($order)
                ->causedBy(auth()->user())
                ->log('admin_notes_updated');

            return response()->json([
                'success' => true,
                'message' => 'Admin notes updated successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update admin notes: ' . $e->getMessage()
            ], 500);
        }
    }

    public function exportOrders(Request $request)
    {
        $query = Order::with(['user', 'shop', 'orderItems']);

        // Apply filters same as index
        if (auth()->user()->hasRole('shop-owner')) {
            $shop = auth()->user()->shop;

            if (!$shop) {
                return response()->json([
                    'success' => false,
                    'message' => 'You need to create a shop first to export orders.'
                ], 403);
            }

            $query->where('shop_id', $shop->id);
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $orders = $query->get();

        // Generate CSV
        $fileName = 'orders-' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
        ];

        $callback = function () use ($orders) {
            $file = fopen('php://output', 'w');

            // Headers
            fputcsv($file, [
                'Order Number',
                'Customer Name',
                'Customer Email',
                'Customer Phone',
                'Shop',
                'Total Amount',
                'Status',
                'Payment Status',
                'Payment Method',
                'Order Date',
                'Items Count'
            ]);

            // Data
            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->order_number,
                    $order->shipping_first_name . ' ' . $order->shipping_last_name,
                    $order->shipping_email,
                    $order->shipping_phone,
                    $order->shop->name,
                    $order->total_amount,
                    $order->status,
                    $order->payment_status,
                    $order->payment_method,
                    $order->created_at->format('Y-m-d H:i:s'),
                    $order->orderItems->count()
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function getOrderStats()
    {
        $query = Order::query();

        if (auth()->user()->hasRole('shop-owner')) {
            $shop = auth()->user()->shop;

            if (!$shop) {
                return $this->getEmptyOrderStats();
            }

            $query->where('shop_id', $shop->id);
        }

        return [
            'total' => $query->count(),
            'pending' => (clone $query)->where('status', 'pending')->count(),
            'confirmed' => (clone $query)->where('status', 'confirmed')->count(),
            'processing' => (clone $query)->where('status', 'processing')->count(),
            'shipped' => (clone $query)->where('status', 'shipped')->count(),
            'delivered' => (clone $query)->where('status', 'delivered')->count(),
            'cancelled' => (clone $query)->where('status', 'cancelled')->count(),
            'revenue' => (clone $query)->where('payment_status', 'paid')->sum('total_amount')
        ];
    }

    private function getEmptyOrderStats()
    {
        return [
            'total' => 0,
            'pending' => 0,
            'confirmed' => 0,
            'processing' => 0,
            'shipped' => 0,
            'delivered' => 0,
            'cancelled' => 0,
            'revenue' => 0
        ];
    }
}
