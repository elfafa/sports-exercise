<?php

namespace App\Cruds;

use App\Repositories\MatchRepo;
use App\Transformers\MatchTransformer;

/**
 * Class MatchCrud
 */
class MatchCrud extends AbstractCrud
{
    private $statisticCrud;

    /**
     * @param MatchRepo $repository
     * @param MatchTransformer $transformer
     * @param StatisticCrud $statisticCrud
     */
    public function __construct(
        MatchRepo $repository,
        MatchTransformer $transformer,
        StatisticCrud $statisticCrud
    ) {
        $this->repository    = $repository;
        $this->transformer   = $transformer;
        $this->statisticCrud = $statisticCrud;
    }

    /**
     * {@inheritdoc}
     */
    public function create()
    {
        parent::create();
        $this->statisticCrud->setMatch($this->instance)->create();

        return $this->instance;
    }

    /**
     * {@inheritdoc}
     */
    public function update()
    {
        parent::update();
        $this->statisticCrud->setMatch($this->instance)->update();

        return $this->instance;
    }
}
