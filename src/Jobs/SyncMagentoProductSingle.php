<?php

namespace Grayloon\Magento\Jobs;

use Grayloon\Magento\Magento;
use Grayloon\Magento\Support\MagentoProducts;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncMagentoProductSingle implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The Magento Product SKU for the API.
     *
     * @var string
     */
    public $sku;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($sku)
    {
        $this->sku = $sku;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $product = (new Magento())->api('products')
            ->show($this->sku)
            ->json();

        (new MagentoProducts())->updateOrCreateProduct($product);
    }
}
