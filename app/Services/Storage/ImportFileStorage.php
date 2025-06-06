<?php

namespace App\Services\Storage;

use App\Enums\ImportFileStatusEnum;
use App\Models\ImportFile;

class ImportFileStorage
{
    public function create(array $data): ImportFile
    {
        if (empty($data['status'])) {
            $data['status'] = ImportFileStatusEnum::PENDING;
        }

        $importFile = ImportFile::query()
            ->create($data);

        if (! $importFile->redis_ukey) {
            $this->update($importFile, ['redis_ukey' => 'import_files_'.$importFile->id]);
        }

        return $importFile;
    }

    public function update(ImportFile $file, $data): bool
    {
        return $file->update($data);
    }
}
