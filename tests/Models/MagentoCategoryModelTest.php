<?php

namespace Grayloon\Magento\Tests;

use Grayloon\Magento\Models\MagentoCategory;
use Grayloon\Magento\Models\MagentoCustomAttributeType;
use Grayloon\Magento\Models\MagentoProduct;
use Grayloon\Magento\Models\MagentoProductCategory;

class MagentoCategoryModelTest extends TestCase
{
    public function test_can_create_magento_category()
    {
        $category = factory(MagentoCategory::class)->create();

        $this->assertNotEmpty($category);
    }

    public function test_magento_category_can_have_parent_category()
    {
        $category = factory(MagentoCategory::class)->create([
            'parent_id' => $parent = factory(MagentoCategory::class)->create(),
        ]);

        $this->assertNotEmpty($category, $parent);
        $this->assertEquals($category->parent()->first()->id, $parent->id);
        $this->assertNotEquals($category->parent()->first()->id, $category->id);
    }

    public function test_can_add_custom_attributes_to_magento_category()
    {
        $category = factory(MagentoCategory::class)->create();

        $attribute = $category->customAttributes()->updateOrCreate([
            'attribute_type'    => 'foo',
            'attribute_type_id' => factory(MagentoCustomAttributeType::class)->create(),
            'value'             => 'bar',
        ]);

        $this->assertNotEmpty($attribute);
        $this->assertEquals('foo', $attribute->attribute_type);
        $this->assertEquals('bar', $attribute->value);
        $this->assertEquals(MagentoCategory::class, $attribute->attributable_type);
        $this->assertEquals($category->id, $attribute->attributable_id);
    }

    public function test_magento_category_can_get_single_product()
    {
        factory(MagentoCategory::class)->create(); // create non-assigned category.
        factory(MagentoProduct::class)->create(); // create non-assigned category.

        $category = factory(MagentoCategory::class)->create();
        factory(MagentoProductCategory::class)->create([
            'magento_category_id' => $category->id,
        ]);

        $products = $category->products()->get();
        $this->assertNotEmpty($products);
        $this->assertEquals(1, $products->count());
        $this->assertInstanceOf(MagentoProduct::class, $products->first());
    }
}
