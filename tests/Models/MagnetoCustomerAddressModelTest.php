<?php

namespace Grayloon\Magento\Tests;

use Grayloon\Magento\Models\MagentoCustomAttribute;
use Grayloon\Magento\Models\MagentoCustomerAddress;

class MagnetoCustomerAddressModelTest extends TestCase
{
    public function test_can_create_magento_customer_address()
    {
        $address = factory(MagentoCustomerAddress::class)->create();

        $this->assertNotEmpty($address);
    }

    public function test_can_get_custom_attributes_on_magento_customer_address()
    {
        $address = factory(MagentoCustomerAddress::class)->create();

        factory(MagentoCustomAttribute::class)->create([
            'attributable_type'   => MagentoCustomerAddress::class,
            'attributable_id'     => $address->id,
        ]);

        $attributes = $address->customAttributes()->get();

        $this->assertNotEmpty($address, $attributes);
        $this->assertEquals(1, $attributes->count());
        $this->assertEquals(MagentoCustomerAddress::class, $attributes->first()->attributable_type);
    }

    public function test_can_update_instead_of_creating_row_custom_attributes_on_customer()
    {
        $address = factory(MagentoCustomerAddress::class)->create();

        factory(MagentoCustomAttribute::class)->create([
            'attributable_type'   => MagentoCustomerAddress::class,
            'attributable_id'     => $address->id,
            'attribute_type'      => 'foo',
            'value'               => 'bar',
        ]);

        $attribute = $address->customAttributes()->updateOrCreate(['attribute_type' => 'foo'], [
            'value'=> 'baz',
        ]);

        $this->assertEquals(1, $address->customAttributes()->count());
        $this->assertEquals('baz', $attribute->value);
    }
}
