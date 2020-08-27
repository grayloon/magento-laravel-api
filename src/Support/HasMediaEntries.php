<?php

namespace Grayloon\Magento\Support;

use Grayloon\Magento\Jobs\DownloadMagentoProductImage;

trait HasMediaEntries
{
    /**
     * Determine if the Custom Attribute type is an image.
     *
     * @param  string  $attribute_type
     * @return bool
     */
    protected function isImageType($attribute_type)
    {
        $types = [
            'thumbnail',
            'image',
            'small_image',
        ];

        return in_array($attribute_type, $types);
    }

    /**
     * Launch a job to download an image to the Laravel application.
     *
     * @param  string  $image
     * @return void
     */
    protected function downloadImage($image)
    {
        if ($image === 'no_selection') {
            return;
        }

        DownloadMagentoProductImage::dispatch($image);
    }
}
