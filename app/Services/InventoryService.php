<?php

namespace App\Services;

use App\Models\Comic;
use App\Models\InventoryTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class InventoryService
{
    /**
     * Giảm tồn kho (khi bán)
     */
    public function reduceStock($comicId, $quantity, $orderId = null, $userId = null)
    {
        return DB::transaction(function () use ($comicId, $quantity, $orderId, $userId) {
            $comic = Comic::lockForUpdate()->findOrFail($comicId);

            if ($comic->stock < $quantity) {
                throw new \Exception("Không đủ tồn kho. Chỉ còn {$comic->stock} quyển.");
            }

            $comic->stock -= $quantity;
            $comic->save();

            InventoryTransaction::create([
                'comic_id' => $comicId,
                'type' => 'sale',
                'quantity_change' => -$quantity,
                'order_id' => $orderId,
                'user_id' => $userId ?? Auth::id(),
                'note' => $orderId ? "Bán hàng - Đơn #{$orderId}" : "Bán hàng",
            ]);

            return $comic;
        });
    }

    /**
     * Hoàn trả tồn kho (khi hủy đơn, hoàn trả)
     */
    public function restoreStock($comicId, $quantity, $orderId = null, $userId = null)
    {
        return DB::transaction(function () use ($comicId, $quantity, $orderId, $userId) {
            $comic = Comic::lockForUpdate()->findOrFail($comicId);
            $comic->stock += $quantity;
            $comic->save();

            InventoryTransaction::create([
                'comic_id' => $comicId,
                'type' => 'return',
                'quantity_change' => $quantity,
                'order_id' => $orderId,
                'user_id' => $userId ?? Auth::id(),
                'note' => $orderId ? "Hoàn trả - Đơn #{$orderId}" : "Hoàn trả hàng",
            ]);

            return $comic;
        });
    }

    /**
     * Nhập thêm hàng
     */
    public function addStock($comicId, $quantity, $userId = null, $notes = null)
    {
        return DB::transaction(function () use ($comicId, $quantity, $userId, $notes) {
            $comic = Comic::lockForUpdate()->findOrFail($comicId);
            $comic->stock += $quantity;
            $comic->save();

            InventoryTransaction::create([
                'comic_id' => $comicId,
                'type' => 'import',
                'quantity_change' => $quantity,
                'user_id' => $userId ?? Auth::id(),
                'note' => $notes ?? "Nhập hàng",
            ]);

            return $comic;
        });
    }

    /**
     * Điều chỉnh tồn kho (tăng/giảm tùy ý)
     */
    public function adjustStock($comicId, $quantityChange, $userId = null, $reason = null)
    {
        return DB::transaction(function () use ($comicId, $quantityChange, $userId, $reason) {
            $comic = Comic::lockForUpdate()->findOrFail($comicId);
            
            if ($quantityChange < 0 && $comic->stock < abs($quantityChange)) {
                throw new \Exception("Không thể giảm quá tồn kho hiện tại.");
            }

            $comic->stock += $quantityChange;
            $comic->save();

            InventoryTransaction::create([
                'comic_id' => $comicId,
                'type' => 'adjustment',
                'quantity_change' => $quantityChange,
                'user_id' => $userId ?? Auth::id(),
                'note' => $reason ?? "Điều chỉnh tồn kho",
            ]);

            return $comic;
        });
    }

    /**
     * Lấy lịch sử giao dịch tồn kho
     */
    public function getHistory($comicId, $limit = 50)
    {
        return InventoryTransaction::where('comic_id', $comicId)
            ->with(['user', 'order'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Kiểm tra có đủ hàng không
     */
    public function hasStock($comicId, $quantity)
    {
        $comic = Comic::find($comicId);
        return $comic && $comic->stock >= $quantity;
    }

    /**
     * Lấy số lượng tồn kho hiện tại
     */
    public function getAvailableStock($comicId)
    {
        $comic = Comic::find($comicId);
        return $comic ? $comic->stock : 0;
    }
}
