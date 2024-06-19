<?php

namespace SiriusProgram\SiriusHelpers;

class StringHelpers
{
    protected \libphonenumber\PhoneNumber $phoneNumber;

    public function __construct(private string $string = '')
    {
        //
    }

    public function __toString(): string
    {
        return $this->string;
    }

    public function of(string $string): static
    {
        $this->string = $string;

        return $this;
    }

    // Transformation

    public function toStr(): static
    {
        $this->string = str($this->string);

        return $this;
    }

    public function encrypt(?string $salt = null): static
    {
        $salt = $salt ?? config('app.key');
        $method = 'aes-256-cbc';
        $key = substr(hash('sha256', $salt, true), 0, 32);
        $iv = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0);

        $this->string = base64_encode(openssl_encrypt($this->string, $method, $key, OPENSSL_RAW_DATA, $iv));

        return $this;
    }

    public function decrypt(?string $salt = null): static
    {
        $salt = $salt ?? config('app.key');
        $method = 'aes-256-cbc';
        $key = substr(hash('sha256', $salt, true), 0, 32);
        $iv = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0);

        $decrypted = openssl_decrypt(base64_decode($this->string), $method, $key, OPENSSL_RAW_DATA, $iv);
        if (empty($decrypted)) {
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

    public function toPhoneNumber(bool $zeroPrefix = false): static
    {
        $formatter = \libphonenumber\PhoneNumberUtil::getInstance();

        $locale = config('app.locale');

        $this->phoneNumber = $formatter->parse($this->string, $locale);

        if (!$formatter->isValidNumber($this->phoneNumber)) {
            throw new \InvalidArgumentException('Invalid phone number.', 500);
        }

        $this->string = $formatter->format($this->phoneNumber, $zeroPrefix ? \libphonenumber\PhoneNumberFormat::NATIONAL : \libphonenumber\PhoneNumberFormat::INTERNATIONAL);

        return $this;
    }

    public function sanitizePhoneNumber(bool $zeroPrefix = false): static
    {
        if (empty($this->phoneNumber)) {
            $this->toPhoneNumber($zeroPrefix);
        }

        $this->string = $this->phoneNumber->getNationalNumber();
        $this->string = $zeroPrefix ? ('0' . $this->string) : ("+" . $this->phoneNumber->getCountryCode() . $this->string);

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
