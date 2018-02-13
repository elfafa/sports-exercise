<?php

namespace App\Cruds;

use App\AbstractModel;
use App\Repositories\AbstractRepository;
use Exception;

/**
 * Class AbstractCrud
 */
abstract class AbstractCrud
{
    /**
     * Datas from XML
     *
     * @var array
     */
    protected $datas;

    /**
     * Instance holder
     *
     * @var AbstractModel
     */
    protected $instance;

    /**
     * Repository instance holder
     *
     * @var AbstractRepository
     */
    protected $repository;

    /**
     * Set data
     *
     * @param array $data
     * @return $this
     */
    public function setData(array $data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Create instance
     *
     * @return AbstractModel
     */
    public function create()
    {
        $this->instance = $this->repository->create($this->data);

        return $this->instance;
    }

    /**
     * Find and set instance
     *
     * @param string $field
     * @param mixed $value
     *
     * @return AbstractModel
     */
    public function findWhere($field, $value)
    {
        $this->instance = $this->repository->findWhere($field, $value);

        return $this->instance;
    }

    /**
     * Update instance
     *
     * @return AbstractModel
     * @throws Exception
     */
    public function update()
    {
        if (is_null($this->instance)) {
            throw new Exception('No instance to update');
        }
        $this->instance = $this->repository->update($this->instance->id, $this->data);

        return $this->instance;
    }
}
