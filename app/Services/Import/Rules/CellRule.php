<?php

namespace App\Services\Import\Rules;

interface CellRule
{
    public static function validate(mixed $value): true;
}
