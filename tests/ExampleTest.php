<?php

namespace Benxmy\LaravelDualUseSignedUrl\Tests;

use http\Exception\InvalidArgumentException;
use Illuminate\Support\Facades\Route;
use Benxmy\DualUseSignedUrl\LaravelDualUseSignedUrlFacade;
use Benxmy\DualUseSignedUrl\LaravelDualUseSignedUrlServiceProvider;
use Benxmy\DualUseSignedUrl\DualUseSignedUrl;
use Orchestra\Testbench\TestCase;

class ExampleTest extends TestCase
{
//
    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate');
        Route::get('test-route')->name('test-route');
        Route::get('signed-route/{user}')->name('signed-route');
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelDualUseSignedUrlServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'DualUseSignedUrl' => LaravelDualUseSignedUrlFacade::class,
        ];
    }

    /** @test */
    public function dual_use_signed_url_can_be_generated_when_route_exists()
    {
        $dualUseSignedUrl = new DualUseSignedUrl();
        $dualUseSignedUrl->make('signed-route', 1, 60);
        $this->assertNotEmpty($dualUseSignedUrl->user_id);
    }

    /** @test */
    public function dual_use_signed_url_cannot_be_generated_when_route_does_not_exist()
    {
        $dualUseSignedUrl = new DualUseSignedUrl();
        $this->expectException(\Exception::class);
        $dualUseSignedUrl->make('does-not-exist', 1);
    }


}
