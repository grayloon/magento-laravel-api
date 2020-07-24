<?php

namespace Grayloon\Magento\Tests\Support;

use Grayloon\Magento\Models\MagentoCategory;
use Grayloon\Magento\Support\MagentoCategories;
use Grayloon\Magento\Tests\TestCase;
use Illuminate\Support\Facades\Http;

class MagentoCategoriesTest extends TestCase
{
    public function test_can_count_magento_categories()
    {
        Http::fake(function ($request) {
            return Http::response([
                'total_count' => 1,
            ], 200);
        });

        $magentoCategories = new MagentoCategories();

        $count = $magentoCategories->count();

        $this->assertEquals(1, $count);
    }

    public function test_can_create_magento_category()
    {
        $categories = [
            [
                'id'         => '1',
                'parent_id'  => 0,
                'name'       => 'Root Catalog',
                'is_active'  => true,
                'position'   => 0,
                'level'      => 0,
                'children'   => '2',
                'created_at' => '2014-04-04 14:17:29',
                'updated_at' => '2014-04-04 14:17:29',
                'path'       => '1',
                'available_sort_by' => [],
                'include_in_menu' =>  true,
                'custom_attributes' => [
                    [
                        'attribute_code' => 'path',
                        'value' => '1',
                    ],
                    [
                        'attribute_code' => 'children_count',
                        'value' => '124',
                    ],
                ],
            ],
        ];

        (new MagentoCategories())->updateCategories($categories);

        $category = MagentoCategory::first();

        $this->assertNotEmpty($category);
        $this->assertEquals('Root Catalog', $category->name);
        $this->assertNull($category->parent_id);
        $this->assertEquals(2, $category->customAttributes()->count());
        $this->assertEquals('path', $category->customAttributes()->first()->attribute_type);
        $this->assertEquals('1', $category->customAttributes()->first()->value);
    }
}
