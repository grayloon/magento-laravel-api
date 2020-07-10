<?php

namespace Grayloon\Magento\Models;

use Illuminate\Database\Eloquent\Model;

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
     * Get all of the magento product custom attributes.
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
    public function ExtAttributes()
    {
        return $this->hasMany(MagentoExtAttribute::class);
    }
}
