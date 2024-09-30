<?php

namespace App\Recommendation\Infrastructure\Mail;

use App\Recommendation\Application\DTO\AttachmentRecommendationDto;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
class ProcessedFileEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(
        public readonly AttachmentRecommendationDto $attachmentRecommendationDto
    )
    {
    }

    /**
     * Get the message envelope.
     *
     * @return Envelope
     */
    public function envelope()
    {

        return new Envelope(
            from: new Address('quadramind@email.com', 'Сервис Smart-рекомендаций'),
            subject: 'Задание по обработки файла выполнено',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return Content
     */
    public function content()
    {
        return new Content(
            view: 'emails.recommendation.processed_file',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments(): array
    {
        return [
            Attachment::fromPath($this->attachmentRecommendationDto->filePath),
        ];
    }
}
