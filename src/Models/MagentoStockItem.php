<?php

namespace Grayloon\Magento\Models;

use Illuminate\Database\Eloquent\Model;

class MagentoStockItem extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'is_in_stock',
        'item_id',
        'product_id',
        'qty',
        'stock_id',
        'synced_at',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'synced_at'];

    /**
     * Product Id belongs to the Magento Product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(MagentoProduct::class, 'product_id');
    }
}
