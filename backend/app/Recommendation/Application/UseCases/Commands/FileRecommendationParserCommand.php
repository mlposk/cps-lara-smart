<?php

namespace App\Recommendation\Application\UseCases\Commands;

use App\Recommendation\Application\DTO\AttachmentRecommendationDto;
use App\Recommendation\Application\Mappers\RecommendationMapper;
use App\Recommendation\Domain\Contracts\Repositories\RecommendationRepositoryInterface;
use App\Recommendation\Infrastructure\Mail\ProcessedFileEmail;
use App\Recommendation\Infrastructure\Parsers\CsvFileParser;
use App\Recommendation\Infrastructure\Composers\CsvFileComposer;
use Box\Spout\Common\Exception\IOException;
use Box\Spout\Writer\Exception\WriterNotOpenedException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class FileRecommendationParserCommand
{
    private CsvFileComposer $csvFileComposer;
    private RecommendationRepositoryInterface $repository;
    private AttachmentRecommendationDto $attachmentDto;

    /**
     * @throws BindingResolutionException
     */
    public function __construct(
        private readonly AttachmentRecommendationDto $attachmentRecommendationDto
    ) {
        $this->repository = app()->make(RecommendationRepositoryInterface::class);
    }

    /**
     * @throws BindingResolutionException
     * @throws \Exception
     */
    public function execute(): void
    {
        $this->initCsvFileComposer();
        $rows = [];
        while (true) {
            if ($row = CsvFileParser::parseNextRow()) {
                $rows[] = $row;
            } else {
                $this->csvFileComposer->closeWriter();
                break;
            }
        }

        $recommendation = RecommendationMapper::fromArray(
            $rows,
            ['source' => 'email', 'value' => $this->attachmentRecommendationDto->userEmail]
        );

        $recommendation->getRecommendation();

         $domainRecommendation = $this->repository->store($recommendation);

         $answersArray = $recommendation->getAnswers();

        // $row = array_merge($row, $recommendation->toArray()['answer']);


        $this->csvFileComposer->addRow($row);

        $this->sendMail();
    }

    /**
     * @throws WriterNotOpenedException
     * @throws IOException
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
