<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class WebsiteCartController extends Controller
{
    public function index()
    {
        $cartItems = $this->getCartItems();
        $cartData = $this->calculateCartTotals($cartItems);

        return view('website.cart', array_merge($cartData, ['cartItems' => $cartItems]));
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|string',
            'quantity' => 'required|integer|min:1|max:10'
        ]);

        try {
            $product = Product::with(['category', 'shop'])
                ->where('uuid', $request->product_id)
                ->orWhere('id', $request->product_id)
                ->firstOrFail();

            // Check stock availability
            if (!$product->is_in_stock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product is out of stock'
                ], 400);
            }

            if ($product->isLowStock() && $request->quantity > $product->stock_quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Only ' . $product->stock_quantity . ' items available in stock'
                ], 400);
            }

            $cart = session()->get('cart', []);
            $cartKey = $this->generateCartKey($product->uuid);

            if (isset($cart[$cartKey])) {
                // Update existing item quantity
                $newQuantity = $cart[$cartKey]['quantity'] + $request->quantity;
                if ($newQuantity > 10) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Maximum quantity limit reached (10 items per product)'
                    ], 400);
                }
                $cart[$cartKey]['quantity'] = $newQuantity;
            } else {
                // Add new item to cart
                $cart[$cartKey] = [
                    'id' => $product->uuid,
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'shop_name' => $product->shop->name ?? 'Unknown Shop',
                    'category' => $product->category->name ?? 'Uncategorized',
                    'price' => $product->sale_price ?? $product->base_price,
                    'original_price' => $product->base_price,
                    'quantity' => $request->quantity,
                    'image' => $product->main_image_url ? asset('website/' . $product->main_image_url) : 'https://via.placeholder.com/100',
                    'max_quantity' => min(10, $product->stock_quantity),
                    'is_in_stock' => $product->is_in_stock
                ];
            }

            session()->put('cart', $cart);
            $cartCount = $this->getCartCount();

            return response()->json([
                'success' => true,
                'message' => 'Product added to cart successfully',
                'cart_count' => $cartCount,
                'cart_item' => $cart[$cartKey]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add product to cart: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|string',
            'quantity' => 'required|integer|min:1|max:10'
        ]);

        try {
            $cart = session()->get('cart', []);
            $cartKey = $this->generateCartKey($request->product_id);

            if (isset($cart[$cartKey])) {
                // Check stock availability
                $product = Product::where('uuid', $request->product_id)->first();
                if ($product && $product->isLowStock() && $request->quantity > $product->stock_quantity) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Only ' . $product->stock_quantity . ' items available in stock'
                    ], 400);
                }

                $cart[$cartKey]['quantity'] = $request->quantity;
                $cart[$cartKey]['max_quantity'] = $product ? min(10, $product->stock_quantity) : 10;
                session()->put('cart', $cart);

                $cartData = $this->calculateCartTotals($cart);

                return response()->json([
                    'success' => true,
                    'message' => 'Cart updated successfully',
                    'cart_data' => $cartData
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Product not found in cart'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update cart: ' . $e->getMessage()
            ], 500);
        }
    }

    public function removeFromCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|string'
        ]);

        try {
            $cart = session()->get('cart', []);
            $cartKey = $this->generateCartKey($request->product_id);

            if (isset($cart[$cartKey])) {
                unset($cart[$cartKey]);
                session()->put('cart', $cart);

                $cartData = $this->calculateCartTotals($cart);
                $cartCount = $this->getCartCount();

                return response()->json([
                    'success' => true,
                    'message' => 'Product removed from cart',
                    'cart_count' => $cartCount,
                    'cart_data' => $cartData
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Product not found in cart'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove product from cart: ' . $e->getMessage()
            ], 500);
        }
    }

    public function clearCart()
    {
        try {
            session()->forget('cart');

            return response()->json([
                'success' => true,
                'message' => 'Cart cleared successfully',
                'cart_count' => 0
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear cart: ' . $e->getMessage()
            ], 500);
        }
    }

    // public function getCartCount()
    // {
    //     $cartCount = $this->getCartCount();
    //     return response()->json(['cart_count' => $cartCount]);
    // }

    // Helper Methods
    private function getCartItems()
    {
        return session()->get('cart', []);
    }

    private function getCartCount()
    {
        $cart = session()->get('cart', []);
        return array_sum(array_column($cart, 'quantity'));
    }

    private function generateCartKey($productId)
    {
        return 'product_' . $productId;
    }

    private function calculateCartTotals($cartItems)
    {
        $subtotal = 0;

        foreach ($cartItems as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        // Calculate shipping (free over 2000, otherwise 100)
        $shipping = $subtotal > 2000 ? 0 : 100;

        // Calculate tax (10% of subtotal)
        $tax = $subtotal * 0.10;

        // Calculate discount (5% of subtotal if over 1000)
        $discount = $subtotal > 1000 ? $subtotal * 0.05 : 0;

        $total = $subtotal + $shipping + $tax - $discount;

        return [
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'tax' => $tax,
            'discount' => $discount,
            'total' => $total
        ];
    }
}
