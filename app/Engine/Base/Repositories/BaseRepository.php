<?php

namespace App\Engine\Base\Repositories;

class BaseRepository implements BaseRepositoryInterface
{
    protected $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model::all();
    }

    public function find($id)
    {
        return $this->model::find($id);
    }

    public function create(array $data)
    {
        return $this->model::create($data);
    }

    public function update($id, array $data)
    {
        $model = $this->model::find($id);
        $model->update($data);
        return $model;
    }

    public function delete($id)
    {
        return $this->model::query()
            ->where([
                'id' => $id,
            ])
            ->delete();
    }


    public function dataTableData()
    {
        return $this->model::query()->whereNotNull('created_at');
    }
}
