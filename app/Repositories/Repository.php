<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

class Repository
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * BaseRepository constructor.
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function create(array $attributes): Model
    {
        return $this->model->create($attributes);
    }

    /**
     * @return ?Model
     */
    public function find($id): ?Model
    {
        return $this->model->find($id);
    }
}
