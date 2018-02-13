<?php

namespace App\Cruds;

use App\Repositories\MatchRepository;

/**
 * Class MatchCrud
 */
class MatchCrud extends AbstractCrud
{
    /**
     * @var StatisticCrud
     */
    protected $statisticCrud;

    /**
     * @param MatchRepository $repository
     * @param StatisticCrud $statisticCrud
     */
    public function __construct(
        MatchRepository $repository,
        StatisticCrud $statisticCrud
    ) {
        $this->repository    = $repository;
        $this->statisticCrud = $statisticCrud;
    }

    /**
     * {@inheridocs}
     */
    public function create()
    {
        parent::create();
        $this->statisticCrud->setMatch($this->instance)->create();

        return $this->instance;
    }

    /**
     * {@inheridocs}
     */
    public function update()
    {
        parent::update();
        $this->statisticCrud->setMatch($this->instance)->update();

        return $this->instance;
    }
}
