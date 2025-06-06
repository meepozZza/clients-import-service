<?php

namespace App\Services\Import\Rules\Exceptions;

final class DateStringException extends CellException
{
    protected $message = "Cell %s hasn't date format.";
}
