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

        (new FakeSupportingExtensionClass)->exposedSyncExtensionAttributes(['foo' => 'bar'], $product);

        $this->assertEquals(1, MagentoExtensionAttributeType::count());
        $this->assertEquals(1, MagentoExtensionAttribute::count());
        $this->assertEquals('foo', MagentoExtensionAttributeType::first()->type);
        $this->assertEquals('bar', MagentoExtensionAttribute::first()->attribute);
    }

    public function test_resolves_existing_extension_attribute_type()
    {
        $product = factory(MagentoProduct::class)->create();
        factory(MagentoExtensionAttributeType::class)->create([
            'type' => 'foo',
        ]);

        (new FakeSupportingExtensionClass)->exposedSyncExtensionAttributes(['foo' => 'bar'], $product);

        $this->assertEquals(1, MagentoExtensionAttributeType::count());
        $this->assertEquals(1, MagentoExtensionAttribute::count());
        $this->assertEquals('foo', MagentoExtensionAttributeType::first()->type);
        $this->assertEquals('bar', MagentoExtensionAttribute::first()->attribute);
    }
}

class FakeSupportingExtensionClass
{
    use HasExtensionAttributes;

    public function exposedSyncExtensionAttributes($attributes, $model)
    {
        return $this->syncExtensionAttributes($attributes, $model);
    }
}
