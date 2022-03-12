<?php

namespace App\Repositories\Tag;

use App\Models\Tag;
use App\Repositories\BaseRepository;
use App\Repositories\User\Contracts\OperatorInterface;
use App\Repositories\Tag\Contracts\TagInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class TagRepository extends BaseRepository implements TagInterface
{
    /**
     * Create new BaseRepository instance
     */
    public function __construct()
    {
        parent::__construct(new Tag());
    }

    /**
     * Get User's Tags
     *
     * @param int|string $userId
     * @return \Illuminate\Http\Response
     * @creator Jan Allan Verano
     */
    public function getUserTags(int|string $userId): LengthAwarePaginator
    {
        return QueryBuilder::for(Tag::class)
            ->where('user_id', $userId)
            ->allowedFilters([
                AllowedFilter::callback('search', function (Builder $query, $value) {
                    $query->whereRaw("tag.name LIKE '%{$value}%'");
                }),
                AllowedFilter::callback('created', function (Builder $query, $value) {
                    if (isset($value['from'])  && isset($value['to'])) {
                        $query->whereRaw("DATE(tag.created_at) BETWEEN '{$value['from']}' AND '{$value['to']}'");
                    }
                })
            ])
            ->select('tags.*')
            ->paginate(15);
    }
}
