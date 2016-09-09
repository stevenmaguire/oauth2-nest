# Nest Provider for OAuth 2.0 Client

[![Latest Version](https://img.shields.io/github/release/stevenmaguire/oauth2-nest.svg?style=flat-square)](https://github.com/stevenmaguire/oauth2-nest/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/stevenmaguire/oauth2-nest/master.svg?style=flat-square)](https://travis-ci.org/stevenmaguire/oauth2-nest)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/stevenmaguire/oauth2-nest.svg?style=flat-square)](https://scrutinizer-ci.com/g/stevenmaguire/oauth2-nest/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/stevenmaguire/oauth2-nest.svg?style=flat-square)](https://scrutinizer-ci.com/g/stevenmaguire/oauth2-nest)
[![Total Downloads](https://img.shields.io/packagist/dt/stevenmaguire/oauth2-nest.svg?style=flat-square)](https://packagist.org/packages/stevenmaguire/oauth2-nest)

This package provides Nest OAuth 2.0 support for the PHP League's [OAuth 2.0 Client](https://github.com/thephpleague/oauth2-client).

## Installation

To install, use composer:

```
composer require stevenmaguire/oauth2-nest
```

## Usage

Usage is the same as The League's OAuth client, using `\Stevenmaguire\OAuth2\Client\Provider\Nest` as the provider.

### Authorization Code Flow

```php
$provider = new Stevenmaguire\OAuth2\Client\Provider\Nest([
    'clientId'          => '{nest-client-id}',
    'clientSecret'      => '{nest-client-secret}',
    'redirectUri'       => 'https://example.com/callback-url'
]);
```
For further usage of this package please refer to the [core package documentation on "Authorization Code Grant"](https://github.com/thephpleague/oauth2-client#usage).

### Resource owner information

Nest does not support access to any personal information of the authorizing resource owner. As such, this package does not support the `getResourceOwner` method documented in the core package.

This package will throw a `Stevenmaguire\OAuth2\Client\Provider\Exception\ResourceOwnerException` exception if you attempt to use this method.

## Testing

``` bash
$ ./vendor/bin/phpunit
```

## Contributing

Please see [CONTRIBUTING](https://github.com/stevenmaguire/oauth2-nest/blob/master/CONTRIBUTING.md) for details.


## Credits

- [Steven Maguire](https://github.com/stevenmaguire)
- [All Contributors](https://github.com/stevenmaguire/oauth2-nest/contributors)


## License

The MIT License (MIT). Please see [License File](https://github.com/stevenmaguire/oauth2-nest/blob/master/LICENSE) for more information.
