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
    public function ParentCategory()
    {
        return $this->belongsTo($this, 'parent_id');
    }
}
