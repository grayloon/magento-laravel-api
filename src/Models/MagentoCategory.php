<?php

namespace Grayloon\Magento\Models;

use Illuminate\Database\Eloquent\Model;

class MagentoCategory extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The Parent id belongs to the Parent Category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo($this, 'parent_id');
    }

    /**
     * The Magento Category custom attributes.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function customAttributes()
    {
        return $this->morphMany(MagentoCustomAttribute::class, 'attributable');
    }

    /**
     * The products assigned to the category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function products()
    {
        return $this->hasManyThrough(MagentoProduct::class, MagentoProductCategory::class, 'magento_category_id', 'id', 'id', 'magento_product_id');
    }
}
