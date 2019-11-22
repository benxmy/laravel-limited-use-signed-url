<?php

namespace Intellow\LaravelSingleUseSignedUrl\Tests;

use http\Exception\InvalidArgumentException;
use Illuminate\Support\Facades\Route;
use Intellow\SingleUseSignedUrl\LaravelSingleUseSignedUrlFacade;
use Intellow\SingleUseSignedUrl\LaravelSingleUseSignedUrlServiceProvider;
use Intellow\SingleUseSignedUrl\SingleUseSignedUrl;
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
            LaravelSingleUseSignedUrlServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'SingleUseSignedUrl' => LaravelSingleUseSignedUrlFacade::class,
        ];
    }

    /** @test */
    public function single_use_signed_url_can_be_generated_when_route_exists()
    {
        $singleUseSignedUrl = new SingleUseSignedUrl();
        $singleUseSignedUrl->make('signed-route', 1, 60);
        $this->assertNotEmpty($singleUseSignedUrl->user_id);
    }

    /** @test */
    public function single_use_signed_url_cannot_be_generated_when_route_does_not_exist()
    {
        $singleUseSignedUrl = new SingleUseSignedUrl();
        $this->expectException(\Exception::class);
        $singleUseSignedUrl->make('does-not-exist', 1);
    }


}
