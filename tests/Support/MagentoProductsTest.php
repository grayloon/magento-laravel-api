<?php

namespace Grayloon\Magento\Tests\Support;

use Grayloon\Magento\Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Grayloon\Magento\Models\MagentoProduct;
use Grayloon\Magento\Support\MagentoProducts;

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

    public function test_can_create_magento_product()
    {
        $products = [
            [
                'id'         => '1',
                'name'       => 'Dunder Mifflin Paper',
                'sku'        => 'DFPC001',
                'price'      => 19.99,
                'status'     => "1",
                'visibility' => "1",
                'type_id'    => 'simple',
                'created_at' => now(),
                'updated_at' => now(),
                'weight'     => 10.00,
                'extension_attributes' => [
                    'website_id' => [1],
                ],
                'custom_attributes' => [
                    [
                        'attribute_code' => 'salesman',
                        'value'          => 'Dwight Schrute',
                    ],
                ]
            ]
        ];

        $magentoProducts = new MagentoProducts();

        $magentoProducts->updateProducts($products);

        $product = MagentoProduct::with('ExtAttributes', 'ExtAttributes.Type', 'CustAttributes', 'CustAttributes.Type')->first();

        $this->assertNotEmpty($product);
        $this->assertEquals('Dunder Mifflin Paper', $product->name);
        $this->assertNotEmpty($product->ExtAttributes());
        $this->assertEquals([1], $product->ExtAttributes()->first()->attribute);
        $this->assertEquals('website_id', $product->ExtAttributes()->first()->Type()->first()->type);
        $this->assertNotEmpty($product->CustAttributes());
        $this->assertEquals('Dwight Schrute', $product->CustAttributes()->first()->attribute);
        $this->assertEquals('salesman', $product->CustAttributes()->first()->Type()->first()->type);
    }
}
