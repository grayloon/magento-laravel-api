<?php

namespace Interiordefine\Magento\Tests;

use Illuminate\Support\Facades\Http;
use Interiordefine\Magento\Api\CustomerGroups;
use Interiordefine\Magento\MagentoFacade;

class CustomerGroupsTest extends TestCase
{
    /** @test */
    public function it_can_instanciate()
    {
        $this->assertInstanceOf(CustomerGroups::class, MagentoFacade::api('customerGroups'));
    }

    /** @test */
    public function it_can_show()
    {
        Http::fake([
            '*rest/all/V1/customerGroups/1' => Http::response([], 200),
        ]);

        $api = MagentoFacade::api('customerGroups')->show(1);

        $this->assertTrue($api->ok());
    }

    /** @test */
    public function it_can_save_group()
    {
        Http::fake([
            '*rest/all/V1/customerGroups/1' => Http::response([], 200),
        ]);

        $api = MagentoFacade::api('customerGroups')->saveGroup(1, []);

        $this->assertTrue($api->ok());
    }

    /** @test */
    public function it_can_delete_group()
    {
        Http::fake([
            '*rest/all/V1/customerGroups/1' => Http::response([], 200),
        ]);

        $api = MagentoFacade::api('customerGroups')->deleteGroup(1);

        $this->assertTrue($api->ok());
    }

    /** @test */
    public function it_can_create_group()
    {
        Http::fake([
            '*rest/all/V1/customerGroups' => Http::response([], 200),
        ]);

        $api = MagentoFacade::api('customerGroups')->createGroup([]);

        $this->assertTrue($api->ok());
    }

    /** @test */
    public function it_can_search_groups()
    {
        Http::fake([
            '*rest/all/V1/customerGroups/search*' => Http::response([], 200),
        ]);

        $api = MagentoFacade::api('customerGroups')->search();

        $this->assertTrue($api->ok());
    }

    /** @test */
    public function it_can_get_default()
    {
        Http::fake([
            '*rest/all/V1/customerGroups/default' => Http::response([], 200),
        ]);

        $api = MagentoFacade::api('customerGroups')->default();

        $this->assertTrue($api->ok());
    }

    /** @test */
    public function it_can_set_default()
    {
        Http::fake([
            '*rest/all/V1/customerGroups/default/1' => Http::response([], 200),
        ]);

        $api = MagentoFacade::api('customerGroups')->setDefault(1);

        $this->assertTrue($api->ok());
    }
}
