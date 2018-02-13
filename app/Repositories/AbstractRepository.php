<?php

namespace App\Repositories;

use App\AbstractModel;
use Illuminate\Support\Collection;

/**
 * Class AbstractRepository
 */
abstract class AbstractRepository
{
    /**
     * AbstractModel instance holder
     *
     * @var AbstractModel instance
     */
    public $model;

    /**
     */
    public function __construct()
    {
        $this->model = new $this->model;
    }

    /**
     * Get all instances
     *
     * @return Collection
     */
    public function all()
    {
        return $this->model->all();
    }

    /**
     * Find an instance either by identifier ($id int|string), or specific field ($id array(key => value))
     *
     * @param  int|string|array $id
     * @return AbstractModel|null
     */
    public function find($id)
    {
        return $this->model->find((int) $id);
    }

    /**
     * Find an instance where the field matches the value, based on a condition
     *
     * @param  string $field
     * @param  mixed $value
     * @param  string $condition
     * @return AbstractModel
     */
    public function findWhere($field, $value, $condition = '=')
    {
        return $this->model->where($field, $condition, $value)->first();
    }

    /**
     * Create an instance
     *
     * @param  array $data
     * @return AbstractModel
     */
    public function create($data)
    {
        return $this->model->create($data);
    }

    /**
     * Update an instance
     *
     * @param  int|string|array $id
     * @param  array $data
     * @return AbstractModel
     */
    public function update($id, $data)
    {
        $toUpdate = $this->find($id);

        foreach ($data as $field => $val) {
            if (in_array($field, $this->model->getFillable())) {
                $toUpdate->$field = $val;
            }
        }

        $toUpdate->save();

        return $toUpdate;
    }
}
