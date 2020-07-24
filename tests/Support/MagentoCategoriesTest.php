<?php

namespace Grayloon\Magento\Tests\Support;

use Grayloon\Magento\Jobs\ResolveMagentoCategory;
use Grayloon\Magento\Models\MagentoCategory;
use Grayloon\Magento\Support\MagentoCategories;
use Grayloon\Magento\Tests\TestCase;
use Illuminate\Support\Facades\Queue;

class MagentoCategoriesTest extends TestCase
{
    public function test_can_create_magento_category()
    {
        Queue::fake();

        $category = [
            'id'            => 1,
            'parent_id'     => 1,
            'name'          => 'foo bar',
            'position'      => 1,
            'level'         => 1,
            'product_count' => 100,
            'children_data' => [],
        ];

        (new MagentoCategories())->updateCategory($category);

        $category = MagentoCategory::first();

        $this->assertNotEmpty($category);
        $this->assertEquals('foo bar', $category->name);
        $this->assertEquals('foo-bar', $category->slug);

        Queue::assertNotPushed(ResolveMagentoCategory::class);
    }

    public function test_can_magento_category_can_resolve_multiple_children_in_category()
    {
        Queue::fake();

        $category = [
            'id'            => 1,
            'parent_id'     => 1,
            'name'          => 'foo',
            'position'      => 1,
            'level'         => 1,
            'product_count' => 100,
            'children_data' => [
                [
                    'id'            => 2,
                    'parent_id'     => 1,
                    'name'          => 'bar',
                    'position'      => 1,
                    'level'         => 2,
                    'product_count' => 100,
                    'children_data' => [],
                ],
                [
                    'id'            => 3,
                    'parent_id'     => 1,
                    'name'          => 'baz',
                    'position'      => 1,
                    'level'         => 2,
                    'product_count' => 100,
                    'children_data' => [],
                ],
            ],
        ];

        (new MagentoCategories())->updateCategory($category);

        Queue::assertPushed(ResolveMagentoCategory::class, 2);
    }

    public function test_magento_category_grandchildren_can_append_grandparents_slug()
    {
        Queue::fake();

        $category = [
            'id'            => 2,
            'parent_id'     => 1,
            'name'          => 'bar',
            'position'      => 1,
            'level'         => 1,
            'product_count' => 100,
            'parent_slug'   => 'foo',
            'children_data' => [
                [
                    'id'            => 3,
                    'parent_id'     => 1,
                    'name'          => 'baz',
                    'position'      => 1,
                    'level'         => 2,
                    'product_count' => 100,
                    'children_data' => [],
                ],
            ],
        ];

        (new MagentoCategories())->updateCategory($category);

        Queue::assertPushed(ResolveMagentoCategory::class);
        Queue::assertPushed(function (ResolveMagentoCategory $job) {
            return $job->category['parent_slug'] === 'foo/bar';
        });
    }

    public function test_magento_category_can_properly_resolve_slug_from_parent()
    {
        $category = [
            'id'            => 2,
            'parent_id'     => 1,
            'name'          => 'baz',
            'position'      => 1,
            'level'         => 1,
            'product_count' => 100,
            'children_data' => [],
            'parent_slug'   => 'foo/bar',
        ];

        (new MagentoCategories())->updateCategory($category);

        $category = MagentoCategory::first();

        $this->assertNotEmpty($category);
        $this->assertEquals('foo/bar/baz', $category->slug);
    }
}
