<?php

namespace App\Http\Controllers;

use App\Services\CheckoutService;
use App\Services\CartService;
use App\Http\Requests\CheckoutRequest;
use App\Http\Requests\ApplyVoucherRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    protected CheckoutService $checkoutService;
    protected CartService $cartService;

    public function __construct(
        CheckoutService $checkoutService,
        CartService $cartService
    ) {
        $this->checkoutService = $checkoutService;
        $this->cartService = $cartService;
    }

    /**
     * Hiển thị form checkout
     */
    public function show(Request $request)
    {
        // Kiểm tra giỏ hàng
        if ($this->cartService->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        // Validate cart
        try {
            $this->cartService->validate();
        } catch (\Exception $e) {
            return redirect()->route('cart.index')
                ->with('error', $e->getMessage());
        }

        $cartSummary = $this->cartService->getSummary();
        $user = Auth::user();

        // Prefill thông tin nếu user đã login
        $defaultData = [];
        if ($user) {
            $defaultData = [
                'customer_name' => $user->name,
                'customer_phone' => $user->phone ?? '',
            ];
        }

        return view('checkout.show', compact('cartSummary', 'defaultData'));
    }

    /**
     * Xử lý checkout và tạo đơn hàng
     */
    public function process(CheckoutRequest $request)
    {
        // Kiểm tra giỏ hàng
        if ($this->cartService->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        try {
            $cartItems = $this->cartService->getItems();
            
            // Validate cart
            $this->checkoutService->validateCart($cartItems);

            // Tạo đơn hàng
            $order = $this->checkoutService->createOrder(
                $cartItems,
                $request->validated(),
                $request->voucher_id,
                Auth::id()
            );

            // Xóa giỏ hàng
            $this->cartService->clear();

            // Redirect đến trang thành công
            return redirect()->route('checkout.success', ['code' => $order->code])
                ->with('success', 'Đặt hàng thành công! Mã đơn hàng: ' . $order->code);

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Áp dụng voucher
     */
    public function applyVoucher(ApplyVoucherRequest $request)
    {
        try {
            $cartItems = $this->cartService->getItems();
            $subtotal = $this->cartService->getSubtotal();

            $result = $this->checkoutService->applyVoucher(
                $request->voucher_code,
                $subtotal,
                Auth::id()
            );

            return response()->json([
                'success' => true,
                'message' => 'Áp dụng mã giảm giá thành công',
                'discount' => $result['discount'],
                'voucher_id' => $result['voucher_id'],
                'total' => $subtotal - $result['discount'],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Xóa voucher
     */
    public function removeVoucher(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Đã xóa mã giảm giá',
            'discount' => 0,
            'voucher_id' => null,
            'total' => $this->cartService->getSubtotal(),
        ]);
    }

    /**
     * Trang thành công
     */
    public function success(Request $request)
    {
        $code = $request->get('code');
        
        if (!$code) {
            return redirect()->route('home');
        }

        $order = $this->checkoutService->getOrderByCode($code);

        if (!$order) {
            return redirect()->route('home')
                ->with('error', 'Không tìm thấy đơn hàng.');
        }

        return view('checkout.success', compact('order'));
    }

    /**
     * Preview đơn hàng (AJAX)
     */
    public function preview(Request $request)
    {
        $cartSummary = $this->cartService->getSummary();
        $voucherCode = $request->get('voucher_code');

        $totals = $this->checkoutService->calculateTotals(
            $cartSummary['items'],
            $voucherCode,
            Auth::id()
        );

        return response()->json([
            'subtotal' => $totals['subtotal'],
            'discount' => $totals['discount'],
            'total' => $totals['total'],
            'voucher_id' => $totals['voucher_id'],
        ]);
    }
}
