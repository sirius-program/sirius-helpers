# Sirius Helpers

[![Latest Version on Packagist](https://img.shields.io/packagist/v/sirius-program/sirius-helpers.svg?style=flat-square)](https://packagist.org/packages/sirius-program/sirius-helpers)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/sirius-program/sirius-helpers/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/sirius-program/sirius-helpers/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/sirius-program/sirius-helpers/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/sirius-program/sirius-helpers/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/sirius-program/sirius-helpers.svg?style=flat-square)](https://packagist.org/packages/sirius-program/sirius-helpers)

This helper contain a lot of functions that can help you to build your apps.

## Installation

You can install the package via composer:

```bash
composer require sirius-program/sirius-helpers
```

Optionally, you can publish the config file with:

```bash
php artisan vendor:publish --tag="sirius-helpers-config"
```

This is the contents of the published config file:

```php
return [
    'currency_locale' => env('CURRENCY_LOCALE', 'id_ID'),
];
```

Or you can easly change the currency locale through `.env` file.

```bash
CURRENCY_LOCALE=en_US
```

## Usage

```php
// use Sirius class
$sirius = new Sirius();
// or use the helper function
sirius();
```

More detail can be found below.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

- [Fathul Husnan](https://github.com/fathulhusnan9901)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

# String Helpers

This helpers contains a lot of functions that can help you to manipulate strings.

## Usage

You can use it from the Sirius instance:
```php
// use Sirius class
$sirius = new Sirius();
$helper = $sirius->string();
// or use the helper function
sirius()->string();
```

Or use it straight from the StringHelpers instance:
```php
// use StringHelpers class
$helper = new StringHelpers();
// or use the helper function
sstring();
```

There are 7 methods you can use to manipulate string:
`encrypt`, `decrypt`, `urlSafe`, `urlUnsafe`, `toPhoneNumber`, `sanitizePhoneNumber`, and `toStr`

## encrypt(?string $salt = null)

This method will encrypt the string you give and implements an encryption method that uses AES-256-CBC encryption with a salt and an initialization vector to securely encrypt a string. The encrypted string is base64-encoded. By default this helper will use your `APP_KEY` for the salt.

```php
echo sirius()->string('text you want to encrypt @ 123')->encrypt(salt: 'sirius');
```

Code above will echo encrypted text: `MwTMh2laUQDG09O9ZsVCv2c8pON/3IlIHf+8Dq55gkg=`

## decrypt(?string $salt = null)

This method will decrypt the chipertext you give and implements an dencryption method that uses AES-256-CBC encryption algorithm (see `encrypt()` method above). By default this helper will use your `APP_KEY` for the salt.

```php
echo sirius()->string('MwTMh2laUQDG09O9ZsVCv2c8pON/3IlIHf+8Dq55gkg=')->decrypt(salt: 'sirius');
```

Code above will echo the depcrypted text: `text you want to encrypt @ 123`

## urlSafe()

This method will transforms your string into a URL-safe format by replacing specific characters with alternative characters that are less likely to cause issues in URLs.

```php
echo sirius()->string('MwTMh2laUQDG09O9ZsVCv2c8pON/3IlIHf+8Dq55gkg=')->urlSafe();
```

Code above will echo the URL-safe format text: `MwTMh2laUQDG09O9ZsVCv2c8pON_3IlIHf.8Dq55gkg-`

## urlUnsafe()

This method will transforms your URL-safe string to it's initial text before you transforms it to the URL-safe string (see `urlSafe()` method above).

```php
echo sirius()->string('MwTMh2laUQDG09O9ZsVCv2c8pON_3IlIHf.8Dq55gkg-')->urlUnsafe();
```

Code above will echo the URL-safe format text: `MwTMh2laUQDG09O9ZsVCv2c8pON/3IlIHf+8Dq55gkg=`

## toPhoneNumber(bool $zeroPrefix = false)

This method will takes any phone number string, parses it, validates it, and formats it into more readable phone number style format. The default format will use a plus with country calling code prefix based on your config in the `app.locale`, if you want the formatted string prefixed with zero, set the `$zeroPrefix` parameter to `true`.

```php
echo sirius()->string('+628123456789')->toPhoneNumber();
```

Code above will echo the readable phone number text: `+62 812-3456-789`

## sanitizePhoneNumber(bool $zeroPrefix = false)

This method will takes any phone number string, sanitizes it, so you will get the 'only number' text from the text you provide.

```php
echo sirius()->string('+62 812-3456-789')->sanitizePhoneNumber(zeroPrefix: true);
```

Code above will echo the sanitized phone number text: `08123456789`

## toStr()

This method will convert your text into Laravel's Str instance

## Method Chaining

Every method above return `$this`, so you can also do method chaining like this:
```php
echo sirius()->string('+628123456789')
    ->toPhoneNumber() // +62 812-3456-789
    ->sanitizePhoneNumber(zeroPrefix: true) // 08123456789
    ->encrypt() // OWb2zHt440dxKnH5jLkklg==
    ->urlSafe() // OWb2zHt440dxKnH5jLkklg--
    ->urlUnsafe() // OWb2zHt440dxKnH5jLkklg==
    ->decrypt() // 08123456789
    ->toStr() // this will convert the text into Laravel's Str instance
```

# Number Helpers


# DateTime Helpers
