<?php

declare(strict_types=1);

namespace SiriusProgram\SiriusHelpers;

use Arr;

class ArrayHelpers
{
    private readonly array $originalArray;

    public function __construct(private array $array = [])
    {
        $this->originalArray = $array;
    }

    public function get(): array
    {
        return $this->array;
    }

    public function getOriginal(): array
    {
        return $this->originalArray;
    }

    public function toArray(): static
    {
        $this->array = Arr::toArray($this->array);

        return $this;
    }

    public function sortKeyByListOf(array $keys): static
    {
        $this->array = collect($this->array)->sortKeysUsing(fn ($a, $b): int => array_search($a, $keys, true) <=> array_search($b, $keys, true))->toArray();

        return $this;
    }

    public function dump(): static
    {
        if (function_exists('dump')) {
            dump($this->array);
        } else {
            var_dump($this->array);
        }

        return $this;
    }

    public function dd(): void
    {
        if (function_exists('dd')) {
            dd($this->array);
        }

        var_dump($this->array);
        exit;
    }
}
