<?php

namespace App\Recommendation\Infrastructure\Composers;

use App\Recommendation\Application\DTO\AttachmentRecommendationDto;
use Box\Spout\Common\Entity\Row;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Writer\CSV\Writer;
use Box\Spout\Writer\Exception\WriterNotOpenedException;
use Box\Spout\Common\Exception\InvalidArgumentException;
use Box\Spout\Common\Exception\IOException;
use Illuminate\Support\Facades\Storage;

class CsvFileComposer
{
    /** @var Writer */
    private Writer $writer;

    /**
     * @var string[]
     */
    private array $columns;

    /**
     * @var bool
     */
    private bool $isHeaderWritten = false;

    /**
     * @var string
     */
    private string $fileUrl;

    public function __construct(array $columns = [], string $fileUrl)
    {
        $this->columns = $columns;
        $this->fileUrl = $fileUrl;
        $this->initCsvWriter();
    }


    /**
     * Initialize the CSV writer
     *
     * @return void
     */
    private function initCsvWriter(): void
    {
        $this->writer = WriterEntityFactory::createCSVWriter();
        $this->writer->setFieldDelimiter(';');
        $this->writer->openToFile($this->fileUrl);
    }

    /**
     * Add a single row to the CSV file
     *
     * @param array $rowData
     * @return void
     * @throws WriterNotOpenedException
     */
    public function addRow(array $rowData): void
    {
        if (!$this->isHeaderWritten) {
            $this->writer->addRow(WriterEntityFactory::createRowFromArray($this->columns));
            $this->isHeaderWritten = true;
        }

        $row = [];
        foreach ($this->columns as $column) {
            $row[] = $rowData[$column] ?? "";
        }

        $this->writer->addRow(WriterEntityFactory::createRowFromArray($row));
    }

    /**
     * Close the writer and finalize the file
     *
     * @return void
     */
    public function closeWriter(): void
    {
        if ($this->writer !== null) {
            $this->writer->close();
        }
    }
}
