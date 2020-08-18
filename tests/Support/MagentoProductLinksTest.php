<?php

namespace Grayloon\Magento\Tests\Support;

use Grayloon\Magento\Jobs\SyncMagentoProductInformation;
use Grayloon\Magento\Models\MagentoProduct;
use Grayloon\Magento\Models\MagentoProductLink;
use Grayloon\Magento\Support\MagentoProductLinks;
use Grayloon\Magento\Tests\TestCase;
use Illuminate\Support\Facades\Queue;

class MagentoProductLinksTest extends TestCase
{
    public function test_can_add_product_link()
    {
        $product = factory(MagentoProduct::class)->create();
        $relating = factory(MagentoProduct::class)->create();

        $response = [
            'product_links' => [
                [
                    'sku' => $relating->sku,
                    'link_type' => 'related',
                    'position' => 1,
                ],
            ],
        ];
        
        (new MagentoProductLinks)->updateProductLinks($product, $response);

        $this->assertEquals(1, MagentoProductLink::count());
        $this->assertEquals($product->id, MagentoProductLink::first()->product_id);
        $this->assertEquals($relating->id, MagentoProductLink::first()->related_product_id);
        $this->assertEquals(1, MagentoProductLink::first()->position);
        $this->assertEquals('related', MagentoProductLink::first()->link_type);
    }

    public function test_empty_response_skips_creation()
    {
        $product = factory(MagentoProduct::class)->create();
        
        (new MagentoProductLinks)->updateProductLinks($product, []);
        $this->assertEquals(0, MagentoProductLink::count());
    }

    public function test_missing_product_links_response_skips_creation()
    {
        $product = factory(MagentoProduct::class)->create();
        
        (new MagentoProductLinks)->updateProductLinks($product, ['foo' => 'bar']);
        $this->assertEquals(0, MagentoProductLink::count());
    }

    public function test_empty_product_links_response_skips_creation()
    {
        $product = factory(MagentoProduct::class)->create();
        
        (new MagentoProductLinks)->updateProductLinks($product, ['product_links' => []]);
        $this->assertEquals(0, MagentoProductLink::count());
    }

    public function test_missing_relating_product_retries_later_in_queue()
    {
        Queue::fake();

        $product = factory(MagentoProduct::class)->create();

        $response = [
            'product_links' => [
                [
                    'sku' => 'foo',
                    'link_type' => 'related',
                    'position' => 1,
                ],
            ],
        ];
        
        (new MagentoProductLinks)->updateProductLinks($product, $response);

        Queue::assertPushed(SyncMagentoProductInformation::class);
        Queue::assertPushed(SyncMagentoProductInformation::class, fn ($job) => $job->product->id === $product->id);
        $this->assertEquals(0, MagentoProductLink::count());
    }

    public function test_can_update_product_link()
    {
        $product = factory(MagentoProduct::class)->create();
        $relating = factory(MagentoProduct::class)->create();
        factory(MagentoProductLink::class)->create([
            'product_id' => $product->id,
            'related_product_id' => $relating->id,
            'link_type' => 'related',
        ]);

        $response = [
            'product_links' => [
                [
                    'sku' => $relating->sku,
                    'link_type' => 'related',
                    'position' => 1,
                ],
            ],
        ];
        
        (new MagentoProductLinks)->updateProductLinks($product, $response);

        $this->assertEquals(1, MagentoProductLink::count());
        $this->assertEquals($product->id, MagentoProductLink::first()->product_id);
        $this->assertEquals($relating->id, MagentoProductLink::first()->related_product_id);
        $this->assertEquals(1, MagentoProductLink::first()->position);
        $this->assertEquals('related', MagentoProductLink::first()->link_type);
    }

    public function test_can_update_product_link_position()
    {
        $product = factory(MagentoProduct::class)->create();
        $relating = factory(MagentoProduct::class)->create();
        $link = factory(MagentoProductLink::class)->create([
            'product_id' => $product->id,
            'related_product_id' => $relating->id,
            'link_type' => 'related',
            'position' => 1,
        ]);

        $response = [
            'product_links' => [
                [
                    'sku' => $relating->sku,
                    'link_type' => 'related',
                    'position' => 3,
                ],
            ],
        ];
        
        (new MagentoProductLinks)->updateProductLinks($product, $response);

        $this->assertEquals(1, MagentoProductLink::count());
        $this->assertEquals(3, MagentoProductLink::first()->position);
    }

    public function test_new_link_type_creates_new_record()
    {
        $product = factory(MagentoProduct::class)->create();
        $relating = factory(MagentoProduct::class)->create();
        $link = factory(MagentoProductLink::class)->create([
            'product_id' => $product->id,
            'related_product_id' => $relating->id,
            'link_type' => 'related',
            'position' => 1,
        ]);

        $response = [
            'product_links' => [
                [
                    'sku' => $relating->sku,
                    'link_type' => 'foo',
                    'position' => 1,
                ],
            ],
        ];
        
        (new MagentoProductLinks)->updateProductLinks($product, $response);

        $this->assertEquals(2, MagentoProductLink::count());
    }
}
