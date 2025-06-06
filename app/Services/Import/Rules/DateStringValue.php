<?php

namespace App\Services\Import\Rules;

use App\Services\Import\Rules\Exceptions\DateStringException;
use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;

final class DateStringValue implements CellRule
{
    public static function validate(mixed $value): true
    {
        try {
            Carbon::parse($value);
        } catch (InvalidFormatException $e) {
            throw new DateStringException;
        }

        return true;
    }
}
