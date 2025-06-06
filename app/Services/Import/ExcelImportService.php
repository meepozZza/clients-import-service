<?php

namespace App\Services\Import;

use App\Models\ImportFile;
use App\Services\Import\Rules\Exceptions\CellException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use OpenSpout\Common\Entity\Cell\EmptyCell;
use OpenSpout\Common\Exception\IOException;
use OpenSpout\Reader\Exception\ReaderNotOpenedException;
use OpenSpout\Reader\XLSX\Options;
use OpenSpout\Reader\XLSX\Reader;

abstract class ExcelImportService
{
    private array $importedData = [];

    private array $errors = [];

    abstract protected static function headingRenames(string $heading): ?string;

    abstract protected static function cellFormats(string $field, mixed $value): mixed;

    abstract protected static function cellValidationRules(): array;

    /**
     * @throws IOException
     * @throws ReaderNotOpenedException
     */
    public function import(ImportFile $file): void
    {
        $tempPath = 'temp/'.uuid_create().'.xlsx';
        $tempFilePath = Storage::path($tempPath);
        Storage::put($tempPath, $file->file);

        $options = new Options;
        $options->SHOULD_PRESERVE_EMPTY_ROWS = true;

        $reader = new Reader($options);
        $reader->open($tempFilePath);

        foreach ($reader->getSheetIterator() as $sheet) {
            $headings = [];
            $maxRows = 0;

            foreach ($sheet->getRowIterator() as $rowIndex => $row) {
                if ($maxRows === 0) {
                    $headings = array_filter($row->toArray());
                    $maxRows = count($headings);

                    continue;
                }

                $cells = array_filter(
                    $row->getCells(),
                    fn ($c, $k) => $k < $maxRows && ! $c instanceof EmptyCell,
                    ARRAY_FILTER_USE_BOTH,
                );

                $currentCellsCount = count($cells);

                if ($currentCellsCount > $maxRows) {
                    $this->errors[$rowIndex][] = 'Too many cells.';
                }

                $datum = [];

                for ($i = 0; $i < $maxRows; $i++) {
                    $cell = $cells[$i] ?? null;
                    $heading = $headings[$i];

                    try {
                        $this->validateCell($heading, $cell?->getValue());
                    } catch (CellException $e) {
                        $this->errors[$rowIndex][] = $e->getMessageWithCell($this->getRowIdentifier($i, $rowIndex));

                        continue;
                    } catch (\Throwable $e) {
                        continue;
                    }

                    $datum[static::headingRenames($heading)] = $this->formatCell($heading, $cell->getValue());
                }

                if (count($datum) === $maxRows) {
                    if (! Cache::has($file->redis_ukey)) {
                        Cache::set($file->redis_ukey, 1);
                    } else {
                        Cache::increment($file->redis_ukey);
                    }

                    $this->importedData[] = $datum;
                }
            }
        }

        $reader->close();
        Storage::delete($tempFilePath);
    }

    /**
     * @throws CellException
     */
    private function validateCell(string $field, mixed $value): void
    {
        $rules = static::cellValidationRules($field);

        foreach ($rules as $rule) {
            $rule::validate($value);
        }
    }

    private function formatCell(string $field, mixed $value): mixed
    {
        return static::cellFormats($field, $value);
    }

    private function getRowIdentifier(int $rowIndex, int $columnIndex): string
    {
        $letter = '';

        while ($rowIndex >= 0) {
            $letter = chr(65 + ($rowIndex % 26)).$letter;
            $rowIndex = (int) ($rowIndex / 26) - 1;
        }

        return $letter.$columnIndex;
    }

    public function getImportedData(): array
    {
        return $this->importedData;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
