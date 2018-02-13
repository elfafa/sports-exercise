<?php

namespace App\Repositories;

/**
 * Class MatchRepository
 */
class MatchRepository extends AbstractRepository
{
    /**
     * {@inheritdoc}
     */
    public $model = '\App\Match';

    const MINIMUM_GOALS = 4;
    const DEFAULT_QUANTITY = 5;

    /**
     * Get top matches with $minimumGoals minimum goals
     *
     * @return AbstractModel[]
     */
    public function getTop($minimumGoals = self::MINIMUM_GOALS)
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
            ->get()
        ;
    }

    /**
     * Get all matches for a team
     *
     * @return AbstractModel[]
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
