<?php

namespace App\Http\Transformers;

use App\Match;
use App\Statistic;
use Illuminate\Support\Collection;

class MatchTransformer
{
    public function transformCollection(Collection $matches)
    {
        $transformed = [];
        foreach ($matches as $match) {
            $transformed[] = $this->transformModel($match);
        }

        return $transformed;
    }

    public function transformModel(Match $match)
    {
        $statistic  = $match->statistic;
        $output = [
            'competition' => (string) $match->competition,
            'match_id'    => (string) $match->external_id,
            'season'      => (string) $match->season,
            'sport'       => (string) $match->sport,
            'teams'       => [
                'home' => (string) $match->team_home_name,
                'away' => (string) $match->team_away_name,
            ],
            'match_length' => (int) $statistic->elapsed_time,
            'match_date'   => (string) $statistic->start_at,
            'created_at'   => (string) $match->created_at,
            'updated_at'   => (string) $match->updated_at,
            'complete'     => (bool) (Statistic::STATUS_END === $statistic->status),
            'stats'        => [
                'top_scorer'   => (string) $statistic->top_scorer_names,
                'winner'       => (string) $statistic->winner_team,
                'total_goals'  => (int) $statistic->total_goals,
                'red_cards'    => (int) $statistic->red_cards,
                'yellow_cards' => (int) $statistic->yellow_cards,
                'home'         => [
                    'total_tackles' => (int) $statistic->team_home_tackles,
                    'total_touches' => (int) $statistic->team_home_touches,
                    'total_fouls'   => (int) $statistic->team_home_fouls,
                ],
                'away' => [
                    'total_tackles' => (int) $statistic->team_away_tackles,
                    'total_touches' => (int) $statistic->team_away_touches,
                    'total_fouls'   => (int) $statistic->team_away_fouls,
                ],
            ],
        ];

        return $output;
    }

}
