<?php

namespace Grayloon\Magento\Tests\Support;

use Grayloon\Magento\Jobs\DownloadMagentoProductImage;
use Grayloon\Magento\Models\MagentoCategory;
use Grayloon\Magento\Models\MagentoCustomAttribute;
use Grayloon\Magento\Models\MagentoCustomAttributeType;
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

    public function test_magento_product_adds_attribute_type()
    {
        Http::fake(function ($request) {
            return Http::response([
                'options' => [
                    [
                        "label" => 'New York',
                        'value' => '1',
                    ],
                    [
                        'label' => 'Los Angeles',
                        'value' => '2',
                    ]
                ],
                'default_frontend_label' => 'Warehouse',
            ], 200);
        });

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
                        'attribute_code' => 'warehouse_id',
                        'value'          => "1",
                    ],
                ],
            ],
        ];

        $magentoProducts = new MagentoProducts();

        $magentoProducts->updateProducts($products);

        $product = MagentoProduct::with('customAttributes', 'customAttributes.type')->first();

        $this->assertNotEmpty($product->customAttributes->first());
        $this->assertInstanceOf(MagentoCustomAttribute::class, $product->customAttributes->first());
        $this->assertEquals('New York', $product->customAttributes->first()->value);
        $this->assertInstanceOf(MagentoCustomAttributeType::class, $product->customAttributes->first()->type()->first());
        $this->assertEquals('Warehouse', $product->customAttributes->first()->type()->first()->display_name);
        $this->assertEquals('warehouse_id', $product->customAttributes->first()->type()->first()->name);
    }

    public function test_magento_product_unknown_attribute_type_value_resolves_as_raw_value()
    {
        Http::fake(function ($request) {
            return Http::response([
                'options' => [
                    [
                        "label" => 'New York',
                        'value' => '1',
                    ],
                    [
                        'label' => 'Los Angeles',
                        'value' => '2',
                    ]
                ],
                'default_frontend_label' => 'Warehouse',
            ], 200);
        });

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
                        'attribute_code' => 'warehouse_id',
                        'value'          => "Unknown",
                    ],
                ],
            ],
        ];

        $magentoProducts = new MagentoProducts();

        $magentoProducts->updateProducts($products);

        $product = MagentoProduct::with('customAttributes')->first();

        $this->assertNotEmpty($product->customAttributes->first());
        $this->assertInstanceOf(MagentoCustomAttribute::class, $product->customAttributes->first());
        $this->assertEquals('Unknown', $product->customAttributes->first()->value);
    }

    public function test_magento_product_existing_attribute_uses_existing_values()
    {
        factory(MagentoCategory::class)->create();
        $type = factory(MagentoCustomAttributeType::class)->create([
            'name' => 'warehouse_id',
            'display_name' => "Warehouse",
            'options' => [
                [
                    "label" => 'New York',
                    'value' => '1',
                ],
                [
                    'label' => 'Los Angeles',
                    'value' => '2',
                ],
            ],
        ]);

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
                        'attribute_code' => 'warehouse_id',
                        'value'          => "1",
                    ],
                ],
            ],
        ];

        $magentoProducts = new MagentoProducts();

        $magentoProducts->updateProducts($products);

        $product = MagentoProduct::with('customAttributes')->first();

        $this->assertNotEmpty($product->customAttributes->first());
        $this->assertInstanceOf(MagentoCustomAttribute::class, $product->customAttributes->first());
        $this->assertEquals(1, $product->customAttributes->count());
        $this->assertEquals('New York', $product->customAttributes->first()->value);
    }

    public function test_magento_product_adds_associated_category()
    {
        $category = factory(MagentoCategory::class)->create();
        factory(MagentoCustomAttributeType::class)->create([
            'name' => 'category_ids',
        ]);

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
        factory(MagentoCustomAttributeType::class)->create([
            'name' => 'image',
        ]);

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
        factory(MagentoCustomAttributeType::class)->create([
            'name' => 'image',
        ]);

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
        factory(MagentoCustomAttributeType::class)->create([
            'name' => 'image',
        ]);

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

    public function test_magento_product_applies_slug_from_url_key()
    {
        $category = factory(MagentoCategory::class)->create();
        factory(MagentoCustomAttributeType::class)->create([
            'name' => 'url_key',
        ]);

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
                        'attribute_code' => 'url_key',
                        'value'          => 'dunder-mifflin-paper',
                    ],
                ],
            ],
        ];

        $magentoProducts = new MagentoProducts();

        $magentoProducts->updateProducts($products);

        $product = MagentoProduct::first();
        $this->assertNotNull($product);
        $this->assertEquals('dunder-mifflin-paper', $product->slug);
    }
}
