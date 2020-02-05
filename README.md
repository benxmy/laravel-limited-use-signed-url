# Very short description of the package

<!-- [![Latest Version on Packagist](https://img.shields.io/packagist/v/intellow/laravel-dual-use-signed-url.svg?style=flat-square)](https://packagist.org/packages/intellow/laravel-dual-use-signed-url)
[![Build Status](https://img.shields.io/travis/intellow/laravel-dual-use-signed-url/master.svg?style=flat-square)](https://travis-ci.org/intellow/laravel-dual-use-signed-url)
[![Quality Score](https://img.shields.io/scrutinizer/g/intellow/laravel-dual-use-signed-url.svg?style=flat-square)](https://scrutinizer-ci.com/g/intellow/laravel-dual-use-signed-url)
[![Total Downloads](https://img.shields.io/packagist/dt/intellow/laravel-dual-use-signed-url.svg?style=flat-square)](https://packagist.org/packages/intellow/laravel-dual-use-signed-url) -->

This is a fork of [Laravel Dual Use Signed URL](https://github.com/intellow/laravel-dual-use-signed-url). This version allows two accesses to the generated URL. This was originally created specifically for signed URLs that can be used as a temporary `src` url for media files.  Due to the way browsers often handle these urls, the request will be made twice -- once for a preflight request and the second time for the actual file. This implementation allows for the url to be used twice in quick succession, but then the url will become unavailable.

## Installation

You can install the package via composer:

```bash
composer require benxmy/laravel-dual-use-signed-url
```

## Usage
I primarily use this to give users a link to login, but want the security of knowing that once the link has been used, it cannot ever be used again (which is why I don't use Laravel Signed URLs)

Make sure to run `php artisan migrate` after you install.

First create a route that accepts a {user} as a parameter and give it a name. For example:

```php
Route::get('/email-login/{user}', [DualUseSignedUrlController::class, 'handle'])
->name('one-time-email-login')
->middleware('validateDualUseSignedUrl');
```

Then in a controller you can generate a dual use signed url to this route with the following:

``` php
$url = DualUseSignedUrl::make('email-login', $userId, $expiresInMinutes);
```

Then just send that $url to the user in a notification or email and they can click the link once to login.

### Testing

Tests are not working right now.

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- Original package by: [Intellow](https://github.com/intellow)
- [benxmy](https://github.com/benxmy)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
