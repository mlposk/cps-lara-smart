<?php

namespace App\Recommendation\Application\Mappers;

use App\Recommendation\Domain\Model\ValueObjects\Body;
use App\Recommendation\Domain\Model\ValueObjects\Query;
use App\Recommendation\Domain\Model\ValueObjects\Title;
use App\Recommendation\Domain\Model\ValueObjects\Project;
use App\Recommendation\Domain\Model\ValueObjects\Deadline;

use Exception;
use Illuminate\Http\Request;

class QueryMapper
{
    /**
     * @throws Exception
     */
    public static function fromRequest(Request $request): Query
    {
        return new Query(
            title: new Title($request->input('title')),
            body: new Body($request->input('body')),
            project: new Project($request->input('project', '')),
            deadline: new Deadline($request->input('deadline', ''))
        );
    }
}
