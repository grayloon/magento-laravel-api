<?php

namespace Grayloon\Magento\Support;

use Grayloon\Magento\Jobs\DownloadMagentoProductImage;
use Grayloon\Magento\Models\MagentoProductMedia;

trait HasMediaEntries
{
    /**
     * Store image record and launch a job to download an image to the Laravel application.
     *
     * @param  array  $image
     * @param  \Grayloon\Magento\Models\MagentoProduct  $product
     * @return void
     */
    public function downloadProductImages($images, $product)
    {
        foreach ($images as $image) {
            MagentoProductMedia::updateOrCreate([
                'id'         => $image['id'],
                'product_id' => $product->id,
            ], [
                'media_type' => $image['media_type'],
                'label'      => $image['label'],
                'position'   => $image['position'],
                'disabled'   => $image['disabled'],
                'types'      => $image['types'],
                'file'       => $image['file'],
            ]);

            DownloadMagentoProductImage::dispatch($image['file']);
        }

        return $this;
    }
}
