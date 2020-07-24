<?php

namespace Grayloon\Magento\Jobs;

use Grayloon\Magento\Support\MagentoCategories;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ResolveMagentoCategory implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $category;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($category)
    {
        $this->category = $category;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        return (new MagentoCategories)->updateCategory($this->category);
    }
}
