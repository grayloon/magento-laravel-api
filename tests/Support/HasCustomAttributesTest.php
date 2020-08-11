<?php

namespace Grayloon\Magento\Tests\Support;

use Grayloon\Magento\Jobs\UpdateProductAttributeGroup;
use Grayloon\Magento\Models\MagentoCustomAttributeType;
use Grayloon\Magento\Support\HasCustomAttributes;
use Grayloon\Magento\Tests\TestCase;
use Illuminate\Support\Facades\Queue;

class HasCustomAttributesTest extends TestCase
{
    public function test_resolves_new_custom_attribute_type()
    {
        Queue::fake();
        
        $newType = (new FakeSupportingClass)->exposedResolveCustomAttributeType('foo_bar');

        $this->assertNotEmpty($newType);
        $this->assertEquals('foo_bar', $newType->name);
        $this->assertEquals('Foo Bar', $newType->display_name);
        $this->assertIsArray($newType->options);
        $this->assertEmpty($newType->options);
        Queue::assertPushed(UpdateProductAttributeGroup::class, fn ($job) => $job->type->id === $newType->id);
    }

    public function test_resolves_existing_custom_attribute_type()
    {
        Queue::fake();
        $existing = factory(MagentoCustomAttributeType::class)->create([
            'name' => 'foo_bar',
        ]);
        
        $type = (new FakeSupportingClass)->exposedResolveCustomAttributeType('foo_bar');

        $this->assertNotEmpty($type);
        $this->assertEquals($type->id, $existing->id);
        $this->assertEquals(1, MagentoCustomAttributeType::count());
        Queue::assertNothingPushed();
    }

    public function test_resolves_existing_raw_value_from_empty_options()
    {
        $type = factory(MagentoCustomAttributeType::class)->create([
            'name' => 'foo_bar',
        ]);
        
        $value = (new FakeSupportingClass)->exposedResolveCustomAttributeValue($type, 'foo');

        $this->assertEquals('foo', $value);
    }

    public function test_resolves_correct_value_from_provided_options()
    {
        $type = factory(MagentoCustomAttributeType::class)->create([
            'name' => 'foo_bar',
            'options' => [
                [
                    'label' => 'New York',
                    'value' => '1',
                ],
                [
                    'label' => 'Los Angeles',
                    'value' => '2',
                ],
            ],
        ]);
        
        $value = (new FakeSupportingClass)->exposedResolveCustomAttributeValue($type, '1');

        $this->assertEquals('New York', $value);
    }

    public function test_resolves_raw_value_from_option_not_supplied_in_options()
    {
        $type = factory(MagentoCustomAttributeType::class)->create([
            'name' => 'foo_bar',
            'options' => [
                [
                    'label' => 'New York',
                    'value' => '1',
                ],
                [
                    'label' => 'Los Angeles',
                    'value' => '2',
                ],
            ],
        ]);
        
        $value = (new FakeSupportingClass)->exposedResolveCustomAttributeValue($type, 'Unknown');

        $this->assertEquals('Unknown', $value);
    }
}

class FakeSupportingClass
{
    use HasCustomAttributes;

    public function exposedResolveCustomAttributeType($attributeCode)
    {
        return $this->resolveCustomAttributeType($attributeCode);
    }

    public function exposedResolveCustomAttributeValue($type, $value)
    {
        return $this->resolveCustomAttributeValue($type, $value);
    }
}
