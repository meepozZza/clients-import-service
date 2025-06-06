<?php

namespace App\Services\Import\Rules;

use App\Services\Import\Rules\Exceptions\IsNotIntegerException;

final class IntegerValue implements CellRule
{
    public static function validate(mixed $value): true
    {
        if ((int) $value != $value) {
            throw new IsNotIntegerException;
        }

        return true;
    }
}
