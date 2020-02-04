<?php

namespace Benxmy\SingleUseSignedUrl;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Benxmy\LaravelSingleUseSignedUrl\Skeleton\SkeletonClass
 */
class LaravelSingleUseSignedUrlFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-single-use-signed-url';
    }
}
