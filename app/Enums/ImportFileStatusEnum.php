<?php

namespace App\Enums;

enum ImportFileStatusEnum: string
{
    case SUCCESS = 'success';
    case PENDING = 'pending';
    case WITH_ERRORS = 'with_errors';
    case FAILED = 'failed';
}
