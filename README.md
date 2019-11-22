# Very short description of the package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/intellow/laravel-single-use-signed-url.svg?style=flat-square)](https://packagist.org/packages/intellow/laravel-single-use-signed-url)
[![Build Status](https://img.shields.io/travis/intellow/laravel-single-use-signed-url/master.svg?style=flat-square)](https://travis-ci.org/intellow/laravel-single-use-signed-url)
[![Quality Score](https://img.shields.io/scrutinizer/g/intellow/laravel-single-use-signed-url.svg?style=flat-square)](https://scrutinizer-ci.com/g/intellow/laravel-single-use-signed-url)
[![Total Downloads](https://img.shields.io/packagist/dt/intellow/laravel-single-use-signed-url.svg?style=flat-square)](https://packagist.org/packages/intellow/laravel-single-use-signed-url)

This is where your description should go. Try and limit it to a paragraph or two, and maybe throw in a mention of what PSRs you support to avoid any confusion with users and contributors.

## Installation

You can install the package via composer:

```bash
composer require intellow/laravel-single-use-signed-url
```

## Usage
I primarily use this to give users a link to login, but want the security of knowing that once the link has been used, it cannot ever be used again (which is why I don't use Laravel Signed URLs)

Make sure to run `php artisan migrate` after you install.

First create a route that accepts a {user} as a parameter and give it a name. For example:

```php
Route::get('/email-login/{user}', [SingleUseSignedUrlController::class, 'handle'])
->name('one-time-email-login')
->middleware('validateSingleUseSignedUrl');
```

Then in a controller you can generate a single use signed url to this route with the following:

``` php
$url = SingleUseSignedUrl::make('email-login', $userId, $expiresInMinutes);
```

Then just send that $url to the user in a notification or email and they can click the link once to login.

### Testing

Tests are not working right now.

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email kevin@intellow.com instead of using the issue tracker.

## Credits

- [Intellow](https://github.com/intellow)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
