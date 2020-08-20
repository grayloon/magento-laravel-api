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

class SyncMagentoProductLinks implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The Magento Product.
     *
     * @var \Grayloon\Magento\Models\MagentoProduct
     */
    public $product;

    /**
     * The Magento API Response.
     *
     * @var array
     */
    public $response = [];

    /**
     * Create a new job instance.
     *
     * @param  \Grayloon\Magento\Models\MagentoProduct  $product
     * @return void
     */
    public function __construct(MagentoProduct $product, $response = [])
    {
        $this->product = $product;
        $this->response = $response;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (! $this->response) {
            $this->response = (new Magento())->api('products')
                ->show($this->product->sku)
                ->json();
        }

        (new MagentoProductLinks())->updateProductLinks($this->product, $this->response);
    }
}
