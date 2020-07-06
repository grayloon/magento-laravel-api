<?php

namespace Grayloon\Magento\Models;

use Illuminate\Database\Eloquent\Model;

class MagentoCustAttribute extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The Magento Custom Attribute Type id belongs to the Custom Attribute Type.
     *
     * @return void
     */
    public function Type()
    {
        return $this->belongsTo(MagentoCustAttributeType::class, 'magento_cust_attribute_type_id');
    }
}
