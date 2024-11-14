<?php

namespace App\Recommendation\Infrastructure\Composers;

use Box\Spout\Common\Entity\Row;
use Box\Spout\Common\Exception\IOException;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Writer\CSV\Writer;
use Box\Spout\Writer\Exception\WriterNotOpenedException;

class CsvFileComposer
{
    private Writer $writer;

    /**
     * @var string[]
     */
    private array $columns;

    private bool $isHeaderWritten = false;

    private string $fileUrl;

    /**
     * @throws IOException
     */
    public function __construct(array $columns, string $fileUrl)
    {
        $this->columns = $columns;
        $this->fileUrl = $fileUrl;
        $this->initCsvWriter();
    }

    /**
     * Initialize the CSV writer
     *
     * @throws IOException
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
     * @throws WriterNotOpenedException
     * @throws IOException
     */
    public function addRow(array $rowData): void
    {
        if (! $this->isHeaderWritten) {
            $this->writer->addRow(WriterEntityFactory::createRowFromArray($this->columns));
            $this->isHeaderWritten = true;
        }

        $row = [];
        foreach ($this->columns as $column) {
            $row[] = $rowData[$column] ?? '';
        }

        $this->writer->addRow(WriterEntityFactory::createRowFromArray($row));
    }

    /**
     * Close the writer and finalize the file
     */
    public function closeWriter(): void
    {
        if ($this->writer !== null) {
            $this->writer->close();
        }
    }
}
