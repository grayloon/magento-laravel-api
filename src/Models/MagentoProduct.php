<?php

namespace Grayloon\Magento\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class MagentoProduct extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'synced_at'];

    /**
     * The Magento Product custom attributes.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function customAttributes()
    {
        return $this->morphMany(MagentoCustomAttribute::class, 'attributable');
    }

    /**
     * The Magento Product has many Ext Attributes.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function extensionAttributes()
    {
        return $this->hasMany(MagentoExtensionAttribute::class);
    }

    /**
     * The categories assigned to the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function categories()
    {
        return $this->hasManyThrough(MagentoCategory::class, MagentoProductCategory::class, 'magento_product_id', 'id', 'id', 'magento_category_id');
    }

    /**
     * Helper to quickly get a value from a custom attribute.
     *
     * @param  string  $key
     * @return mixed
     */
    public function customAttributeValue($key)
    {
        $attribute = $this->customAttributes->where('attribute_type', $key)->first();

        return $attribute ? $attribute->value : null;
    }

    /**
     * Helper to easily get the product image.
     *
     * @return null|string
     */
    public function productImage()
    {
        $attribute = $this->customAttributes->where('attribute_type', 'image')->first();

        if ($attribute && Storage::exists('public/product/'.$attribute->value)) {
            return 'product/'.$attribute->value;
        }

        return null;
    }

    /**
     * The related Magento products.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function related()
    {
        return $this->hasManyThrough(MagentoProduct::class, MagentoProductLink::class, 'product_id', 'id', 'id', 'related_product_id')
            ->orderBy('position');
    }
}
