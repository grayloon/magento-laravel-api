<?php

namespace Grayloon\Magento\Support;

use Grayloon\Magento\Jobs\UpdateProductAttributeGroup;
use Grayloon\Magento\Models\MagentoCustomAttribute;
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

    /**
     * Mass updates all Custom Attribute values from the resolved options.
     *
     * @param \Grayloon\Magento\Models\MagentoCustomAttributeType  $type
     * @return void
     */
    protected function updateCustomAttributeTypeValues($type)
    {
        MagentoCustomAttribute::where('attribute_type_id', $type->id)
            ->get()
            ->each(fn ($attribute) => $attribute->update([
                'value' => $this->resolveCustomAttributeValue($type, $attribute->value),
            ]));

        return $this;
    }

    /**
     * Sync the Magento Custom attributes with the associated model.
     *
     * @param  array  $attributes
     * @param  mixed  $model
     * @return void
     */
    protected function syncCustomAttributes($attributes, $model, $checkConditionalRules = false)
    {
        foreach ($attributes as $attribute) {
            // Custom rules set by the specified
            if ($checkConditionalRules) {
                $this->applyConditionalRules($attribute, $model);
            }

            $type = $this->resolveCustomAttributeType($attribute['attribute_code']);
            $value = $this->resolveCustomAttributeValue($type, $attribute['value']);

            $model
                ->customAttributes()
                ->updateOrCreate(['attribute_type_id' => $type->id], [
                    'attribute_type' => $attribute['attribute_code'],
                    'value'          => $value,
                ]);
        }

        return $this;
    }

    /**
     * Get a value from the provided custom attributes.
     *
     * @param  array  $apiCategory
     * @return string
     */
    protected function findAttributeByKey($key, $attributes)
    {
        foreach ($attributes as $attribute) {
            if ($attribute['attribute_code'] === $key) {
                return $attribute['value'];
            }
        }

        return null;
    }
}
