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
    'country_code' => env('COUNTRY_CODE', 'ID'),
    'currency_locale' => env('CURRENCY_LOCALE', 'id_ID'),
];
```

Or you can easly change them through `.env` file.

```env
COUNTRY_CODE=US
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

## Testing

To test this package, you can run the following command:

```bash
composer test
```

or

```bash
vendor/bin/pest
```

## Check Current Version

You can check what version of SiriusHelpers you are using right now by run a command:
```bash
php artisan about
```

There is one line like this you can see under the `Environment` group:
```bash
SiriusHelpers Version ................................................................ 1.1.0
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

- [Fathul Husnan](https://github.com/fathulhusnan9901)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

# String Helpers

This helpers contains a lot of functions that can help you to manipulate string.

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
sString();
```

There are 7 methods you can use to manipulate string:
`encrypt`, `decrypt`, `urlSafe`, `urlUnsafe`, `toPhoneNumber`, `sanitizePhoneNumber`, and `toStr`.

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

This method will takes any phone number string, parses it, validates it, and formats it into more readable phone number style format. The default format will use a plus with country calling code prefix based on country code on your config in the `sirius-helper.country_code`, if you want the formatted string prefixed with zero, set the `$zeroPrefix` parameter to `true`. This method relying on package [libphonenumber](https://github.com/googlei18n/libphonenumber) under the hood.

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

This method will convert your text into Laravel's Stringable instance

## Method Chaining

Every method above (except for `toStr()`) return `$this`, so you can also chain the methods like this:
```php
echo sirius()->string('+628123456789')
    ->toPhoneNumber() // +62 812-3456-789
    ->sanitizePhoneNumber(zeroPrefix: true) // 08123456789
    ->encrypt() // OWb2zHt440dxKnH5jLkklg==
    ->urlSafe() // OWb2zHt440dxKnH5jLkklg--
    ->urlUnsafe() // OWb2zHt440dxKnH5jLkklg==
    ->decrypt() // 08123456789
    ->toStr(); // will convert the text into Laravel's Stringable instance
```

## Dumping Everywhere

You can do `dump()` or `dd()` in every method above.
For example:
```php
echo sirius()->string('any text')
    ->encrypt()
    ->dump()
    ->decrypt()
    ->dd();
```

# Number Helpers

This helpers contains a lot of functions that can help you to manipulate number.

## Usage

You can use it from the Sirius instance:
```php
// use Sirius class
$sirius = new Sirius();
$helper = $sirius->number();

// or use the helper function
sirius()->number();
```

Or use it straight from the NumberHelpers instance:
```php
// use NumberHelpers class
$helper = new NumberHelpers();

// or use the helper function
sNumber();
```

There are 6 methods you can use to manipulate number:
`toInt`, `toFloat`, `format`, `toRoman`, `toCurrency`, `toCent`, `fromCent`, and `spell`.

## toInt()

This method will convert your number into Integer.

```php
echo sirius()->number(1234.56)->toInt();
```

Code above will echo `1234`

## toFloat()

This method will convert your number into Float.

```php
echo sirius()->number('1234.56')->toFloat();
```

Code above will echo `1234.56`

## format(?string $currencyLocale = null)

This method will add delimiter to your number based on your provided `$currencyLocale` or currency locale you set on the config file. The default currency locale will be `id_ID` if you don't provide any locale or if you don't set it on the config file.

```php
echo sirius()->number(1234567.89)->format();
```

Code above will echo `1.234.567,89`

```php
echo sirius()->number(1234567.89)->format(currencyLocale: 'en_US');
```

Code above will echo `1,234,567.89`

## toRoman()

This method will convert your number into roman numerals.

```php
echo sirius()->number(1234)->toRoman();
```

Code above will echo `MCCXXXIV`

## toCurrency(?string $currencyLocale = null)

This method will convert your number into currency format. The default currency locale will be `id_ID` if you don't provide any locale or if you don't set it on the config file.

```php
echo sirius()->number(1234567.89)->toCurrency();
```

Code above will echo `Rp1.234.567,89`

```php
echo sirius()->number(1234567.89)->toCurrency(currencyLocale: 'en_US');
```

Code above will echo `$1,234,567.89`

## toCent()

This method will convert your number into cent (times the given number by 100).

```php
echo sirius()->number(1234567.89)->toCent();
```

Code above will echo `123.456.789`

## fromCent(bool $impactOriginalNumber = true)

This method will devide the given number by 100. By default, this method's result will also impact the original number, if you want to keep the original number in cent, set the `$impactOriginalNumber` parameter to `false`.

```php
echo sirius()->number(123.456.789)->fromCent();
```

Code above will echo `1234567.89`

## spell(?string $currencyLocale = null)

This method will spell out your number. The default currency locale will be `id_ID` if you don't provide any locale or if you don't set it on the config file. Also the language will be spelled out based that same locale.

```php
echo sirius()->number(1234567.89)->spell();
```

Code above will echo `satu juta dua ratus tiga puluh empat ribu lima ratus enam puluh tujuh koma delapan sembilan`

```php
echo sirius()->number(1234567.89)->spell(currencyLocale: 'en_US');
```

Code above will echo `one million two hundred thirty-four thousand five hundred sixty-seven point eight nine`

If you chained this method after `toCurrency()` method, the result will be appended with the spelled currency symbol.

```php
echo sirius()->number(1234567.89)->toCurrency(currencyLocale: 'en_US')->spell();
```

Code above will echo `one million two hundred thirty-four thousand five hundred sixty-seven point eight nine dollars`

## Get the Original Number

You can always retrieve the original number.

```php
echo sirius()->number(10000.05)->toCurrency()->getOriginal();
```

Code above will echo `10000.05`

## Method Chaining

Every method above return `$this`, so you can also chain the methods like this:
```php
echo sirius()->number(10000)
    ->toCurrency() // Rp10.000
    ->spell() // sepuluh ribu rupiah
    ->getOriginal(); // 10000
```

## Dumping Everywhere

You can do `dump()` or `dd()` in every method above.
For example:
```php
echo sirius()->number(10000.05)
    ->toCurrency()
    ->dump()
    ->spell()
    ->dd();
```

# DateTime Helpers

This helpers contains a lot of functions that can help you to manipulate datetime string.

## Usage

You can use it from the Sirius instance:
```php
// use Sirius class
$sirius = new Sirius();
$helper = $sirius->dateTime();

// or use the helper function
sirius()->dateTime();
```

Or use it straight from the DateTimeHelpers instance:
```php
// use DateTimeHelpers class
$helper = new DateTimeHelpers();

// or use the helper function
sDateTime();
```

There are 7 methods you can use to manipulate datetime string:
`toDateTime`, `toCarbon`, `format`, `toLongMonth`, `toShortMonth`, `toLongDay`, and `toShortDay`.

## toDateTime(string $fromFormat = 'Y-m-d H:i:s')

This method will convert your datetime string into PHP's `DateTime` object.

## toCarbon(string $fromFormat = 'Y-m-d H:i:s')

This method will convert your datetime string into [nesbot](https://carbon.nesbot.com/)'s `Carbon` object.

## format(string $format = 'Y-m-d H:i:s')

This method will format your datetime string based on the `$format` parameter. The provided datetime string must be converted into PHP's `DateTime` object or [nesbot](https://carbon.nesbot.com/)'s `Carbon` object first.

## toLongMonth()

This method will convert your datetime string into long month string. The provided datetime string can be in integer, datetime string (ex: '2024-01-01'), PHP's `DateTime`, or [nesbot](https://carbon.nesbot.com/)'s `Carbon` object. This method will use the language based on your config in `app.locale`.

```php
echo sirius()->dateTime(12)->toLongMonth();
```

Code above will echo `December`

## toShortMonth()

This method will convert your datetime string into short month string. The provided datetime string can be in integer, datetime string (ex: '2024-01-01'), PHP's `DateTime`, or [nesbot](https://carbon.nesbot.com/)'s `Carbon` object. This method will use the language based on your config in `app.locale`.

```php
echo sirius()->dateTime('2024-01-01')->toShortMonth();
```

Code above will echo `Jan`

## toLongDay()

This method will convert your datetime string into long day string. The provided datetime string can be in integer, datetime string (ex: '2024-01-01'), PHP's `DateTime`, or [nesbot](https://carbon.nesbot.com/)'s `Carbon` object. This method will use the language based on your config in `app.locale`.

```php
echo sirius()->dateTime(6)->toLongDay();
```

Code above will echo `Saturday`

## toShortDay()

This method will convert your datetime string into short day string. The provided datetime string can be in integer, datetime string (ex: '2024-01-01'), PHP's `DateTime`, or [nesbot](https://carbon.nesbot.com/)'s `Carbon` object. This method will use the language based on your config in `app.locale`.

```php
echo sirius()->dateTime('2024-01-01')->toShortDay();
```

Code above will echo `Mon`

## Get the Original DateTime String

You can always retrieve the original datetime string.

```php
echo sirius()->dateTime('2024-01-01')->toCarbon('Y-m-d')->getOriginal();
```

Code above will echo `2024-01-01`

## Method Chaining

Every method above return `$this`, so you can also chain the methods like this:
```php
echo sirius()->dateTime('2024-01-01 01:01:01')
    ->toDateTime() // will be converted into PHP's DateTime object
    ->format('Y-m-d') // 2024-01-01
    ->toLongMonth() // January
    ->getOriginal(); // 2024-01-01 01:01:01
```

## Dumping Everywhere

You can do `dump()` or `dd()` in every method above.
For example:
```php
echo sirius()->dateTime('2024-01-01 01:01:01')
    ->toDateTime()
    ->format('Y-m-d')
    ->dump()
    ->toLongMonth()
    ->dd()
```

## Static Methods

This helpers contains 2 static methods: `getAllMonths`, and `getAllDays`. As the name says, these two methods will retrieve all months and days with the language based on your config in `app.locale`.

These two methods accept 2 arguments: `$formatter` and `$format`. The default `$formatter` will be `new \IntlDateFormatter($locale, \IntlDateFormatter::FULL, \IntlDateFormatter::NONE)` and the default `$format` will be `MMMM`. You can find the other format symbol in [ICU Documentation](https://unicode-org.github.io/icu/userguide/format_parse/datetime/).

```php
$months = DateTimeHelpers::getAllMonths();
```

The `$months` variable will be an array of this:
```php
[
    1 => "January",
    2 => "February",
    3 => "March",
    4 => "April",
    5 => "May",
    6 => "June",
    7 => "July",
    8 => "August",
    9 => "September",
    10 => "October",
    11 => "November",
    12 => "December",
]
```

```php
$days = DateTimeHelpers::getAllDays();
```

The `$days` variable will be an array of this:
```php
[
    0 => "Sunday",
    1 => "Monday",
    2 => "Tuesday",
    3 => "Wednesday",
    4 => "Thursday",
    5 => "Friday",
    6 => "Saturday",
]
```

The `getAllDays` method also accepts the 3rd argument `$startingDay`. The default value will be constant `START_WITH_SUNDAY`. You can set it to constant `START_WITH_MONDAY` to make it start with Monday.

```php
$days = DateTimeHelpers::getAllDays(startingDay: DateTimeHelpers::START_WITH_MONDAY);
```

The `$days` variable will be an array of this:
```php
[
    1 => "Monday",
    2 => "Tuesday",
    3 => "Wednesday",
    4 => "Thursday",
    5 => "Friday",
    6 => "Saturday",
    7 => "Sunday",
]
```

# Other Static Helpers

There are 2 useful static methods that you can access from Sirius instance:
`calculateDistanceInMeters`, and `setNullIfBlank`.

## calculateDistanceInMeters(float $latitudeFrom, float $longitudeFrom, float $latitudeTo, float $longitudeTo, float $earthRadius = 6371000): float

This method will calculate the distance between two points of latitude and logitude in meters. The default `$earthRadius` will be `6371000` (in meters).

for example:
```php
echo SiriusProgram\SiriusHelpers\Sirius::calculateDistanceInMeters(-7.3197956, 112.765537, -7.3231706, 112.7578611);
```

Code above will echo `926.0155020636498`

## setNullIfBlank(mixed $data, bool $keepZero = false, bool $keepEmptyArray = false, bool $keepEmptyString = false): mixed

This method will set null if the given data is either zero, empty array, or empty string. If the given data is in array (and it's not empty), this method will iterate and transform the data inside of it.

for example:
```php
print_r(SiriusProgram\SiriusHelpers\Sirius::setNullIfBlank([0, 1, '', 2, null, 3, []]));
```

Code above will print `[null, 1, null, 2, null, 3, null]`

you can keep zero as zero, empty array as empty array and empty string as empty string by setting the `$keepZero`, `$keepEmptyArray`, and/or `$keepEmptyString` parameter to `true`.