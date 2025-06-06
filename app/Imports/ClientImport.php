<?php

namespace App\Imports;

use App\Models\Client;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithValidation;

class ClientImport implements ToArray, WithChunkReading, WithValidation
{
    public function array(array $row)
    {
        return new Client([
            'external_id' => $row[0],
            'name' => $row[1],
            'date' => $row[2],
        ]);
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function rules(): array
    {
        return [
            '0' => function ($attribute, $value, $onFailure) {
                empty($value) && $onFailure('Empty');
            },
            '1' => function ($attribute, $value, $onFailure) {
                empty($value) && $onFailure('Empty');
            },
            '2' => function ($attribute, $value, $onFailure) {
                empty($value) && $onFailure('Empty');
            },
        ];
    }
}
