<?php

namespace App\Repositories;

use App\Models\Client;
use App\Repositories\Criteria\ClientRequestCriteria;
use Illuminate\Database\Eloquent\Builder;
use Prettus\Repository\Eloquent\BaseRepository;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * Class ClientRepositoryEloquent.
 */
class ClientRepositoryEloquent extends BaseRepository implements ClientRepository
{
    /**
     * {@inheritDoc}
     */
    public function all($columns = ['*'])
    {
        $this->applyCriteria();
        $this->applyScope();

        if ($this->model instanceof Builder) {
            $results = $this->model->get($columns);
        } elseif ($this->model instanceof QueryBuilder) {
            $results = $this->model->get($columns);
        } else {
            $results = $this->model->all($columns);
        }

        $this->resetModel();
        $this->resetScope();

        return $this->parserResult($results);
    }

    /**
     * {@inheritDoc}
     */
    public function paginate($limit = null, $page = null, $columns = ['*'], $method = 'paginate')
    {
        $this->applyCriteria();
        $this->applyScope();
        $limit = is_null($limit) ? config('repository.pagination.limit', 15) : $limit;
        $results = $this->model->{$method}($limit, $columns, 'page', $page);
        $results->appends(app('request')->query());
        $this->resetModel();

        return $this->parserResult($results);
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Client::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(new ClientRequestCriteria(request()));
    }
}
