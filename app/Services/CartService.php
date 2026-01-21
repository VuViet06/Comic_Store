<?php

namespace App\Services;

use App\Models\Comic;
use Illuminate\Support\Facades\Session;

class CartService
{
    const CART_SESSION_KEY = 'cart';

    /**
     * Thêm truyện vào giỏ hàng
     */
    public function add($comicId, $quantity)
    {
        $comic = Comic::active()->findOrFail($comicId);

        if ($comic->stock < $quantity) {
            throw new \Exception("Không đủ hàng. Chỉ còn {$comic->stock} quyển.");
        }

        $cart = Session::get(self::CART_SESSION_KEY, []);

        if (isset($cart[$comicId])) {
            $newQuantity = $cart[$comicId]['quantity'] + $quantity;
            if ($newQuantity > $comic->stock) {
                throw new \Exception("Số lượng vượt quá tồn kho. Chỉ còn {$comic->stock} quyển.");
            }
            $cart[$comicId]['quantity'] = $newQuantity;
        } else {
            $cart[$comicId] = [
                'comic_id' => $comicId,
                'quantity' => $quantity,
                'price' => $comic->price,
            ];
        }

        Session::put(self::CART_SESSION_KEY, $cart);
        return $cart[$comicId];
    }

    /**
     * Lấy danh sách items trong giỏ hàng với thông tin đầy đủ
     */
    public function getItems()
    {
        $cart = Session::get(self::CART_SESSION_KEY, []);
        $items = [];

        foreach ($cart as $comicId => $item) {
            $comic = Comic::with(['category', 'publisher'])->find($comicId);
            
            if (!$comic || !$comic->is_active) {
                // Xóa item không hợp lệ
                unset($cart[$comicId]);
                continue;
            }

            // Kiểm tra stock thực tế
            if ($comic->stock < $item['quantity']) {
                $item['quantity'] = $comic->stock;
                if ($comic->stock == 0) {
                    unset($cart[$comicId]);
                    continue;
                }
            }

            $items[] = [
                'comic_id' => $comicId,
                'comic' => $comic,
                'quantity' => $item['quantity'],
                'price' => $comic->price,
                'subtotal' => $comic->price * $item['quantity'],
            ];
        }

        Session::put(self::CART_SESSION_KEY, $cart);
        return $items;
    }

    /**
     * Cập nhật số lượng
     */
    public function updateQuantity($comicId, $newQuantity)
    {
        $comic = Comic::active()->findOrFail($comicId);

        if ($newQuantity <= 0) {
            return $this->remove($comicId);
        }

        if ($comic->stock < $newQuantity) {
            throw new \Exception("Không đủ hàng. Chỉ còn {$comic->stock} quyển.");
        }

        $cart = Session::get(self::CART_SESSION_KEY, []);

        if (isset($cart[$comicId])) {
            $cart[$comicId]['quantity'] = $newQuantity;
            $cart[$comicId]['price'] = $comic->price; // Cập nhật giá mới nhất
            Session::put(self::CART_SESSION_KEY, $cart);
            return $cart[$comicId];
        }

        throw new \Exception("Sản phẩm không có trong giỏ hàng.");
    }

    /**
     * Xóa item khỏi giỏ hàng
     */
    public function remove($comicId)
    {
        $cart = Session::get(self::CART_SESSION_KEY, []);
        unset($cart[$comicId]);
        Session::put(self::CART_SESSION_KEY, $cart);
        return true;
    }

    /**
     * Xóa toàn bộ giỏ hàng
     */
    public function clear()
    {
        Session::forget(self::CART_SESSION_KEY);
        return true;
    }

    /**
     * Đếm tổng số lượng items
     */
    public function getCount()
    {
        $cart = Session::get(self::CART_SESSION_KEY, []);
        return array_sum(array_column($cart, 'quantity'));
    }

    /**
     * Tính tổng tiền (chưa có discount)
     */
    public function getSubtotal()
    {
        $items = $this->getItems();
        return array_sum(array_column($items, 'subtotal'));
    }

    /**
     * Kiểm tra giỏ hàng có rỗng không
     */
    public function isEmpty()
    {
        $cart = Session::get(self::CART_SESSION_KEY, []);
        return empty($cart);
    }

    /**
     * Lấy summary giỏ hàng
     */
    public function getSummary()
    {
        $items = $this->getItems();
        $subtotal = $this->getSubtotal();

        return [
            'items' => $items,
            'count' => $this->getCount(),
            'subtotal' => $subtotal,
            'is_empty' => empty($items),
        ];
    }

    /**
     * Validate giỏ hàng trước khi checkout
     */
    public function validate()
    {
        $items = $this->getItems();
        $errors = [];

        foreach ($items as $item) {
            $comic = $item['comic'];
            if (!$comic->is_active) {
                $errors[] = "Truyện '{$comic->title}' đã ngừng bán.";
            }
            if ($comic->stock < $item['quantity']) {
                $errors[] = "Truyện '{$comic->title}' chỉ còn {$comic->stock} quyển.";
            }
        }

        if (!empty($errors)) {
            throw new \Exception(implode(' ', $errors));
        }

        return true;
    }
}
