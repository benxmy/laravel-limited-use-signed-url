<?php

namespace Intellow\LaravelSingleUseSignedUrl;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Intellow\LaravelSingleUseSignedUrl\Skeleton\SkeletonClass
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
