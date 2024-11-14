<?php

namespace App\Recommendation\Application\UseCases\Commands;

use App\Recommendation\Application\DTO\AttachmentRecommendationDto;
use App\Recommendation\Application\Mappers\AnswerMapper;
use App\Recommendation\Domain\Contracts\Repositories\RecommendationRepositoryInterface;
use App\Recommendation\Domain\Model\Aggregates\Recommendation;
use App\Recommendation\Infrastructure\Composers\CsvFileComposer;
use App\Recommendation\Infrastructure\Mail\ProcessedFileEmail;
use App\Recommendation\Infrastructure\Parsers\CsvFileParser;
use Box\Spout\Common\Exception\IOException;
use Box\Spout\Writer\Exception\WriterNotOpenedException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class FileRecommendationParserCommand
{
    private CsvFileComposer $csvFileComposer;

    private RecommendationRepositoryInterface $repository;

    private array $payload;

    private ?array $columns;

    /**
     * @throws BindingResolutionException
     */
    public function __construct(
        private AttachmentRecommendationDto $attachmentRecommendationDto,
        private readonly Recommendation $recommendation
    ) {
        $this->repository = app()->make(RecommendationRepositoryInterface::class);
    }

    private function initPayload(): void
    {
        $filerPath = $this->attachmentRecommendationDto->filePath;
        $columns = CsvFileParser::parseNextRow($filerPath);

        if (! $columns) {
            throw new \InvalidArgumentException('Empty fields');
        }
        if (array_diff($columns, ['title', 'body', 'project', 'smartTitle', 'recommendation'])) {
            throw new \InvalidArgumentException('invalid fields: '.implode(',', $columns));
        }

        $this->columns = $columns;

        while (true) {
            if ($row = CsvFileParser::parseNextRow()) {
                $this->payload[] = $row;
            } else {
                break;
            }
        }
    }

    /**
     * @throws \Exception
     */
    private function fillRecommendation(): void
    {
        $answers = AnswerMapper::fromArray($this->payload);
        $this->recommendation->addAnswer($answers);
        $this->recommendation->executeAnswer();
    }

    /**
     * @throws IOException
     * @throws WriterNotOpenedException
     */
    private function fillPayload(): void
    {
        $answersArray = $this->recommendation->getAnswers();
        $fileName = $this->attachmentRecommendationDto->jobId.'_converted.csv';
        $filePath = Storage::disk('public')->path('recommendations/'.$fileName);
        $this->csvFileComposer = new CsvFileComposer($this->columns, $filePath);
        foreach ($answersArray as $item) {
            $this->csvFileComposer->addRow($item);
        }
        $this->csvFileComposer->closeWriter();

        $this->reBuildDto($filePath);
    }

    private function reBuildDto($filePath): void
    {
        $this->attachmentRecommendationDto = new AttachmentRecommendationDto(
            $this->attachmentRecommendationDto->userEmail,
            $filePath,
            $this->attachmentRecommendationDto->jobId
        );
    }

    /**
     * @throws \Exception
     */
    public function execute(): void
    {
        $this->initPayload();
        $this->fillRecommendation();
        $this->fillPayload();

        $this->repository->store($this->recommendation);
        $this->sendMail();
    }

    private function sendMail(): void
    {
        Mail::to($this->attachmentRecommendationDto->userEmail)
            ->send(new ProcessedFileEmail($this->attachmentRecommendationDto));
    }
}
