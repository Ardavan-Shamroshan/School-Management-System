<?php

namespace App\Traits;

use Illuminate\Support\Arr;

trait BaseEnum
{
    public function is(array|string|int|self $value): bool
    {
        if ($value instanceof self) {
            return $this == $value;
        }

        if (is_array($value)) {
            foreach ($value as $item) {
                if ($this->is($item)) {
                    return true;
                }
            }

            return false;
        }

        return $this == self::getBy($value);
    }

    public static function toArray($values): array
    {
        return Arr::mapWithKeys(Arr::flatten($values),
            function ($value) use ($values) {
                if (is_array($values)) {
                    return [$value->value => $value->getLabel()];
                }
                return [$values->value => $values->getLabel()];
            });
    }

    public static function getBy(string|int|null $value): ?self
    {
        if (is_null($value)) {
            return null;
        }

        return Arr::first(self::cases(), fn($case) => $case->value == $value);
    }
}
