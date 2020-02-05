<?php

namespace Benxmy\DualUseSignedUrl;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Benxmy\LaravelDualUseSignedUrl\Skeleton\SkeletonClass
 */
class LaravelDualUseSignedUrlFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-dual-use-signed-url';
    }
}
