<?php

namespace Grayloon\Magento\Support;

use Grayloon\Magento\Models\MagentoExtensionAttribute;
use Grayloon\Magento\Models\MagentoExtensionAttributeType;

trait HasExtensionAttributes
{
    /**
     * Sync the Magento 2 Extension attributes with the associated model.
     *
     * @param  array  $attributes
     * @param  mixed  $product
     * @return void
     */
    protected function syncExtensionAttributes($attributes, $model)
    {
        foreach ($attributes as $key => $attribute) {
            $type = MagentoExtensionAttributeType::firstOrCreate(['type' => $key]);

            MagentoExtensionAttribute::updateOrCreate([
                'magento_product_id'            => $model->id,
                'magento_ext_attribute_type_id' => $type->id,
            ], ['attribute' => $attribute]);
        }

        return $this;
    }
}
