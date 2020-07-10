<?php

namespace Grayloon\Magento\Models;

use Grayloon\Magento\Models\MagentoCategory;
use Grayloon\Magento\Models\MagentoProduct;
use Illuminate\Database\Eloquent\Model;

class MagentoProductCategory extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;


    /**
     * The Magento Category ID belongs to the Magento Category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(MagentoCategory::class);
    }

    /**
     * The Magento Product ID belongs to the Magento Product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(MagentoProduct::class);
    }
}
