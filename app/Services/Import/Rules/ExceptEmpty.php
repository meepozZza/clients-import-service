<?php

namespace App\Services\Import\Rules;

use App\Services\Import\Rules\Exceptions\ExceptEmptyException;

final class ExceptEmpty implements CellRule
{
    public static function validate(mixed $value): true
    {
        if (empty($value)) {
            throw new ExceptEmptyException;
        }

        return true;
    }
}
