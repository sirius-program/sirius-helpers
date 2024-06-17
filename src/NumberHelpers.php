<?php

namespace SiriusProgram\SiriusHelpers;

class NumberHelpers
{
    private string $currencySymbol;

    private string|int|float $numberOriginal;

    protected \NumberFormatter $formatter;

    protected \libphonenumber\PhoneNumber $phoneNumber;

    public function __construct(private string|int|float $number = '', private ?string $locale = null)
    {
        $this->locale = $locale ?? config('sirius-helpers.currency-locale');

        $this->formatter = new \NumberFormatter($this->locale, \NumberFormatter::DECIMAL);

        $this->numberOriginal = $number;
    }

    public function __toString(): string
    {
        return $this->get();
    }

    // Getters

    public function getOriginal(): string|int|float
    {
        return $this->numberOriginal;
    }

    public function get(): string|int|float
    {
        return $this->number;
    }

    // Transformation

    public function of(string|int|float $number): static
    {
        $this->number = $number;

        $this->numberOriginal = $number;

        return $this;
    }

    public function setLocale(string $locale): static
    {
        $this->locale = $locale;

        $this->formatter = new \NumberFormatter($locale, \NumberFormatter::DECIMAL);

        return $this;
    }

    public function toInt(): static
    {
        $this->number = str($this->number)->numbers()->toInt();

        return $this;
    }

    public function toFloat(): static
    {
        $this->number = str($this->number)->numbers()->toFloat();

        return $this;
    }

    public function format(): static
    {
        $this->number = $this->formatter->format($this->numberOriginal);

        return $this;
    }

    public function toRoman(): static
    {
        $this->setLocale('@numbers=roman');
        $this->format($this->numberOriginal);

        return $this;
    }

    public function toCurrency(): static
    {
        $currencyFormatter = new \NumberFormatter($this->locale, \NumberFormatter::CURRENCY);

        $symbol = $currencyFormatter->getSymbol(\NumberFormatter::CURRENCY_SYMBOL);

        $this->currencySymbol = $symbol;

        $this->number = $symbol.$this->format($this->numberOriginal);

        return $this;
    }

    public function spell(): static
    {
        $spellFormatter = new \NumberFormatter($this->locale, \NumberFormatter::SPELLOUT);

        $this->number = $spellFormatter->format($this->numberOriginal).$this->currencySymbolSpell();

        if (str_starts_with($this->locale, 'id_')) {
            $this->number = str($this->number)->replace('titik', 'koma')->replace('kosong', 'nol')->toString();
        }

        return $this;
    }

    public function toPhoneNumber(bool $zeroPrefix = false): static
    {
        $formatter = \libphonenumber\PhoneNumberUtil::getInstance();

        $locale = config('app.locale');

        $this->phoneNumber = $formatter->parse($this->numberOriginal, $locale);

        $this->number = $formatter->format($this->phoneNumber, $zeroPrefix ? \libphonenumber\PhoneNumberFormat::NATIONAL : \libphonenumber\PhoneNumberFormat::INTERNATIONAL);

        return $this;
    }

    public function sanitizePhoneNumber(bool $zeroPrefix = false): static
    {
        if (empty($this->phoneNumber)) {
            $this->toPhoneNumber($zeroPrefix);
        }

        $this->number = $this->phoneNumber->getNationalNumber();
        $this->number = $zeroPrefix ? '0'.$this->number : $this->phoneNumber->getCountryCode().$this->number;

        return $this;
    }

    public function dump(): static
    {
        if (function_exists('dump')) {
            dump($this->number);
        } else {
            var_dump($this->number);
        }

        return $this;
    }

    public function dd(): void
    {
        if (function_exists('dd')) {
            dd($this->number);
        }

        var_dump($this->number);
        exit;
    }

    // Helpers

    private function currencySymbolSpell(): string
    {
        return match (true) {
            str_contains($this->locale, 'id_') => match ($this->currencySymbol) {
                default => $this->currencySymbol,

                'Rp' => ' rupiah',
                '$' => ' dolar',
                '€' => ' euro',
                '£' => ' pound',
                '¥' => ' yen',
                '¥' => ' yuan',
                '₽' => ' rubel',
                'ر.س' => ' riyal',

                'IDR' => ' rupiah indonesia',
                'USD', 'US$' => ' dollar amerika',
                'SGD', 'SG$' => ' dollar singapura',
                'EUR', 'EU€' => ' euro',
                'GBP', 'GB£' => ' pound',
                'JPY', 'JP¥' => ' yen jepang',
                'CNY', 'CN¥' => ' yuan tiongkok',
                'RUB', 'RU₽' => ' ruble rusia',
                'SAR' => ' riyal arab saudi',
            },
            str_contains($this->locale, 'en_') => match ($this->currencySymbol) {
                default => $this->currencySymbol,

                'Rp' => ' rupiah',
                '$' => ' dollar',
                '€' => ' euro',
                '£' => ' pound',
                '¥' => ' yen',
                '¥' => ' yuan',
                '₽' => ' ruble',
                'ر.س' => ' riyal',

                'IDR' => ' indonesian rupiah',
                'USD', 'US$' => ' united states dollar',
                'SGD', 'SG$' => ' singaporean dollar',
                'EUR', 'EU€' => ' euro',
                'GBP', 'GB£' => ' pound',
                'JPY', 'JP¥' => ' japanese yen',
                'CNY', 'CN¥' => ' chinese yuan',
                'RUB', 'RU₽' => ' russian ruble',
                'SAR' => ' saudi arabian riyal',
            },
            str_contains($this->locale, 'ja_') => match ($this->currencySymbol) {
                default => $this->currencySymbol,

                'Rp' => 'ルピア',
                '$' => 'ドル',
                '€' => 'ユーロ',
                '£' => 'ポンド',
                '￥' => '円',
                '¥' => '円',
                '¥' => '元',
                '₽' => 'ルーブル',
                'ر.س' => 'リヤル',

                'IDR' => 'インドネシアルピア',
                'USD', 'US$' => '米ドル',
                'SGD', 'SG$' => 'シンガポールドル',
                'EUR', 'EU€' => 'ユーロ',
                'GBP', 'GB£' => 'ポンド',
                'JPY', 'JP¥' => '日本円',
                'CNY', 'CN¥' => '中国元',
                'RUB', 'RU₽' => 'ロシアルーブル',
                'SAR' => 'サウジアラビアリヤル',
            },
        };
    }
}
