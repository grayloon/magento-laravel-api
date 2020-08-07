<?php

namespace Grayloon\Magento\Tests\Support;

use Grayloon\Magento\Jobs\DownloadMagentoProductImage;
use Grayloon\Magento\Models\MagentoCategory;
use Grayloon\Magento\Models\MagentoProduct;
use Grayloon\Magento\Support\MagentoProducts;
use Grayloon\Magento\Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;

class MagentoProductsTest extends TestCase
{
    public function test_can_count_magento_products()
    {
        Http::fake(function ($request) {
            return Http::response([
                'total_count' => 1,
            ], 200);
        });

        $magentoProducts = new MagentoProducts();

        $count = $magentoProducts->count();

        $this->assertEquals(1, $count);
    }

    public function test_magento_product_adds_associated_category()
    {
        $category = factory(MagentoCategory::class)->create();

        $products = [
            [
                'id'         => '1',
                'name'       => 'Dunder Mifflin Paper',
                'sku'        => 'DFPC001',
                'price'      => 19.99,
                'status'     => '1',
                'visibility' => '1',
                'type_id'    => 'simple',
                'created_at' => now(),
                'updated_at' => now(),
                'weight'     => 10.00,
                'extension_attributes' => [
                    'website_id' => [1],
                ],
                'custom_attributes' => [
                    [
                        'attribute_code' => 'category_ids',
                        'value'          => ['1'],
                    ],
                ],
            ],
        ];

        $magentoProducts = new MagentoProducts();

        $magentoProducts->updateProducts($products);

        $product = MagentoProduct::first();
        $productCategories = $product->categories()->get();

        $this->assertNotEmpty($product);
        $this->assertNotEmpty($productCategories);
        $this->assertCount(1, $productCategories);
        $this->assertSame($category->id, $productCategories->first()->id);
    }

    public function test_magento_product_launches_job_to_download_product_image()
    {
        Queue::fake();
        
        factory(MagentoCategory::class)->create();

        $products = [
            [
                'id'         => '1',
                'name'       => 'Dunder Mifflin Paper',
                'sku'        => 'DFPC001',
                'price'      => 19.99,
                'status'     => '1',
                'visibility' => '1',
                'type_id'    => 'simple',
                'created_at' => now(),
                'updated_at' => now(),
                'weight'     => 10.00,
                'extension_attributes' => [
                    'website_id' => [1],
                ],
                'custom_attributes' => [
                    [
                        'attribute_code' => 'image',
                        'value'          => 'foo.jpg',
                    ],
                ],
            ],
        ];

        $magentoProducts = new MagentoProducts();

        $magentoProducts->updateProducts($products);

        Queue::assertPushed(DownloadMagentoProductImage::class);
    }

    public function test_magento_product_does_not_job_on_invalid_image_download()
    {
        Queue::fake();
        
        factory(MagentoCategory::class)->create();

        $products = [
            [
                'id'         => '1',
                'name'       => 'Dunder Mifflin Paper',
                'sku'        => 'DFPC001',
                'price'      => 19.99,
                'status'     => '1',
                'visibility' => '1',
                'type_id'    => 'simple',
                'created_at' => now(),
                'updated_at' => now(),
                'weight'     => 10.00,
                'extension_attributes' => [
                    'website_id' => [1],
                ],
                'custom_attributes' => [
                    [
                        'attribute_code' => 'image',
                        'value'          => 'no_selection',
                    ],
                ],
            ],
        ];

        $magentoProducts = new MagentoProducts();

        $magentoProducts->updateProducts($products);

        Queue::assertNotPushed(DownloadMagentoProductImage::class);
    }

    public function test_magento_product_download_image_is_correctly_constructed()
    {
        Queue::fake();
        
        factory(MagentoCategory::class)->create();

        $products = [
            [
                'id'         => '1',
                'name'       => 'Dunder Mifflin Paper',
                'sku'        => 'DFPC001',
                'price'      => 19.99,
                'status'     => '1',
                'visibility' => '1',
                'type_id'    => 'simple',
                'created_at' => now(),
                'updated_at' => now(),
                'weight'     => 10.00,
                'extension_attributes' => [
                    'website_id' => [1],
                ],
                'custom_attributes' => [
                    [
                        'attribute_code' => 'image',
                        'value'          => '/foo.jpg',
                    ],
                ],
            ],
        ];

        $magentoProducts = new MagentoProducts();

        $magentoProducts->updateProducts($products);

        Queue::assertPushed(DownloadMagentoProductImage::class);
        Queue::assertPushed(fn (DownloadMagentoProductImage $downloadJob) => $downloadJob->uri === '/foo.jpg');
        Queue::assertPushed(fn (DownloadMagentoProductImage $downloadJob) => $downloadJob->directory === '/pub/media/catalog/product');
        Queue::assertPushed(fn (DownloadMagentoProductImage $downloadJob) => $downloadJob->fullUrl === '/pub/media/catalog/product/foo.jpg');
    }
}
