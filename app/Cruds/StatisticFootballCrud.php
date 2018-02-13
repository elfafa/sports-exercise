<?php

namespace App\Cruds;

use App\Statistic;

/**
 * Class StatisticFootballCrud
 */
class StatisticFootballCrud implements StatisticSpecificInterface
{
    /**
     * @var array
     */
    protected $analyzedXml = [];

    /**
     * {@inheridocs}
     */
    public function getAnalyzedXml(array $xml)
    {
        // initialize useful arrays (temporary)
        $matchDatas  = $xml['SoccerDocument']['MatchData'];
        $teams       = $xml['SoccerDocument']['Team'];
        $matchInfo   = $matchDatas['MatchInfo'];
        $teamPlayers = [];
        foreach ($teams as $team) {
            $teamPlayers[$team['@uID']] = $team['Player'];
        }

        // fill match status (before/current/complete)
        switch ($matchInfo['@Period']) {
            case 'PreMatch': $this->analyzedXml['status'] = Statistic::STATUS_BEFORE; break;
            case 'FullTime': $this->analyzedXml['status'] = Statistic::STATUS_END; break;
            default        : $this->analyzedXml['status'] = Statistic::STATUS_CURRENT; break;
        }

        // fill match starting date
        $this->analyzedXml['start_at'] = new \DateTime($matchInfo['Date']);

        // fill some match statistics - step 1
        if (array_key_exists('@Type', $matchDatas['Stat'])) {
            $this->fillSingleMatchStat($stat);
        } else {
            foreach ($matchDatas['Stat'] as $stat) {
                $this->fillSingleMatchStat($stat);
            }
        }

        // fill some team statistics - step 1
        $homeTeam = $awayTeam = null;
        foreach ($matchDatas['TeamData'] as $teamDatas) {
            if ('Home' === $teamDatas['@Side']) {
                $homeTeam = $this->getTeamStats(
                    $teamDatas,
                    $teamPlayers[$teamDatas['@TeamRef']]
                );
                $this->analyzedXml['team_home_id'] = $teamDatas['@TeamRef'];
            } else {
                $awayTeam = $this->getTeamStats(
                    $teamDatas,
                    $teamPlayers[$teamDatas['@TeamRef']]
                );
                $this->analyzedXml['team_away_id'] = $teamDatas['@TeamRef'];
            }
        }

        // fill some match statistics - step 2
        $this->analyzedXml['total_goals']      = $homeTeam['goals'] + $awayTeam['goals'];
        $this->analyzedXml['winner_team']      = $this->getWinnerTeam($homeTeam, $awayTeam);
        $this->analyzedXml['top_scorer_names'] = $this->getTopScorerNames($homeTeam, $awayTeam);

        // fill some team statistics - step 2
        $this->fillTeamStats($homeTeam, $awayTeam);

        return $this->analyzedXml;
    }

    /**
     * Fill $analyzedXml with useful match stats
     *
     * @param array $stat single XML single stat from XML
     */
    protected function fillSingleMatchStat(array $stat)
    {
        switch ($stat['@Type']) {
            case 'match_time': $this->analyzedXml['elapsed_time'] = (int) $stat['#text']; break;
            case 'goals'     : $this->analyzedXml['total_goals'] = (int) $stat['#text']; break;
        }
    }

    /**
     * Get winner team (null if same score)
     *
     * @param array $homeTeam stats from home team
     * @param array $awayTeam stats from away team
     */
    protected function getWinnerTeam(array $homeTeam, array $awayTeam)
    {
        $winnerTeam = null;
        if ($homeTeam['goals'] < $awayTeam['goals']) {
            $winnerTeam = 'away';
        } elseif ($homeTeam['goals'] > $awayTeam['goals']) {
            $winnerTeam = 'home';
        }

        return $winnerTeam;
    }

