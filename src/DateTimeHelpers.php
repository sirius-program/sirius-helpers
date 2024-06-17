<?php

namespace SiriusProgram\SiriusHelpers;

class DateTimeHelpers
{
    CONST START_WITH_SUNDAY = 0;

    CONST START_WITH_MONDAY = 1;

    private string|int|\DateTime|Carbon\Carbon $datetimeOriginal;

    protected \IntlDateFormatter $formatter;

    public function __construct(private string|int|\DateTime|Carbon\Carbon $datetime = '')
    {
        $locale = config('app.locale');

        $this->formatter = new \IntlDateFormatter($locale, \IntlDateFormatter::FULL, \IntlDateFormatter::NONE);

        $this->datetimeOriginal = $datetime;
    }

    public function __toString(): string
    {
        return $this->get();
    }

    // Getters

    public function getOriginal(): string|int|\DateTime|Carbon\Carbon
    {
        return $this->datetimeOriginal;
    }

    public function get(): string|int|\DateTime|Carbon\Carbon
    {
        return $this->datetime;
    }

    // Transformation

    public function of(string|int|\DateTime|Carbon\Carbon $string): static
    {
        $this->datetime = $string;

        $this->datetimeOriginal = $string;

        return $this;
    }

    public function toDateTime(string $format = 'Y-m-d H:i:s'): static
    {
        $this->datetime = \DateTime::createFromFormat($format, $this->datetime);

        if ($this->datetime === 0) {
            throw new \InvalidArgumentException('Invalid date format.', 500);
        }

        return $this;
    }

    public function toCarbon(string $format = 'Y-m-d H:i:s'): static
    {
        $this->datetime = Carbon\Carbon::createFromFormat($format, $this->datetime);

        if ($this->datetime === 0) {
            throw new \InvalidArgumentException('Invalid date format.', 500);
        }

        return $this;
    }

    public function toLongMonth(): static
    {
        return self::transform('getAllMonths', 'F', 'MMMM');
    }

    public function toShortMonth(): static
    {
        return self::transform('getAllMonths', 'M', 'MM');
    }

    public function toLongDay(): static
    {
        return self::transform('getAllDays', 'l', 'EEEE');
    }

    public function toShortDay(): static
    {
        return self::transform('getAllDays', 'D', 'EE');
    }

    public function format(string $format): static
    {
        if ($this->datetime instanceof \DateTime) {
            $this->datetime = $this->datetime->format($format);
        } elseif ($this->datetime instanceof Carbon\Carbon) {
            $this->datetime = $this->datetime->translatedFormat($format);
        } else {
            throw new \Exception(sprintf('Cannot format instance of %s.', gettype($this->datetime)), 500);
        }

        return $this;
    }

    public function dump(): static
    {
        if (function_exists('dump')) {
            dump($this->datetime);
        } else {
            var_dump($this->datetime);
        }
        
        return $this;
    }

    public function dd(): void
    {
        if (function_exists('dd')) {
            dd($this->datetime);
        }

        var_dump($this->datetime);
        die;
    }

    // Static
    
    public static function getAllMonths(string $format = 'MMMM'): array
    {
        if (!in_array('intl', get_loaded_extensions())) {
            throw new \Exception('Intl extension is not loaded in this environment.', 500);
        }

        if (!in_array($format, ['M', 'MM', 'MMM', 'MMMM', 'MMMMM'])) {
            throw new \InvalidArgumentException('Invalid format, accepted format: M, MM, MMM, MMMM, or MMMMM.', 500);
        }

        $datetime = new \DateTime;

        $this->formatter->setPattern($format);

        $months = [];
        for ($i = 1; $i <= 12; $i++) {
            $months[$i] = $this->formatter->format($datetime->setDate(2000, $i, 1));
        }

        return $months;
    }

    public static function getAllDays(string $format = 'EEEE', int $startingDay = self::START_WITH_SUNDAY): array
    {
        if (!in_array('intl', get_loaded_extensions())) {
            throw new \Exception('Intl extension is not loaded in this environment.', 500);
        }

        if (!in_array($format, ['E', 'EE', 'EEE', 'EEEE', 'EEEEE'])) {
            throw new \InvalidArgumentException('Invalid format, accepted format: E, EE, EEE, EEEE or EEEEEE.', 500);
        }

        $datetime = new \DateTime;

        $this->formatter->setPattern($format);

        $days = [];
        for ($i = 1; $i <= 7; $i++) {
            $days[$i] = $this->formatter->format($datetime->setDate(2001, 1, $i));
        }

        if ($startingDay == self::START_WITH_SUNDAY) {
            $days[0] = $days[7];
            unset($days[7]);
        }

        return $days;
    }

    // Helper

    private function transform(string $fetchFunction, string $datetimeFormat, string $fetcherFormat): static
    {
        if ($this->datetime instanceof \DateTime) {
            $this->datetime = $this->datetime->format($datetimeFormat);
        } elseif ($this->datetime instanceof Carbon\Carbon) {
            $this->datetime = $this->datetime->translatedFormat($datetimeFormat);
        } elseif (is_int($this->datetime)) {
            $this->datetime = self::{$fetchFunction}($fetcherFormat)[(int)$this->datetime];
        } else {
            $format = 'Y-m-d';
            if (str_contains(' ', $this->datetime)) {
                $format = 'Y-m-d H:i:s';
            }
            
            class_exists(Carbon\Carbon::class) ? $this->toCarbon($format) : $this->toDateTime($format);
            $this->transform($fetchFunction, $datetimeFormat, $fetcherFormat);
        }


        return $this;
    }
}