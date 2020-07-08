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
     * The Magento Product has many Ext Attributes.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ExtAttributes()
    {
        return $this->hasMany(MagentoExtAttribute::class);
    }

    /**
     * The Magento Product has many Custom Attributes.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function CustAttributes()
    {
        return $this->hasMany(MagentoCustAttribute::class);
    }
}
