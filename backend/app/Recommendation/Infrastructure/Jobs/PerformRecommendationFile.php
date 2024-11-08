<?php

namespace App\Recommendation\Infrastructure\Jobs;

use App\Recommendation\Application\DTO\AttachmentRecommendationDto;
use App\Recommendation\Application\UseCases\Commands\FileRecommendationParserCommand;
use App\Recommendation\Domain\Model\Aggregates\Recommendation;
use App\Recommendation\Infrastructure\Mail\ProcessedFileEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class PerformRecommendationFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        private readonly AttachmentRecommendationDto $attachmentRecommendationDto,
        private readonly Recommendation $recommendation,
    ) {
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws BindingResolutionException
     */
    public function handle()
    {
        (new FileRecommendationParserCommand($this->attachmentRecommendationDto, $this->recommendation))->execute();
    }
}
