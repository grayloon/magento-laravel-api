<?php

namespace Grayloon\Magento\Models;

use Illuminate\Database\Eloquent\Model;

class MagentoExtAttribute extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'attribute' => 'json',
    ];

    /**
     * The Magento Extension Attribute type id belongs to the type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Type()
    {
        return $this->belongsTo(MagentoExtAttributeType::class, 'magento_ext_attribute_type_id');
    }
}
