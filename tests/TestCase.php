<?php

namespace Interiordefine\Magento\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Interiordefine\Magento\MagentoServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    use RefreshDatabase;

    /**
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $config = $app->get('config');

        $config->set('logging.default', 'errorlog');

        $config->set('database.default', 'testbench');

        $config->set('telescope.storage.database.connection', 'testbench');

        $config->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $app->when(DatabaseEntriesRepository::class)
            ->needs('$connection')
            ->give('testbench');
    }

    protected function getPackageProviders($app)
    {
        return [MagentoServiceProvider::class];
    }
}
