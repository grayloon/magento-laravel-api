<?php

namespace Grayloon\Magento\Tests;

use Grayloon\Magento\Models\MagentoProductCategory;

class MagentoProductCategoryModelTest extends TestCase
{
    public function test_can_create_magento_product_category()
    {
        $productCategory = factory(MagentoProductCategory::class)->create();

        $this->assertNotEmpty($productCategory);
    }
}
