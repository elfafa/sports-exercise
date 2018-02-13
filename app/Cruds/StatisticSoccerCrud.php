<?php

namespace App\Cruds;

use App\Statistic;

/**
 * Class StatisticSoccerCrud
 */
class StatisticSoccerCrud
{
    protected $analyzedXml = [];

    public function getAnalyzedXml(array $xml)
    {
        $matchDatas = $xml['SoccerDocument']['MatchData'];
        $teams      = $xml['SoccerDocument']['Team'];

        $matchInfo = $matchDatas['MatchInfo'];
        switch ($matchInfo['@Period']) {
            case 'PreMatch': $this->analyzedXml['status'] = Statistic::STATUS_BEFORE; break;
            case 'FullTime': $this->analyzedXml['status'] = Statistic::STATUS_END; break;
            default        : $this->analyzedXml['status'] = Statistic::STATUS_CURRENT; break;
        }
        $this->analyzedXml['start_at'] = new \DateTime($matchInfo['Date']);

        if (array_key_exists('@Type', $matchDatas['Stat'])) {
            $this->getSingleMatchStat($stat);
        } else {
            foreach ($matchDatas['Stat'] as $stat) {
                $this->getSingleMatchStat($stat);
            }
        }

        $teamPlayers = [];
        foreach ($teams as $team) {
            $teamPlayers[$team['@uID']] = $team['Player'];
        }

        $homeTeam = $awayTeam = null;
        foreach ($matchDatas['TeamData'] as $teamDatas) {
            if ('Home' === $teamDatas['@Side']) {
                $homeTeam = $this->getTeamStats($teamDatas, $teamPlayers[$teamDatas['@TeamRef']]);
            } else {
                $awayTeam = $this->getTeamStats($teamDatas, $teamPlayers[$teamDatas['@TeamRef']]);
            }
        }

        $this->analyzedXml['total_goals'] = $homeTeam['goals'] + $awayTeam['goals'];

        $this->analyzedXml['winner_team'] = null;
        if ($homeTeam['goals'] < $awayTeam['goals']) {
            $this->analyzedXml['winner_team'] = 'away';
        } elseif ($homeTeam['goals'] > $awayTeam['goals']) {
            $this->analyzedXml['winner_team'] = 'home';
        }

        if (
            $homeTeam['top_scorers']['goals']
            && $homeTeam['top_scorers']['goals'] < $awayTeam['top_scorers']['goals']
        ) {
            $this->analyzedXml['top_scorer'] = $awayTeam['top_scorers']['names'];
        } elseif (
            $awayTeam['top_scorers']['goals']
            && $awayTeam['top_scorers']['goals'] < $homeTeam['top_scorers']['goals']
        ) {
            $this->analyzedXml['top_scorer'] = $homeTeam['top_scorers']['names'];
        } elseif (
            $awayTeam['top_scorers']['goals']
            && $awayTeam['top_scorers']['goals'] == $homeTeam['top_scorers']['goals']
        ) {
            $this->analyzedXml['top_scorer'] = $homeTeam['top_scorers']['names'].';'.$awayTeam['top_scorers']['names'];
        }

        foreach ([ 'home' => $homeTeam, 'away' => $awayTeam ] as $teamKey => $team) {
            foreach ([ 'tackles', 'touches', 'fouls' ] as $key) {
                $this->analyzedXml['team_'.$teamKey.'_'.$key] = $team[$key];
            }
        }

        return $this->analyzedXml;
    }

    protected function getSingleMatchStat(array $stat)
    {
        switch ($stat['@Type']) {
            case 'match_time': $this->analyzedXml['elapsed_time'] = (int) $stat['#text']; break;
            case 'goals': $this->analyzedXml['total_goals'] = (int) $stat['#text']; break;
        }
    }

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

    protected function getPlayerStats(array $playerData)
    {
        $stats = [
            'ref'   => $playerData['@PlayerRef'],
            'goals' => 0,
            'fouls' => 0,
        ];
        foreach ($playerData['Stat'] as $stat) {
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
