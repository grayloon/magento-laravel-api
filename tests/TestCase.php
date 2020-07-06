<?php

namespace Grayloon\Magento\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Grayloon\Magento\MagentoServiceProvider;

class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app)
    {
        return [MagentoServiceProvider::class];
    }
}
