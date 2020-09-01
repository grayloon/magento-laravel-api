<?php

namespace Grayloon\Magento\Tests\Support;

use Grayloon\Magento\Jobs\WaitForLinkedProductSku;
use Grayloon\Magento\Models\MagentoProduct;
use Grayloon\Magento\Models\MagentoProductLink;
use Grayloon\Magento\Support\HasProductLinks;
use Grayloon\Magento\Tests\TestCase;
use Illuminate\Support\Facades\Queue;

class HasProductLinkTest extends TestCase
{
    public function test_creates_product_links()
    {
        $product = factory(MagentoProduct::class)->create([
            'id' => 5,
        ]);
        $link = factory(MagentoProduct::class)->create([
            'id' => 10,
        ]);
        $links = [
            [
                'sku' => $product->sku,
                'link_type' => 'related',
                'linked_product_sku' => $link->sku,
                'linked_product_type' => 'simple',
                'position' => 0,
            ],
        ];

        (new FakeProductLinksSupportingTest)->exposedSyncProductLinks($links, $product);

        $this->assertEquals(1, MagentoProductLink::count());
        $this->assertEquals(5, MagentoProductLink::first()->product_id);
        $this->assertEquals(10, MagentoProductLink::first()->related_product_id);
    }

    public function test_queues_wait_for_product_when_relating_model_not_available()
    {
        Queue::fake();

        $product = factory(MagentoProduct::class)->create([
            'id' => 5,
        ]);
        $links = [
            [
                'sku' => $product->sku,
                'link_type' => 'related',
                'linked_product_sku' => 'foo',
                'linked_product_type' => 'simple',
                'position' => 0,
            ],
        ];

        (new FakeProductLinksSupportingTest)->exposedSyncProductLinks($links, $product);

        $this->assertEquals(0, MagentoProductLink::count());
        Queue::assertPushed(WaitForLinkedProductSku::class);
        Queue::assertPushed(WaitForLinkedProductSku::class, fn ($job) => $job->response === $links[0]);
        Queue::assertPushed(WaitForLinkedProductSku::class, fn ($job) => $job->product === $product);
    }

    public function test_updates_product_links()
    {
        $link = factory(MagentoProductLink::class)->create();
        $links = [
            [
                'sku' => $link->product->sku,
                'link_type' => 'related',
                'linked_product_sku' => $link->related->sku,
                'linked_product_type' => 'simple',
                'position' => 20,
            ],
        ];

        (new FakeProductLinksSupportingTest)->exposedSyncProductLinks($links, $link->product);

        $this->assertEquals(1, MagentoProductLink::count());
        $this->assertEquals(20, MagentoProductLink::first()->position);
        $this->assertEquals($link->product->id, MagentoProductLink::first()->product_id);
        $this->assertEquals($link->related->id, MagentoProductLink::first()->related_product_id);
    }

    public function test_can_process_empty_links_response()
    {
        $product = factory(MagentoProduct::class)->create();

        (new FakeProductLinksSupportingTest)->exposedSyncProductLinks($links = [], $product);

        $this->assertEquals(0, MagentoProductLink::count());
    }
}

class FakeProductLinksSupportingTest
{
    use HasProductLinks;

    public function exposedSyncProductLinks($links, $product)
    {
        return $this->syncProductLinks($links, $product);
    }
}
