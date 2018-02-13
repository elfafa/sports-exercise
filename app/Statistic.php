<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Statistic extends Model
{
    protected $fillable = [
        'match_id',
        'status',
        'elapsed_time',
        'start_at',
        'top_scorer_name',
        'winner_team',
        'total_goals',
        'red_cards',
        'yellow_cards',
        'team_home_taskles',
        'team_home_touches',
        'team_home_fouls',
        'team_away_taskles',
        'team_away_touches',
        'team_away_fouls',
    ];

    const STATUS_BEFORE  = 'before';
    const STATUS_CURRENT = 'current';
    const STATUS_END     = 'complete';

    /**
     * Get the match that owns the statistic.
     */
    public function match()
    {
        return $this->belongsTo(Match::class);
    }

}
