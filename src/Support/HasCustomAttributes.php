<?php

namespace Grayloon\Magento\Support;

use Grayloon\Magento\Jobs\UpdateProductAttributeGroup;
use Grayloon\Magento\Models\MagentoCustomAttributeType;
use Illuminate\Support\Str;

trait HasCustomAttributes
{
    /**
     * Resolve the Custom Attribute Type by the Attribute Code.
     *
     * @param  string  $attributeCode
     * @return \Grayloon\Magento\Models\MagentoCustomAttributeType
     */
    protected function resolveCustomAttributeType($attributeCode)
    {
        $type = MagentoCustomAttributeType::firstOrCreate(['name' => $attributeCode], [
            'display_name' => Str::title(Str::snake(Str::studly($attributeCode), ' ')),
            'options'      => [],
        ]);

        if ($type->wasRecentlyCreated) {
            UpdateProductAttributeGroup::dispatch($type);
        }

        return $type;
    }

    /**
     * Resolve the Custom Attribute Value by the provided options.
     *
     * @param  \Grayloon\Magento\Models\MagentoCustomAttributeType  $type
     * @param  string  $value;
     * @return string|null
     */
    protected function resolveCustomAttributeValue($type, $value)
    {
        if ($type->options) {
            foreach ($type->options as $option) {
                if ($option['value'] == $value) {
                    return $option['label'];
                }
            }
        }

        if (is_array($value)) {
            $value = json_encode($value);
        }

        return $value;
    }
}
