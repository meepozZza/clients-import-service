<?php

namespace App\Services\Import\Rules;

use App\Services\Import\Rules\Exceptions\IsNotStringException;

final class StringValue implements CellRule
{
    public static function validate(mixed $value): true
    {
        if (! is_string($value)) {
            throw new IsNotStringException;
        }

        return true;
    }
}
