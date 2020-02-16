# Description

<!-- [![Latest Version on Packagist](https://img.shields.io/packagist/v/mindlarkdev/laravel-limited-use-signed-url.svg?style=flat-square)](https://packagist.org/packages/mindlarkdev/laravel-limited-use-signed-url)
[![Build Status](https://img.shields.io/travis/mindlarkdev/laravel-limited-use-signed-url/master.svg?style=flat-square)](https://travis-ci.org/mindlarkdev/laravel-limited-use-signed-url)
[![Quality Score](https://img.shields.io/scrutinizer/g/mindlarkdev/laravel-limited-use-signed-url.svg?style=flat-square)](https://scrutinizer-ci.com/g/mindlarkdev/laravel-limited-use-signed-url)
[![Total Downloads](https://img.shields.io/packagist/dt/mindlarkdev/laravel-limited-use-signed-url.svg?style=flat-square)](https://packagist.org/packages/mindlarkdev/laravel-limited-use-signed-url) -->

This is an extensive modification of [Laravel Single Use Signed URL](https://github.com/intellow/laravel-single-use-signed-url). This version allows a customizable number accesses to the generated URL, after which the url will be unavailable. This package was created specifically for signed URLs that can be used as a temporary `src` url for media files.  Due to the way browsers often handle these urls, the request will be made twice -- once for a pre-flight request and the second time for the actual file. This implementation allows for the url to be used twice in quick succession, but then the url will become unavailable. The url generated will only be available to the user who generates it and an expiration time can be set for the url usage.  

## Installation

1. Install with composer:

```bash
composer require mindlarkdev/laravel-limited-use-signed-url
```

2. Publish the configuration file:
```bash
php artisan vendor:publish 
```
Select the `Mindlarkdev\LimitedUseSignedUrl\LaravelLimitedUseSIgnedUrlSeviceProvider` item.

The config file is named `limited-use-urls.php` and has the following options:
```
return [
	'expires_in_minutes' => 2,
	'uses_allowed' => 2,
]
```

These settings allow you to set the default expiration and allowed uses. These parameters can also be overridden when a url is created in a controller.

3. Run `php artisan migrate`.

## Usage
I originally forked this from [Laravel Limited Use Signed URL](https://github.com/intellow/laravel-limited-use-signed-url) so I could make it more difficult for someone to actually access a direct download link for an embedded `src` attribute url. The original package is quite useful for limited-use URLs for password resets, etc. 

First create a route that accepts a {user} as a parameter and give it a name. For example:

```php
Route::get('/play-media/{user}/{content}', [LimitedUseSignedUrlController::class, 'handle'])
->name('limited-use-url')
->middleware('validateLimitedUseSignedUrl');
```
The above route can be whatever you want really.  The package will append the extra paramaeters in the background.  For example, you could create a route like: `Route::get('/play-media/{user}/{media}', ...)`. However, you **must** include the `{user}` as the first parameter in the route. 

Then in a controller you can generate a dual use signed url to this route with the following:

``` php
$urlData = [
	'route_name' => 'limited-use-url',
	'user_id' => auth()->user()->id,
	'expires_in_minutes' => 3, // OPTIONAL - default set in config file
	'uses_allowed' => 1 // OPTIONAL - default set in config file
];
$extraParams = ['content' => $content->id]; // Extra params will be added to the route. In the above example, we're using {content}

$url = MakeLimitedUseSignedUrl::makeUrl('limited-use-url', $urlData, $extraParams);
```
For my use (as the `src` attribute), I can then simply pass the `$url` to the view and use it in the audio or video tag.

### Testing

Tests are not working right now.

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- Multi-use URLs Implementation: [midlarkdev](https://github.com/mindlarkdev)
- Original package by: [Intellow](https://github.com/intellow)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