    /**
     * Get top scorer names (if same quantity of goals)
     *
     * @param array $homeTeam stats from home team
     * @param array $awayTeam stats from away team
     */
    protected function getTopScorerNames(array $homeTeam, array $awayTeam)
    {
        $topScorerNames = null;
        if (
            $homeTeam['top_scorers']['goals']
            && $homeTeam['top_scorers']['goals'] < $awayTeam['top_scorers']['goals']
        ) {
            $topScorerNames = $awayTeam['top_scorers']['names'];
        } elseif (
            $awayTeam['top_scorers']['goals']
            && $awayTeam['top_scorers']['goals'] < $homeTeam['top_scorers']['goals']
        ) {
            $topScorerNames = $homeTeam['top_scorers']['names'];
        } elseif (
            $awayTeam['top_scorers']['goals']
            && $awayTeam['top_scorers']['goals'] == $homeTeam['top_scorers']['goals']
        ) {
            $topScorerNames = $homeTeam['top_scorers']['names'].';'.$awayTeam['top_scorers']['names'];
        }

        return $topScorerNames;
    }

    /**
     * Fill $analyzedXml with useful team stats
     *
     * @param array $homeTeam stats from home team
     * @param array $awayTeam stats from away team
     */
    protected function fillTeamStats(array $homeTeam, array $awayTeam)
    {
        foreach ([ 'home' => $homeTeam, 'away' => $awayTeam ] as $teamKey => $team) {
            foreach ([ 'tackles', 'touches', 'fouls' ] as $key) {
                $this->analyzedXml['team_'.$teamKey.'_'.$key] = $team[$key];
            }
        }
    }

    /**
     * Get useful team stats
     *
     * @param array $teamDatas statistic of team
     * @param array $teamPlayers list of players for this team
     * @return array
     */
    protected function getTeamStats(array $teamDatas, array $teamPlayers)
    {
        $stats = [
            'goals'       => (int) $teamDatas['@Score'],
            'fouls'       => 0,
            'top_scorers' => [
                'refs'  => null,
                'goals' => 0,
                'names' => null,
            ]
        ];
        foreach ($teamDatas['Stat'] as $stat) {
            switch ($stat['@Type']) {
                case 'total_yel_card': $stats['yellow_cards'] = (int) $stat['#text']; break;
                case 'total_red_card': $stats['red_cards'] = (int) $stat['#text']; break;
                case 'total_tackle'  : $stats['tackles'] = (int) $stat['#text']; break;
                case 'touches'       : $stats['touches'] = (int) $stat['#text']; break;
            }
        }
        foreach ($teamDatas['PlayerLineUp']['MatchPlayer'] as $playerData) {
            $playerStats = $this->getPlayerStats($playerData);
            $stats['fouls'] += $playerStats['fouls'];
            if ($playerStats['goals']) {
                if ($playerStats['goals'] === $stats['top_scorers']['goals']) {
                    $stats['top_scorers']['refs'] .= ';'.$playerStats['ref'];
                } elseif ($playerStats['goals'] > $stats['top_scorers']['goals']) {
                    $stats['top_scorers']['refs']  = $playerStats['ref'];
                    $stats['top_scorers']['goals'] = $playerStats['goals'];
                }
            }
        }
        if ($stats['top_scorers']['refs']) {
            foreach (explode(';', $stats['top_scorers']['refs']) as $ref) {
                foreach ($teamPlayers as $player) {
                    if ($ref === $player['@uID']) {
                        $stats['top_scorers']['names'] .= $player['PersonName']['First'].' '.$player['PersonName']['Last'].';';
                        break;
                    }
                }
            }
            $stats['top_scorers']['names'] = substr($stats['top_scorers']['names'], 0, -1);
        }

        return $stats;
    }

    /**
     * Get useful player stats
     *
     * @param array $playerDatas statistic of player
     * @return array
     */
    protected function getPlayerStats(array $playerDatas)
    {
        $stats = [
            'ref'   => $playerDatas['@PlayerRef'],
            'goals' => 0,
            'fouls' => 0,
        ];
        foreach ($playerDatas['Stat'] as $stat) {
            if (is_array($stat) && array_key_exists('@Type', $stat)) {
                switch ($stat['@Type']) {
                    case 'fouls': $stats['fouls'] = (int) $stat['#text']; break;
                    case 'goals': $stats['goals'] = (int) $stat['#text']; break;
                }
            }
        }

        return $stats;
    }
}
