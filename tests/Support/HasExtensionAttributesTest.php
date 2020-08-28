<?php

namespace Grayloon\Magento\Tests\Support;

use Grayloon\Magento\Models\MagentoExtensionAttribute;
use Grayloon\Magento\Models\MagentoExtensionAttributeType;
use Grayloon\Magento\Models\MagentoProduct;
use Grayloon\Magento\Support\HasExtensionAttributes;
use Grayloon\Magento\Tests\TestCase;

class HasExtensionAttributesTest extends TestCase
{
    public function test_resolves_new_extension_attribute_type()
    {
        $product = factory(MagentoProduct::class)->create();

        (new FakeSupportingClass)->exposedSyncExtensionAttributes(['foo' => 'bar'], $product);

        $this->assertEquals(1, MagentoExtensionAttributeType::count());
        $this->assertEquals(1, MagentoExtensionAttribute::count());
        $this->assertEquals('foo', MagentoExtensionAttributeType::first()->type);
        $this->assertEquals('bar', MagentoExtensionAttribute::first()->attribute);
    }

    public function test_resolves_existing_extension_attribute_type()
    {
        $product = factory(MagentoProduct::class)->create();
        $type = factory(MagentoExtensionAttributeType::class)->create([
            'type' => 'foo',
        ]);
        factory(MagentoExtensionAttribute::class)->create([
            'attribute' => 'bar',
            'magento_product_id' => $product->id,
            'magento_ext_attribute_type_id' => $type,
        ]);

        (new FakeSupportingClass)->exposedSyncExtensionAttributes(['foo' => 'bar'], $product);

        $this->assertEquals(1, MagentoExtensionAttributeType::count());
        $this->assertEquals(1, MagentoExtensionAttribute::count());
        $this->assertEquals('foo', MagentoExtensionAttributeType::first()->type);
        $this->assertEquals('bar', MagentoExtensionAttribute::first()->attribute);
    }

    public function test_resolves_existing_extension_attribute()
    {
        $product = factory(MagentoProduct::class)->create();
        $type = factory(MagentoExtensionAttributeType::class)->create([
            'type' => 'foo',
        ]);
        factory(MagentoExtensionAttribute::class)->create([
            'attribute' => 'bar',
            'magento_product_id' => $product->id,
            'magento_ext_attribute_type_id' => $type->id,
        ]);

        (new FakeSupportingClass)->exposedSyncExtensionAttributes(['foo' => 'bar'], $product);

        $this->assertEquals(1, MagentoExtensionAttributeType::count());
        $this->assertEquals(1, MagentoExtensionAttribute::count());
        $this->assertEquals('foo', MagentoExtensionAttributeType::first()->type);
        $this->assertEquals('bar', MagentoExtensionAttribute::first()->attribute);
    }
}

class FakeSupportingClass
{
    use HasExtensionAttributes;

    public function exposedSyncExtensionAttributes($attributes, $model)
    {
        return $this->syncExtensionAttributes($attributes, $model);
    }
}
