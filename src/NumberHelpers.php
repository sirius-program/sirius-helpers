<?php

namespace SiriusProgram\SiriusHelpers;

class NumberHelpers
{
    private string $currencySymbol = '';

    private string|int|float $originalNumber;

    protected \NumberFormatter $formatter;

    public function __construct(private string|int|float $number = '', private ?string $currencyLocale = null)
    {
        $this->currencyLocale = $currencyLocale ?? config('sirius-helpers.currency_locale');

        $this->formatter = new \NumberFormatter($this->currencyLocale, \NumberFormatter::DECIMAL);

        $this->originalNumber = $number;
    }

    public function __toString(): string
    {
        return $this->get();
    }

    // Getters

    public function getOriginal(): string|int|float
    {
        return $this->originalNumber;
    }

    public function get(): string|int|float
    {
        return $this->number;
    }

    // Transformation

    public function of(string|int|float $number): static
    {
        $this->number = $number;

        $this->originalNumber = $number;

        return $this;
    }

    public function setLocale(string $currencyLocale): static
    {
        $this->currencyLocale = $currencyLocale;

        $this->formatter = new \NumberFormatter($currencyLocale, \NumberFormatter::DECIMAL);

        return $this;
    }

    public function toInt(): static
    {
        $this->number = str($this->originalNumber)->numbers()->toInt();

        return $this;
    }

    public function toFloat(): static
    {
        $this->number = str($this->originalNumber)->numbers()->toFloat();

        return $this;
    }

    public function format(?string $currencyLocale = null): static
    {
        if (!is_null($currencyLocale)) {
            $this->setLocale($currencyLocale);
        }

        $this->number = $this->formatter->format($this->originalNumber);

        return $this;
    }

    public function toRoman(): static
    {
        $this->setLocale('@numbers=roman');
        $this->format($this->originalNumber);

        return $this;
    }

    public function toCurrency(?string $currencyLocale = null): static
    {
        if (!is_null($currencyLocale)) {
            $this->setLocale($currencyLocale);
        }

        $currencyFormatter = new \NumberFormatter($this->currencyLocale, \NumberFormatter::CURRENCY);

        $symbol = $currencyFormatter->getSymbol(\NumberFormatter::CURRENCY_SYMBOL);

        $this->currencySymbol = $symbol;

        $this->number = $symbol . $this->format($this->originalNumber);

        return $this;
    }

    public function spell(?string $currencyLocale = null): static
    {
        if (!is_null($currencyLocale)) {
            $this->setLocale($currencyLocale);
        }

        $spellFormatter = new \NumberFormatter($this->currencyLocale, \NumberFormatter::SPELLOUT);

        $this->number = $spellFormatter->format($this->originalNumber) . $this->currencySymbolSpell();

        if (str_starts_with($this->currencyLocale, 'id_')) {
            $this->number = str($this->number)->replace('titik', 'koma')->replace('kosong', 'nol')->toString();
        }

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
            default                                    => '',
            str_contains($this->currencyLocale, 'id_') => match ($this->currencySymbol) {
                default => $this->currencySymbol,

                'Rp'  => ' rupiah',
                '$'   => ' dolar',
                '€'   => ' euro',
                '£'   => ' pound',
                '¥'   => ' yen',
                '¥'   => ' yuan',
                '₽'   => ' rubel',
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
            str_contains($this->currencyLocale, 'en_') => str(match ($this->currencySymbol) {
                default => $this->currencySymbol,

                'Rp'  => ' rupiah',
                '$'   => ' dollar',
                '€'   => ' euro',
                '£'   => ' pound',
                '¥'   => ' yen',
                '¥'   => ' yuan',
                '₽'   => ' ruble',
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
            })->plural($this->originalNumber)->toString(),
            str_contains($this->currencyLocale, 'ja_') => match ($this->currencySymbol) {
                default => $this->currencySymbol,

                'Rp'  => 'ルピア',
                '$'   => 'ドル',
                '€'   => 'ユーロ',
                '£'   => 'ポンド',
                '￥'   => '円',
                '¥'   => '円',
                '¥'   => '元',
                '₽'   => 'ルーブル',
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
