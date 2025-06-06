<?php

namespace App\Services\Import\Rules\Exceptions;

final class ExceptEmptyException extends CellException
{
    protected $message = 'Cell %s is empty.';
}
