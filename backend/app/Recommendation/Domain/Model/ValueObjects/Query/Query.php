<?php

namespace App\Recommendation\Domain\Model\ValueObjects\Query;

use App\Common\Domain\ValueObject;
use Exception;

class Query extends ValueObject
{
    /**
     * @throws Exception
     */
    public function __construct(
        public Title $title,
        public Body $body,
        public Project $project,
        public Deadline $deadline
    ) {
    }

    public function toArray(): array
    {
        return [
            'title' => (string) $this->title,
            'body' => (string) $this->body,
            'project' => (string) $this->project,
            'deadline' => (string) $this->deadline,
        ];
    }
}
