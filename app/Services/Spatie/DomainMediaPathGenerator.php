<?php

namespace App\Services\Spatie;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator as BasePathGenerator;

abstract class DomainMediaPathGenerator implements BasePathGenerator
{
    public function getPath(Media $media): string
    {
        return $this->prefixer($media).'/';
    }

    public function getPathForConversions(Media $media): string
    {
        return $this->prefixer($media).'/conversions/';
    }

    public function getPathForResponsiveImages(Media $media): string
    {
        return $this->prefixer($media).'/responsive/';
    }

    abstract protected function domainPath(): string;

    private function prefixer(Media $media): string
    {
        $date = $media->created_at ?? now();
        $prefix = config('media-library.prefix', '');
        $path = $this->domainPath().'/'.$date->format('Y/m').'/'.$media->uuid;

        return $prefix ? rtrim($prefix, '/').'/'.$path : $path;
    }
}
