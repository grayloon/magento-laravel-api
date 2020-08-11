<?php

namespace Grayloon\Magento\Jobs;

use Grayloon\Magento\Magento;
use Grayloon\Magento\Models\MagentoCustomAttributeType;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateProductAttributeGroup implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The Magento Custom Attribute Type.
     *
     * @var \Grayloon\Magento\Models\MagentoCustomAttributeType
     */
    public $type;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(MagentoCustomAttributeType $type)
    {
        $this->type = $type;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $api = (new Magento())->api('productAttributes')
            ->show($this->type->name)
            ->json();

        $this->type->update([
            'display_name' => $api['default_frontend_label'] ?? $this->type->display_name,
            'options'      => $api['options'] ?? [],
        ]);
    }
}
