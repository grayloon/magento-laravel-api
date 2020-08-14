<?php

namespace Grayloon\Magento\Tests\Support;

use Grayloon\Magento\Models\MagentoProductLinkType;
use Grayloon\Magento\Support\MagentoProductLinkTypes;
use Grayloon\Magento\Tests\TestCase;

class MagentoProductLinkTypesSupportTest extends TestCase
{
    public function test_can_create_product_link_types_from_api_response()
    {
        $typesFromApi = [
            [
                'code' => 1,
                'name' => 'Related',
            ],
            [
                'code' => 5,
                'name' => 'Checkout',
            ],
            [
                'code' => 2,
                'name' => 'Upsell',
            ],
        ];

        $magentoProductLinkTypes = new MagentoProductLinkTypes();

        $magentoProductLinkTypes->storeTypes($typesFromApi);

        $types = MagentoProductLinkType::get();

        $this->assertEquals(3, $types->count());
        $this->assertEquals('Checkout', $types->last()->name);
        $this->assertEquals(5, $types->last()->id);
    }

    public function test_empty_response_can_pass_through_product_link_type_creation()
    {
        $typesFromApi = [];

        $magentoProductLinkTypes = new MagentoProductLinkTypes();

        $magentoProductLinkTypes->storeTypes($typesFromApi);

        $types = MagentoProductLinkType::get();

        $this->assertEmpty($types);
        $this->assertEquals(0, $types->count());
    }

    public function test_invalid_response_can_pass_through_product_link_type_creation()
    {
        $typesFromApi = [
            [
                'name' => 'foo',
            ],
        ];

        $magentoProductLinkTypes = new MagentoProductLinkTypes();

        $magentoProductLinkTypes->storeTypes($typesFromApi);

        $types = MagentoProductLinkType::get();

        $this->assertEmpty($types);
        $this->assertEquals(0, $types->count());
    }

    public function test_existing_product_link_type_updates_but_doesnt_create()
    {
        $typesFromApi = [
            [
                'code' => 1,
                'name' => 'Related',
            ],
            [
                'code' => 5,
                'name' => 'Checkout',
            ],
            [
                'code' => 2,
                'name' => 'Upsell',
            ],
        ];

        $magentoProductLinkTypes = new MagentoProductLinkTypes();

        $magentoProductLinkTypes->storeTypes($typesFromApi);

        $types = MagentoProductLinkType::get();

        $this->assertEquals(3, $types->count());
        $this->assertEquals('Checkout', $types->last()->name);
        $this->assertEquals(5, $types->last()->id);
    }
}
