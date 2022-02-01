<?php

namespace Interiordefine\Magento\Tests;

use Interiordefine\Magento\MagentoServiceProvider;
use Orchestra\Testbench\TestCase;

class ExampleTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [MagentoServiceProvider::class];
    }

    /** @test */
    public function true_is_true()
    {
        $this->assertTrue(true);
    }
}
