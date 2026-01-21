<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use App\Http\Requests\AddToCartRequest;
use App\Http\Requests\UpdateCartRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CartController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Trang giỏ hàng
     */
    public function index(Request $request)
    {
        $summary = $this->cartService->getSummary();
        return view('cart.index', $summary);
    }

    /**
     * Thêm vào giỏ hàng
     */
    public function add(AddToCartRequest $request): JsonResponse
    {
        try {
            $item = $this->cartService->add(
                $request->comic_id,
                $request->quantity
            );

            return response()->json([
                'success' => true,
                'message' => 'Đã thêm vào giỏ hàng',
                'cart_count' => $this->cartService->getCount(),
                'item' => $item,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Cập nhật số lượng
     */
    public function update(UpdateCartRequest $request): JsonResponse
    {
        try {
            $item = $this->cartService->updateQuantity(
                $request->comic_id,
                $request->quantity
            );

            $summary = $this->cartService->getSummary();

            return response()->json([
                'success' => true,
                'message' => 'Đã cập nhật giỏ hàng',
                'cart_count' => $this->cartService->getCount(),
                'subtotal' => $summary['subtotal'],
                'item' => $item,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Xóa item khỏi giỏ hàng
     */
    public function remove(Request $request): JsonResponse
    {
        $request->validate([
            'comic_id' => 'required|integer|exists:comics,id',
        ]);

        $this->cartService->remove($request->comic_id);

        $summary = $this->cartService->getSummary();

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa khỏi giỏ hàng',
            'cart_count' => $this->cartService->getCount(),
            'subtotal' => $summary['subtotal'],
        ]);
    }

    /**
     * Xóa toàn bộ giỏ hàng
     */
    public function clear(): JsonResponse
    {
        $this->cartService->clear();

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa toàn bộ giỏ hàng',
        ]);
    }

    /**
     * API Lấy số lượng items trong giỏ
     */
    public function getCount(): JsonResponse
    {
        return response()->json([
            'count' => $this->cartService->getCount(),
        ]);
    }

    /**
     * API Lấy mini cart (dropdown)
     */
    public function getMiniCart(): JsonResponse
    {
        $summary = $this->cartService->getSummary();

        return response()->json([
            'items' => $summary['items'],
            'count' => $summary['count'],
            'subtotal' => $summary['subtotal'],
        ]);
    }
}
