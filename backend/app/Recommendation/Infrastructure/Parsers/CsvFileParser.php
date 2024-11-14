<?php

namespace App\Recommendation\Infrastructure\Parsers;

use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Box\Spout\Reader\CSV\Reader;
use Box\Spout\Reader\XLSX\Reader as XLSXReader;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;

class CsvFileParser
{
    private static string $rootDirectoryPath;

    private static Reader|XLSXReader $reader;

    private static string $filePath;

    private static \Iterator $rowIterator;

    private static bool $initialized = false;

    private static bool $isOnlyHeader = true;

    private static array $headers = [];

    public static function parseNextRow(?string $fileUrl = null): ?array
    {
        if (! self::$initialized) {
            self::$filePath = $fileUrl;
            self::init();
            self::execute();
        }

        if (! self::$rowIterator->valid()) {
            self::terminate();
            if (self::$isOnlyHeader) {
                self::$isOnlyHeader = true;
                throw new InvalidArgumentException('The CSV file contains only header row and no data.');
            }

            return null;
        }

        $row = self::$rowIterator->current();
        $row = $row->toArray();
        self::$rowIterator->next();

        if (empty(self::$headers)) {
            self::$headers = $row;

            return self::$headers;
        }

        self::$isOnlyHeader = false;

        return array_combine(self::$headers, $row);
    }

    private static function init(): void
    {
        self::initCsvReader();
        self::$initialized = true;
    }

    private static function execute(): void
    {
        self::$reader->open(self::$filePath);
        self::$rowIterator = self::$reader->getSheetIterator()->current()->getRowIterator();
        if (self::$rowIterator->valid()) {
            self::$rowIterator->next();
        }
    }

    private static function terminate(): void
    {
        self::$reader->close();
        //        self::deleteFile();
        self::$initialized = false;
    }

    private static function initCsvReader(): void
    {
        $fileExtension = pathinfo(self::$filePath, PATHINFO_EXTENSION);
        $fileContent = file_get_contents(self::$filePath);
        if (mb_detect_encoding($fileContent, 'UTF-8', true) === false) {
            $fileContent = mb_convert_encoding($fileContent, 'UTF-8', 'Windows-1251');
            file_put_contents(self::$filePath, $fileContent);
        }
        if ($fileExtension === 'csv' || $fileExtension === 'tmp') {
            self::$reader = ReaderEntityFactory::createCSVReader();
            self::$reader->setFieldDelimiter(';');
            self::$reader->setFieldEnclosure('"');
        } elseif ($fileExtension === 'xlsx') {
            self::$reader = ReaderEntityFactory::createXLSXReader();
        }
    }

    private static function deleteFile(): void
    {
        if (Storage::exists(self::$filePath)) {
            Storage::delete(self::$filePath);
        }
    }
}
