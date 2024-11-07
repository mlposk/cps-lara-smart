<?php

namespace App\Recommendation\Application\Mappers;

use App\Recommendation\Domain\Model\ValueObjects\Query\Body;
use App\Recommendation\Domain\Model\ValueObjects\Query\Query;
use App\Recommendation\Domain\Model\ValueObjects\Query\QueryCollection;
use App\Recommendation\Domain\Model\ValueObjects\Query\Title;
use App\Recommendation\Domain\Model\ValueObjects\Query\Project;
use App\Recommendation\Domain\Model\ValueObjects\Query\Deadline;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class QueryMapper
{
    /**
     * @throws Exception
     */
    public static function fromRequest(Request $request): QueryCollection
    {
        $collection = collect();
        $collection->push(
            new Query(
                title: new Title($request->input('title')),
                body: new Body($request->input('body')),
                project: new Project($request->input('project', '')),
                deadline: new Deadline($request->input('deadline', ''))
            )
        );
        return new QueryCollection($collection);
    }

    /**
     * @throws Exception
     */
    public static function fromArray(array $array): Query
    {
        return new Query(
            title: new Title($array['title']),
            body: new Body($array['body']),
            project: new Project($array['project'] ?? ''),
            deadline: new Deadline($array['deadline'] ?? '')
        );
    }
}
