<?php

namespace App\Recommendation\Domain\Repositories;

use App\Recommendation\Domain\Model\Entities\Task;

interface TaskRepositoryInterface{
    public function store(Task $task);
}
