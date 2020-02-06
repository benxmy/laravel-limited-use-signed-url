# Description

<!-- [![Latest Version on Packagist](https://img.shields.io/packagist/v/benxmy/laravel-dual-use-signed-url.svg?style=flat-square)](https://packagist.org/packages/benxmy/laravel-dual-use-signed-url)
[![Build Status](https://img.shields.io/travis/benxmy/laravel-dual-use-signed-url/master.svg?style=flat-square)](https://travis-ci.org/benxmy/laravel-dual-use-signed-url)
[![Quality Score](https://img.shields.io/scrutinizer/g/benxmy/laravel-dual-use-signed-url.svg?style=flat-square)](https://scrutinizer-ci.com/g/benxmy/laravel-dual-use-signed-url)
[![Total Downloads](https://img.shields.io/packagist/dt/benxmy/laravel-dual-use-signed-url.svg?style=flat-square)](https://packagist.org/packages/benxmy/laravel-dual-use-signed-url) -->

This is a fork of [Laravel Single Use Signed URL](https://github.com/intellow/laravel-single-use-signed-url). This version allows two accesses to the generated URL. This package was created specifically for signed URLs that can be used as a temporary `src` url for media files.  Due to the way browsers often handle these urls, the request will be made twice -- once for a preflight request and the second time for the actual file. This implementation allows for the url to be used twice in quick succession, but then the url will become unavailable. The url generated will only be available to the user who generates it and an expiration time can be set for the url usage.  A future update will include the optional paramater of 'uses_allowed' so the number of times the url is used can be set dynamically.

## Installation

You can install the package via composer:

```bash
composer require benxmy/laravel-dual-use-signed-url
```

Run `php artisan migrate` after you install.

## Usage
I originally forked this from [Laravel Single Use Signed URL](https://github.com/intellow/laravel-single-use-signed-url) so I could make it more difficult for someone to actually access a direct download link for an embedded `src` attribute url. The original package is quite useful for single-use URLs for password resets, etc. 

First create a route that accepts a {user} as a parameter and give it a name. For example:

```php
Route::get('/play-media/{user}', [DualUseSignedUrlController::class, 'handle'])
->name('dual-use-url')
->middleware('validateDualUseSignedUrl');
```
The above route can be whatever you want really.  The package will append the extra paramaeters in the background.  For example, you could create a route like: `Route::get('/play-media/{user}/{media}', ...)`. However, you **must** include the `{user}` as the first parameter in the route. 

Then in a controller you can generate a dual use signed url to this route with the following:

``` php
$url = DualUseSignedUrl::make('dual-use-url', $userId, $expiresInMinutes);
```

For my use (as the `src` attribute), I can then simply pass the `$url` to the view and use it in the audio or video tag.

### Testing

Tests are not working right now.

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- This customization: [benxmy](https://github.com/benxmy)
- Original package by: [Intellow](https://github.com/intellow)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
