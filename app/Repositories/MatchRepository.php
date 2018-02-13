<?php

namespace App\Repositories;

use Illuminate\Support\Collection;

/**
 * Class MatchRepository
 */
class MatchRepository extends AbstractRepository
{
    /**
     * {@inheritdocs}
     */
    public $model = '\App\Match';

    /**
     * @var int
     */
    const MINIMUM_GOALS = 4;

    /**
     * @var int
     */
    const DEFAULT_QUANTITY = 5;

    /**
     * Get top matches with $minimumGoals minimum goals
     *
     * @param int $minimumGoals
     * @param int $quantity
     *
     * @return Collection
     */
    public function getTop($minimumGoals = self::MINIMUM_GOALS, $quantity = self::DEFAULT_QUANTITY)
    {
        return $this->model
            ->select([
                'matches.*',
                'statistics.*'
            ])
            ->join('statistics', function ($join) use ($minimumGoals) {
                $join
                    ->on('matches.id', '=', 'statistics.match_id')
                    ->on(\DB::raw($minimumGoals), '<=', 'statistics.total_goals')
                ;
            })
            ->orderBy('statistics.total_goals', 'desc')
            ->limit($quantity)
            ->get()
        ;
    }

    /**
     * Get all matches for a team
     *
     * @param string $team
     * @param int $quantity
     *
     * @return Collection
     */
    public function getForTeam($team, $quantity = self::DEFAULT_QUANTITY)
    {
        return $this->model
            ->select([
                'matches.*',
                'statistics.*'
            ])
            ->join('statistics', function ($join) {
                $join
                    ->on('matches.id', '=', 'statistics.match_id')
                ;
            })
            ->where('matches.team_home_name', '=', $team)
            ->orWhere('matches.team_away_name', '=', $team)
            ->orWhere('matches.team_home_id', '=', $team)
            ->orWhere('matches.team_away_id', '=', $team)
            ->orderBy('statistics.start_at', 'desc')
            ->limit($quantity)
            ->get()
        ;
    }
}
