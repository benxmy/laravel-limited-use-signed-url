<?php

namespace Benxmy\LimitedUseSignedUrl;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Benxmy\LaravelLimitedUseSignedUrl\Skeleton\SkeletonClass
 */
class LaravelLimitedUseSignedUrlFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-limited-use-signed-url';
    }
}
