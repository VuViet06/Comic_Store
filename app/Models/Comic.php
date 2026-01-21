<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comic extends Model
{

    protected $fillable = [
        'category_id',
        'publisher_id',
        'title',
        'slug',
        'description',
        'author',
        'published_year',
        'edition_type',
        'condition',
        'series',
        'volume',
        'price',
        'cover',
        'stock',
        'is_active',
    ];


    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
        'published_year' => 'integer',
        'volume' => 'integer',
        'is_active' => 'boolean',
    ];


    public function category()
    {
        return $this->belongsTo(Category::class);
    }


    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }

    public function inventoryTransactions()
    {
        return $this->hasMany(InventoryTransaction::class);
    }


    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }


    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }
}
