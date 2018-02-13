<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    protected $fillable = [
        'sport',
        'competition',
        'external_id',
        'team_home',
        'team_away',
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
