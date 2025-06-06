<?php

namespace App\Services\Storage;

use App\Models\Client;
use Illuminate\Support\Facades\DB;

class ClientStorage
{
    /**
     * @throws \Throwable
     */
    public function upsert(array $data, string $uniqueField, array $keys): true
    {
        DB::beginTransaction();

        try {
            foreach (array_chunk($data, 1000) as $chunk) {
                Client::query()->upsert(
                    $chunk,
                    $uniqueField,
                    $keys,
                );
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        return true;
    }
}
