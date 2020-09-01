<?php

namespace Grayloon\Magento\Tests\Support;

use Grayloon\Magento\Jobs\DownloadMagentoProductImage;
use Grayloon\Magento\Models\MagentoProduct;
use Grayloon\Magento\Models\MagentoProductMedia;
use Grayloon\Magento\Support\HasMediaEntries;
use Grayloon\Magento\Tests\TestCase;
use Illuminate\Support\Facades\Queue;

class HasMediaEntriesTest extends TestCase
{
    public function test_creates_new_product_images()
    {
        Queue::fake();

        $product = factory(MagentoProduct::class)->create();
        $images = [
            [
                'id' => 1,
                'media_type' => 'image',
                'label' => null,
                'position' => 1,
                'disabled' => false,
                'types' => [
                    'image',
                    'small_image',
                    'thumbnail',
                ],
                'file' => '/p/paper.jpg',
            ],
        ];

        (new FakeSupportingMediaEntriesClass)->exposedDownloadProductImages($images, $product);

        $this->assertEquals(1, MagentoProductMedia::count());
        $this->assertEquals($product->id, MagentoProductMedia::first()->product_id);
        $this->assertEquals('image', MagentoProductMedia::first()->media_type);
        $this->assertNull(MagentoProductMedia::first()->label);
        $this->assertEquals(1, MagentoProductMedia::first()->position);
        $this->assertEquals(0, MagentoProductMedia::first()->disabled);
        $this->assertEquals(['image', 'small_image', 'thumbnail'], MagentoProductMedia::first()->types);
        $this->assertEquals('/p/paper.jpg', MagentoProductMedia::first()->file);
    }

    public function test_launches_job_to_download_product_image()
    {
        Queue::fake();

        $product = factory(MagentoProduct::class)->create();
        $images = [
            [
                'id' => 1,
                'media_type' => 'image',
                'label' => null,
                'position' => 1,
                'disabled' => false,
                'types' => [
                    'image',
                    'small_image',
                    'thumbnail',
                ],
                'file' => '/p/paper.jpg',
            ],
        ];

        (new FakeSupportingMediaEntriesClass)->exposedDownloadProductImages($images, $product);

        $this->assertEquals(1, MagentoProductMedia::count());

        Queue::assertPushed(DownloadMagentoProductImage::class);
        Queue::assertPushed(DownloadMagentoProductImage::class, fn ($job) => $job->uri === '/p/paper.jpg');
    }

    public function test_updates_existing_image()
    {
        Queue::fake();

        $product = factory(MagentoProduct::class)->create();
        $image = factory(MagentoProductMedia::class)->create([
            'id' => 1,
            'product_id' => $product->id,
            'label' => null,
        ]);
        $images = [
            [
                'id' => 1,
                'media_type' => 'image',
                'label' => 'foo',
                'position' => 1,
                'disabled' => false,
                'types' => [
                    'image',
                    'small_image',
                    'thumbnail',
                ],
                'file' => '/p/paper.jpg',
            ],
        ];

        (new FakeSupportingMediaEntriesClass)->exposedDownloadProductImages($images, $product);

        $this->assertEquals(1, MagentoProductMedia::count());
        $this->assertEquals('foo', MagentoProductMedia::first()->label);
    }

    public function test_product_images_can_receive_empty_images_result()
    {
        $product = factory(MagentoProduct::class)->create();
        (new FakeSupportingMediaEntriesClass)->exposedDownloadProductImages($images = [], $product);

        $this->assertEquals(0, MagentoProductMedia::count());
    }
}

class FakeSupportingMediaEntriesClass
{
    use HasMediaEntries;

    public function exposedDownloadProductImages($images, $product)
    {
        return $this->downloadProductImages($images, $product);
    }
}
