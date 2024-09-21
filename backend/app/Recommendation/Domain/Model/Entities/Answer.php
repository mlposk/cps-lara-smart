<?php

namespace App\Recommendation\Domain\Model\Entities;

use App\Common\Domain\Entity;
use App\Recommendation\Domain\Model\ValueObjects\Query\Query;

class Answer extends Entity
{

    private Query $query;

    public function __construct()
    {
    }

    /**
     * @param Query $query
     * @return void
     */
    public function setQuery(Query $query): void
    {
        $this->query = $query;
    }

    public function toArray(): array
    {
        return [
            'test' => 'Texte tete',
            'message' => 'Some text'
        ];
    }
}
