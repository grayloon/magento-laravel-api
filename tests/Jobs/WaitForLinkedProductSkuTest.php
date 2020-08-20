<?php

namespace Grayloon\Magento\Tests\Jobs;

use Exception;
use Grayloon\Magento\Jobs\WaitForLinkedProductSku;
use Grayloon\Magento\Models\MagentoProduct;
use Grayloon\Magento\Models\MagentoProductLink;
use Grayloon\Magento\Tests\TestCase;

class WaitForLinkedProductSkuTest extends TestCase
{
    public function test_advances_to_link_creation_on_related_existence()
    {
        $product = factory(MagentoProduct::class)->create();
        $related = factory(MagentoProduct::class)->create();

        $response = [
            'sku' => $product->sku,
            'link_type' => 'upsell',
            'linked_product_sku' => $related->sku,
            'linked_product_type' => 'simple',
            'position' => '1',
        ];

        WaitForLinkedProductSku::dispatch($product, $response);

        $this->assertEquals(1, MagentoProductLink::count());
    }

    public function test_fails_when_try_limit_maxed_and_related_doesnt_exist()
    {
        $this->expectException(Exception::class);
        $product = factory(MagentoProduct::class)->create();

        $response = [
            'sku' => $product->sku,
            'link_type' => 'upsell',
            'linked_product_sku' => 'foo',
            'linked_product_type' => 'simple',
            'position' => '1',
        ];

        WaitForLinkedProductSku::dispatch($product, $response, 3);

        $this->assertEquals(0, MagentoProductLink::count());
    }
}
