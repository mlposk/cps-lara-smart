<?php

namespace App\Recommendation\Application\UseCases\Commands;

use App\Recommendation\Application\DTO\AttachmentRecommendationDto;
use App\Recommendation\Application\Mappers\RecommendationMapper;
use App\Recommendation\Infrastructure\Mail\ProcessedFileEmail;
use App\Recommendation\Infrastructure\Parsers\CsvFileParser;
use App\Recommendation\Infrastructure\Composers\CsvFileComposer;
use Box\Spout\Writer\Exception\WriterNotOpenedException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class FileRecommendationParserCommand
{
    private CsvFileComposer $csvFileComposer;

    private AttachmentRecommendationDto $attachmentDto;

    public function __construct(private readonly AttachmentRecommendationDto $attachmentRecommendationDto)
    {
    }

    /**
     * @throws BindingResolutionException
     */
    public function execute(): void
    {
        $this->initCsvFileComposer();

        while (true) {
            if ($row = CsvFileParser::parseNextRow()) {
                $recommendation = RecommendationMapper::fromArray($row);
                $recommendation->execute();
                $row = array_merge($row, $recommendation->toArray()['answer']);
                $this->csvFileComposer->addRow($row);
            } else {
                $this->csvFileComposer->closeWriter();
                break;
            }
        }

        $this->sendMail();
    }

    /**
     * @throws WriterNotOpenedException
     */
    private function initCsvFileComposer(): void
    {
        $fileName = $this->attachmentRecommendationDto->jobId . '_converted.csv';
        $filePath = Storage::disk('public')->path('recommendations/' . $fileName);

        $this->attachmentDto = new AttachmentRecommendationDto(
            jobId: $this->attachmentRecommendationDto->jobId,
            userEmail: $this->attachmentRecommendationDto->userEmail,
            filePath: Storage::disk('public')->url('recommendations/' . $fileName)
        );

        $row = CsvFileParser::parseNextRow($this->attachmentRecommendationDto->filePath);
        $this->csvFileComposer = new CsvFileComposer($row, $filePath);
        $this->csvFileComposer->addRow([]);
    }

    private function sendMail(): void
    {
        Mail::to($this->attachmentDto->userEmail)
            ->send(new ProcessedFileEmail($this->attachmentDto));
    }
}
