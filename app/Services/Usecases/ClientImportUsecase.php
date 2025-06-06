<?php

namespace App\Services\Usecases;

use App\Enums\ImportFileStatusEnum;
use App\Events\ClientXlsxImported;
use App\Models\Client;
use App\Services\Import\Client\ClientExcelImportService;
use App\Services\Storage\ClientStorage;
use App\Services\Storage\ImportFileStorage;
use Illuminate\Http\UploadedFile;
use OpenSpout\Common\Exception\IOException;
use OpenSpout\Reader\Exception\ReaderNotOpenedException;
use Throwable;

class ClientImportUsecase
{
    public function __construct(
        private readonly ClientExcelImportService $importService,
        private readonly ImportFileStorage $importFileStorage,
        private readonly ClientStorage $clientStorage,
    ) {}

    /**
     * @throws IOException
     * @throws Throwable
     * @throws ReaderNotOpenedException
     */
    public function import(UploadedFile $file): bool
    {
        $importFile = $this->importFileStorage->create([
            'file' => $file->getContent(),
            'type' => Client::class,
        ]);

        try {
            $this->importService->import($importFile);
        } catch (Throwable $e) {
            $this->importFileStorage->update($importFile, ['status' => ImportFileStatusEnum::FAILED]);

            throw $e;
        }

        $importedData = $this->importService->getImportedData();
        $errors = $this->importService->getErrors();

        $this->importFileStorage->update(
            $importFile,
            ['status' => empty($errors) ? ImportFileStatusEnum::SUCCESS : ImportFileStatusEnum::WITH_ERRORS],
        );

        $this->clientStorage->upsert(
            $importedData,
            'external_id',
            ['name', 'date']
        );

        broadcast(new ClientXlsxImported($importedData, $errors, $importFile));

        file_put_contents(base_path('result.txt'), json_encode($errors));

        return true;
    }

    public function importService(): ClientExcelImportService
    {
        return $this->importService;
    }
}
