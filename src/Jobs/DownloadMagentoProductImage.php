<?php

namespace Grayloon\Magento\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class DownloadMagentoProductImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The end uri of the image from Magento.
     *
     * @var string
     */
    public $uri;

    /**
     * The Magento directory where images are located.
     *
     * @var string
     */
    public $directory;

    /**
     * The fully constructed URL to download the image.
     *
     * @var string
     */
    public $fullUrl;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($uri, $directory = null)
    {
        $this->uri = $uri;
        $this->directory = $this->directory ?: '/pub/media/catalog/product';
        $this->fullUrl = config('magento.base_url').$this->directory.$this->uri;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $contents = file_get_contents($this->fullUrl);
        $name = substr($this->fullUrl, strrpos($this->fullUrl, '/') + 1);

        Storage::put('product/'.$name, $contents);
    }
}
