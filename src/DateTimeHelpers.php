<?php

namespace SiriusProgram\SiriusHelpers;

class DateTimeHelpers
{
    const START_WITH_SUNDAY = 0;

    const START_WITH_MONDAY = 1;

    private string|int|\DateTime|\Carbon\Carbon $originalDateTime;

    protected \IntlDateFormatter $formatter;

    public function __construct(private string|int|\DateTime|\Carbon\Carbon $dateTime = '')
    {
        $locale = config('app.locale');

        $this->formatter = new \IntlDateFormatter($locale, \IntlDateFormatter::FULL, \IntlDateFormatter::NONE);

        $this->originalDateTime = $dateTime;
    }

    public function __toString(): string
    {
        return $this->get();
    }

    // Getters

    public function getOriginal(): string|int|\DateTime|\Carbon\Carbon
    {
        return $this->originalDateTime;
    }

    public function get(): string|int|\DateTime|\Carbon\Carbon
    {
        return $this->dateTime;
    }

    // Transformation

    public function of(string|int|\DateTime|\Carbon\Carbon $string): static
    {
        $this->dateTime = $string;

        $this->originalDateTime = $string;

        return $this;
    }

    public function toDateTime(string $fromFormat = 'Y-m-d H:i:s'): static
    {
        $this->dateTime = \DateTime::createFromFormat($fromFormat, $this->dateTime);

        if ($this->dateTime === 0) {
            throw new \InvalidArgumentException('Invalid date format.', 500);
        }

        return $this;
    }

    public function toCarbon(string $fromFormat = 'Y-m-d H:i:s'): static
    {
        $this->dateTime = \Carbon\Carbon::createFromFormat($fromFormat, $this->dateTime);

        if ($this->dateTime === 0) {
            throw new \InvalidArgumentException('Invalid date format.', 500);
        }

        return $this;
    }

    public function format(string $format = 'Y-m-d H:i:s'): static
    {
        if ($this->dateTime instanceof \DateTime) {
            $this->dateTime = $this->dateTime->format($format);
        } elseif ($this->dateTime instanceof \Carbon\Carbon) {
            $this->dateTime = $this->dateTime->translatedFormat($format);
        } else {
            throw new \Exception(sprintf('Cannot format instance of %s.', gettype($this->dateTime)), 500);
        }

        return $this;
    }

    public function toLongMonth(): static
    {
        return self::transform('getAllMonths', 'F', 'MMMM');
    }

    public function toShortMonth(): static
    {
        return self::transform('getAllMonths', 'M', 'MMM');
    }

    public function toLongDay(): static
    {
        return self::transform('getAllDays', 'l', 'EEEE');
    }

    public function toShortDay(): static
    {
        return self::transform('getAllDays', 'D', 'EE');
    }

    public function dump(): static
    {
        if (function_exists('dump')) {
            dump($this->dateTime);
        } else {
            var_dump($this->dateTime);
        }

        return $this;
    }

    public function dd(): void
    {
        if (function_exists('dd')) {
            dd($this->dateTime);
        }

        var_dump($this->dateTime);
        exit;
    }

    // Helper

    private function transform(string $fetchFunction, string $dateTimeFormat, string $fetcherFormat): static
    {
        if ($this->dateTime instanceof \DateTime) {
            $this->dateTime = $this->dateTime->format($dateTimeFormat);
        } elseif ($this->dateTime instanceof \Carbon\Carbon) {
            $this->dateTime = $this->dateTime->translatedFormat($dateTimeFormat);
        } elseif (is_numeric($this->dateTime)) {
            $this->dateTime = self::{$fetchFunction}(format: $fetcherFormat)[(int) $this->dateTime];
        } else {
            $format = 'Y-m-d';
            if (str_contains(' ', $this->dateTime)) {
                $format = 'Y-m-d H:i:s';
            }

            class_exists(\Carbon\Carbon::class) ? $this->toCarbon($format) : $this->toDateTime($format);
            $this->transform($fetchFunction, $dateTimeFormat, $fetcherFormat);
        }

        return $this;
    }

    // Static

    public static function getAllMonths(?\IntlDateFormatter $formatter = null, string $format = 'MMMM'): array
    {
        if (!in_array('intl', get_loaded_extensions())) {
            throw new \Exception('Intl extension is not loaded in this environment.', 500);
        }

        if (!in_array($format, ['M', 'MM', 'MMM', 'MMMM', 'MMMMM'])) {
            throw new \InvalidArgumentException('Invalid format, accepted format: M, MM, MMM, MMMM, or MMMMM.', 500);
        }

        $locale = config('app.locale');

        $formatter = new \IntlDateFormatter($locale, \IntlDateFormatter::FULL, \IntlDateFormatter::NONE);

        $formatter->setPattern($format);

        $dateTime = new \DateTime;

        $months = [];
        for ($i = 1; $i <= 12; $i++) {
            $months[$i] = $formatter->format($dateTime->setDate(2000, $i, 1));
        }

        return $months;
    }

    public static function getAllDays(?\IntlDateFormatter $formatter = null, string $format = 'EEEE', int $startingDay = self::START_WITH_SUNDAY): array
    {
        if (!in_array('intl', get_loaded_extensions())) {
            throw new \Exception('Intl extension is not loaded in this environment.', 500);
        }

        if (!in_array($format, ['E', 'EE', 'EEE', 'EEEE', 'EEEEE'])) {
            throw new \InvalidArgumentException('Invalid format, accepted format: E, EE, EEE, EEEE or EEEEEE.', 500);
        }

        $locale = config('app.locale');

        $formatter = new \IntlDateFormatter($locale, \IntlDateFormatter::FULL, \IntlDateFormatter::NONE);

        $formatter->setPattern($format);

        $dateTime = new \DateTime;

        $days = [];
        for ($i = 1; $i <= 7; $i++) {
            $days[$i] = $formatter->format($dateTime->setDate(2001, 1, $i));
        }

        if ($startingDay == self::START_WITH_SUNDAY) {
            $days[0] = $days[7];
            unset($days[7]);
        }

        ksort($days);

        return $days;
    }
}
