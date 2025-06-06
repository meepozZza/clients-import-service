<?php

namespace App\Services\Import\Rules\Exceptions;

class CellException extends \Exception
{
    public function getMessageWithCell(string $cell): string
    {
        return sprintf($this->getMessage(), $cell);
    }
}
