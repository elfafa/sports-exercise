<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    protected $fillable = [
        'sport',
        'competition',
        'external_id',
        'team_home_id',
        'team_home_name',
        'team_away_id',
        'team_away_name',
        'season',
        'feed_file',
        //
        'created_at',
        'updated_at',
    ];

    /**
     * Get the statistic associated with the match.
     */
    public function statistic()
    {
        return $this->hasOne(Statistic::class);
    }
}
