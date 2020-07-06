<?php

namespace Grayloon\Magento\Tests;

use Orchestra\Testbench\TestCase;
use Grayloon\Magento\MagentoServiceProvider;

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
