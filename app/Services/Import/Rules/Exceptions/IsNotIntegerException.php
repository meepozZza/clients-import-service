<?php

namespace App\Services\Import\Rules\Exceptions;

final class IsNotIntegerException extends CellException
{
    protected $message = 'Cell %s is not integer.';
}
