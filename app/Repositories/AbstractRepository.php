<?php

namespace App\Repositories;

use App\AbstractModel;
use Illuminate\Database\Query;
use Stikit\Traits\UidTrait;

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
     * @return AbstractModel[]
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
     * Find all instances where the field matches the value, based on a condition
     *
     * @param  string $field
     * @param  string $value
     * @param  string $condition
     * @return AbstractModel[]
     */
    public function findAllWhere($field, $value, $condition = '=')
    {
        return $this->model->where($field, $condition, $value)->get();
    }

    /**
     * Find all instances where the field matches the value, based on a condition
     *
     * @param  string $field
     * @param  string $value
     * @param  string $condition
     * @return AbstractModel[]
     */
    public function findAllDistinctWhere($field, $value, $condition = '=')
    {
        return $this->model->where($field, $condition, $value)->distinct()->get();
    }

    /**
     * Find all instances where the field matches the values (array), based on a condition
     *
     * @param  string $field
     * @param  mixed $values array
     * @return AbstractModel[]
     */
    public function findAllWhereIn($field, $values)
    {
        return $this->model->whereIn($field, $values)->get();
    }

    /**
     * Add 'where X = xxx' condition to builder
     *
     * @param  array|string $column
     * @param  string|null $operator
     * @param  string|null $value
     * @return Builder
     */
    public function where($column, $operator = null, $value = null)
    {
        return $this->model->where($column, $operator, $value);
    }

    /**
     * Add 'where X in (xxx)' condition to builder
     *
     * @param  string $field
     * @param  array $value
     * @return Builder
     */
    public function whereIn($field, $values)
    {
        return $this->model->whereIn($field, $values);
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
