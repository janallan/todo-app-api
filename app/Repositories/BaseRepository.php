<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class BaseRepository implements BaseInterface
{
    /**
     * @var \Illuminate\Database\Eloquent\Model $model
     */
    private $model;

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Get list of resource
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function all(): LengthAwarePaginator
    {
        return $this->model
            ->orderBy('created_at', 'desc')
            ->paginate();
    }

    /**
     * Create new resource
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data): Model
    {
        $model = $this->model->create($data);

        $model->refresh();

        return $model;
    }

    /**
     * Get specified resource
     * @param int|string $id
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function find($id): ?Model
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Update specified resource
     * @param int|string $id
     * @param array $data
     */
    public function update($id, array $data): Model
    {
        $this->model
        ->where($this->model->getKeyName(), $id)
        ->update($data);

        return $this->find($id);
    }

    /**
     * Delete specified resource
     * @param int|string $id
     * @return void
     */
    public function delete($id): void
    {
        $this->model->where($this->model->getKeyName(), $id)
            ->delete();
    }
}
