<?php

namespace Grayloon\Magento\Tests\Support;

use Grayloon\Magento\Jobs\UpdateProductAttributeGroup;
use Grayloon\Magento\Models\MagentoCategory;
use Grayloon\Magento\Models\MagentoCustomAttributeType;
use Grayloon\Magento\Support\MagentoCategories;
use Grayloon\Magento\Tests\TestCase;
use function GuzzleHttp\Promise\queue;
use Illuminate\Support\Facades\Http;

use Illuminate\Support\Facades\Queue;

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
        Queue::fake();
        
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
                        'attribute_code' => 'url_path',
                        'value' => 'foo/bar',
                    ],
                    [
                        'attribute_code' => 'children_count',
                        'value' => '124',
                    ],
                ],
            ],
        ];

        factory(MagentoCustomAttributeType::class)->create(['name' => 'path']);
        factory(MagentoCustomAttributeType::class)->create(['name' => 'url_path']);
        factory(MagentoCustomAttributeType::class)->create(['name' => 'children_count']);

        (new MagentoCategories())->updateCategories($categories);

        $category = MagentoCategory::first();

        $this->assertNotEmpty($category);
        $this->assertEquals('Root Catalog', $category->name);
        $this->assertNull($category->parent_id);
        $this->assertEquals(3, $category->customAttributes()->count());
        $this->assertEquals('path', $category->customAttributes()->first()->attribute_type);
        $this->assertEquals('1', $category->customAttributes()->first()->value);
        $this->assertEquals('foo/bar', $category->slug);
        Queue::assertNothingPushed();
    }

    public function test_root_category_has_nullable_slug()
    {
        factory(MagentoCustomAttributeType::class)->create(['name' => 'path']);
        factory(MagentoCustomAttributeType::class)->create(['name' => 'children_count']);

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
        $this->assertNull($category->slug);
    }

    public function test_can_apply_new_custom_attribute_type_to_category()
    {
        Queue::fake();

        factory(MagentoCustomAttributeType::class)->create(['name' => 'path']);
        factory(MagentoCustomAttributeType::class)->create(['name' => 'children_count']);

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
                    [
                        'attribute_code' => 'warehouse_id',
                        'value' => '1',
                    ],
                ],
            ],
        ];

        (new MagentoCategories())->updateCategories($categories);

        $category = MagentoCategory::first();

        $this->assertEquals(3, $category->customAttributes()->count());
        $this->assertEquals('1', $category->customAttributes()->where('attribute_type', 'warehouse_id')->first()->value);
        Queue::assertPushed(UpdateProductAttributeGroup::class);
    }

    public function test_can_apply_raw_value_attribute_if_unknown_type_option_in_category()
    {
        Queue::fake();

        factory(MagentoCustomAttributeType::class)->create(['name' => 'path']);
        factory(MagentoCustomAttributeType::class)->create(['name' => 'children_count']);

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
                    [
                        'attribute_code' => 'warehouse_id',
                        'value' => 'Unknown',
                    ],
                ],
            ],
        ];

        (new MagentoCategories())->updateCategories($categories);

        $category = MagentoCategory::first();

        $this->assertEquals(3, $category->customAttributes()->count());
        $this->assertEquals('Unknown', $category->customAttributes()->where('attribute_type', 'warehouse_id')->first()->value);
        Queue::assertPushed(UpdateProductAttributeGroup::class);
    }
}
