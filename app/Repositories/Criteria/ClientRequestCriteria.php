<?php

namespace App\Repositories\Criteria;

use Prettus\Repository\Contracts\RepositoryInterface;
use Prettus\Repository\Criteria\RequestCriteria;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ClientRequestCriteria extends RequestCriteria
{
    public function apply($model, RepositoryInterface $repository)
    {
        return QueryBuilder::for($model::class)
            ->defaultSort('-id')
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::exact('external_id'),
                AllowedFilter::exact('name'),
                AllowedFilter::exact('date'),
            ])
            ->allowedIncludes([
                //
            ])
            ->scopes([
                //
            ])
            ->allowedSorts([
                'id',
                'external_id',
                'name',
                'date',
                'created_at',
                'updated_at',
            ]);
    }
}
