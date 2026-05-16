<?php

declare(strict_types=1);

namespace SiriusProgram\SiriusHelpers;

use libphonenumber\PhoneNumber;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

class StringHelpers implements \Stringable
{
    protected PhoneNumber $phoneNumber;

    public function __construct(private string $string = '')
    {
        //
    }

    public function __toString(): string
    {
        return $this->get();
    }

    // Getter

    public function get(): string
    {
        return $this->string;
    }

    public function toString(): string
    {
        return $this->get();
    }

    // Transformation

    public function of(string $string): static
    {
        $this->string = $string;

        return $this;
    }

    public function toStr(): \Illuminate\Support\Stringable
    {
        return str($this->string);
    }

    public function encrypt(?string $salt = null): static
    {
        $salt ??= config('app.key');
        $method = 'aes-256-cbc';
        $key = substr(hash('sha256', (string) $salt, true), 0, 32);
        $iv = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0);

        $this->string = base64_encode(openssl_encrypt($this->string, $method, $key, OPENSSL_RAW_DATA, $iv));

        return $this;
    }

    public function decrypt(?string $salt = null): static
    {
        $salt ??= config('app.key');
        $method = 'aes-256-cbc';
        $key = substr(hash('sha256', (string) $salt, true), 0, 32);
        $iv = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0);

        $decrypted = openssl_decrypt(base64_decode($this->string), $method, $key, OPENSSL_RAW_DATA, $iv);
        if (in_array($decrypted, ['', '0', false], true)) {
            $decrypted = openssl_decrypt(base64_decode($this->urlUnsafe()->string), $method, $key, OPENSSL_RAW_DATA, $iv);
        }

        $this->string = $decrypted;

        return $this;
    }

    public function urlSafe(): static
    {
        $this->string = strtr($this->string, '+/=', '._-');

        return $this;
    }

    public function urlUnsafe(): static
    {
        $this->string = strtr($this->string, '._-', '+/=');

        return $this;
    }

    public function isPartOfPhoneNumber(): bool
    {
        $string = str($this->string)->remove('(')->remove(')')->remove('+')->remove('-')->remove(' ')->toString();

        return (bool) preg_match('/^\d{2,}$/', $string);
    }

    public function toPhoneNumber(bool $zeroPrefix = false, ?string $countryCode = null): static
    {
        $formatter = PhoneNumberUtil::getInstance();

        $countryCode ??= config('sirius-helpers.country_code');

        $this->phoneNumber = $formatter->parse($this->string, $countryCode);

        if (!$formatter->isValidNumber($this->phoneNumber)) {
            throw new \InvalidArgumentException('Invalid phone number.', 500);
        }

        $this->string = $formatter->format($this->phoneNumber, $zeroPrefix ? PhoneNumberFormat::NATIONAL : PhoneNumberFormat::INTERNATIONAL);

        return $this;
    }

    public function sanitizePhoneNumber(bool $zeroPrefix = false, ?string $countryCode = null): static
    {
        $countryCode ??= config('sirius-helpers.country_code');

        try {
            if (empty($this->phoneNumber)) {
                $formatter = PhoneNumberUtil::getInstance();
                $this->phoneNumber = $formatter->parse($this->string, $countryCode);
            }

            $this->string = $this->phoneNumber->getNationalNumber();
            $this->string = $zeroPrefix ? ('0' . $this->string) : ('+' . $this->phoneNumber->getCountryCode() . $this->string);
        } catch (\Throwable) {
            $dailingCode = Sirius::getCountryDetail($countryCode)['dailingCode'];

            $this->string = str($this->string)->remove('(')->remove(')')->remove('-')->remove(' ')->toString();

            if (str_starts_with($this->string, 0)) {
                $this->string = substr($this->string, 0, 1);
            }

            if (str_starts_with($this->string, (string) $dailingCode)) {
                $this->string = str_replace($dailingCode, '', $this->string);
            }

            $this->string = $zeroPrefix ? 0 . $this->string : $dailingCode . $this->string;
        }

        return $this;
    }

    /**
     * @param  int  $length  default '2', set to 0 to return all initials
     */
    public function toInitials(int $length = 2): static
    {
        $words = explode(' ', $this->string, $length > 0 ? $length : PHP_INT_MAX);

        $initials = '';

        foreach ($words as $word) {
            $initials .= $word[0];
        }

        if ($length > 0 && strlen($initials) < $length) {
            $initials = str_pad($initials, $length, ' ');
        }

        $this->string = strtoupper($initials);

        return $this;
    }

    public function dump(): static
    {
        if (function_exists('dump')) {
            dump($this->string);
        } else {
            var_dump($this->string);
        }

        return $this;
    }

    public function dd(): void
    {
        if (function_exists('dd')) {
            dd($this->string);
        }

        var_dump($this->string);
        exit;
    }
}
