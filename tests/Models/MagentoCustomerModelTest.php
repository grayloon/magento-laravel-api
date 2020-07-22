<?php

namespace Grayloon\Magento\Tests;

use Grayloon\Magento\Models\MagentoCustomAttribute;
use Grayloon\Magento\Models\MagentoCustomer;
use Grayloon\Magento\Models\MagentoCustomerAddress;

class MagentoCustomerModelTest extends TestCase
{
    public function test_can_create_magento_customer()
    {
        $customer = factory(MagentoCustomer::class)->create();

        $this->assertNotEmpty($customer);
    }

    public function test_can_get_custom_attributes_on_magento_customer()
    {
        $customer = factory(MagentoCustomer::class)->create();

        factory(MagentoCustomAttribute::class)->create([
            'attributable_type'   => MagentoCustomer::class,
            'attributable_id'     => $customer->id,
        ]);

        $attributes = $customer->customAttributes()->get();

        $this->assertNotEmpty($customer, $attributes);
        $this->assertEquals(1, $attributes->count());
        $this->assertEquals(MagentoCustomer::class, $attributes->first()->attributable_type);
    }

    public function test_can_update_instead_of_creating_row_custom_attributes_on_customer()
    {
        $customer = factory(MagentoCustomer::class)->create();

        factory(MagentoCustomAttribute::class)->create([
            'attributable_type'   => MagentoCustomer::class,
            'attributable_id'     => $customer->id,
            'attribute_type'      => 'foo',
            'value'               => 'bar',
        ]);

        $attribute = $customer->customAttributes()->updateOrCreate(['attribute_type' => 'foo'], [
            'value'=> 'baz',
        ]);

        $this->assertEquals(1, $customer->customAttributes()->count());
        $this->assertEquals('baz', $attribute->value);
    }

    public function test_magento_customer_has_many_addresses()
    {
        $customer = factory(MagentoCustomer::class)->create();

        factory(MagentoCustomerAddress::class, 5)->create([
            'customer_id' => $customer->id,
        ]);

        $this->assertEquals(5, $customer->addresses()->count());
        $this->assertInstanceOf(MagentoCustomerAddress::class, $customer->addresses()->first());
    }
}
