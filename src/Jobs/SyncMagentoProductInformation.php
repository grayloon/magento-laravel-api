<?php

namespace Grayloon\Magento\Jobs;

use Grayloon\Magento\Magento;
use Grayloon\Magento\Models\MagentoProduct;
use Grayloon\Magento\Support\MagentoProductLinks;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncMagentoProductInformation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The Magento Product.
     *
     * @var \Grayloon\Magento\Models\MagentoProduct
     */
    public $product;

    /**
     * Create a new job instance.
     *
     * @param  \Grayloon\Magento\Models\MagentoProduct  $product
     * @return void
     */
    public function __construct(MagentoProduct $product)
    {
        $this->product = $product;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $productApi = (new Magento())->api('products')
            ->show($this->product->sku)
            ->json();

        (new MagentoProductLinks())->updateProductLinks($this->product, $productApi);
    }
}
