<?php

namespace App\Cruds;

use App\AbstractModel;
use Exception;
use Ramsey\Uuid\Uuid;
use App\Repositories\AbstractRepo;

/**
 * Class AbstractCrud
 */
abstract class AbstractCrud
{
    /**
     * Created instance holder
     *
     * @var AbstractModel
     */
    protected $instance;

    /**
     * Repository instance holder
     *
     * @var AbstractRepo
     */
    protected $repository;

    /**
     * Set data
     *
     * @param $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get instance
     *
     * @return AbstractModel
     */
    public function getInstance()
    {
        return $this->instance;
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
     * @param $field
     * @param $value
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
