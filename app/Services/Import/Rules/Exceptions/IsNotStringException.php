<?php

namespace App\Services\Import\Rules\Exceptions;

final class IsNotStringException extends CellException
{
    protected $message = 'Cell %s is not string.';
}
