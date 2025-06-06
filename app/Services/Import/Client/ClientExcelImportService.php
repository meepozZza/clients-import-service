<?php

namespace App\Services\Import\Client;

use App\Services\Import\ExcelImportService;
use App\Services\Import\Rules\DateStringValue;
use App\Services\Import\Rules\ExceptEmpty;
use App\Services\Import\Rules\IntegerValue;
use App\Services\Import\Rules\StringValue;
use Illuminate\Support\Carbon;

class ClientExcelImportService extends ExcelImportService
{
    protected static function cellValidationRules(?string $field = null): array
    {
        $rules = [
            'id' => [
                ExceptEmpty::class,
                IntegerValue::class,
            ],
            'name' => [
                ExceptEmpty::class,
                StringValue::class,
            ],
            'date' => [
                ExceptEmpty::class,
                DateStringValue::class,
            ],
        ];

        return $rules[$field] ?? $rules;
    }

    protected static function cellFormats(string $field, mixed $value): mixed
    {
        $formats = [
            'date' => fn () => Carbon::create($value)->format('Y-m-d'),
        ];

        if (isset($formats[$field])) {
            $value = call_user_func($formats[$field]);
        }

        return $value;
    }

    protected static function headingRenames(string $heading): ?string
    {
        $names = [
            'id' => 'external_id',
        ];

        return $names[$heading] ?? $heading;
    }
}
