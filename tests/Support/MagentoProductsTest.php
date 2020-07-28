<?php

namespace Grayloon\Magento\Tests\Support;

use Grayloon\Magento\Models\MagentoCategory;
use Grayloon\Magento\Models\MagentoProduct;
use Grayloon\Magento\Support\MagentoProducts;
use Grayloon\Magento\Tests\TestCase;
use Illuminate\Support\Facades\Http;

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
                        'value'          => ["1"],
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
}
