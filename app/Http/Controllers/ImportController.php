<?php

namespace App\Http\Controllers;

use App\Cruds\MatchCrud;
use App\Http\Requests\SaveRequest;

/**
 * Class ImportController
 *
 * @resource Import match datas
 */
class ImportController extends Controller
{
    /**
     * Save a match
     *
     * @param SaveRequest $request
     * @param MatchCrud $matchCrud
     * @return JsonResponse
     */
    public function save(
        SaveRequest $request,
        MatchCrud $matchCrud
    ) {
        $matchCrud
            ->setData([
                'competition'    => $request->get('competition'),
                'external_id'    => $request->get('match_id'),
                'season'         => $request->get('season'),
                'sport'          => $request->get('sport'),
                'team_home_name' => $request->get('teams')['home'],
                'team_away_name' => $request->get('teams')['away'],
                'created_at'     => $request->get('created_at'),
                'updated_at'     => $request->get('updated_at'),
                'feed_file'      => $request->get('feed_file'),
            ])
        ;
        $match = $matchCrud->findWhere('external_id', $request->get('match_id'));
        if (! $match) {
            // creation case
            $match   = $matchCrud->create();
            $message = 'Match created';
        } else {
            // update case
            $match = $matchCrud->update();
            $message = 'Match updated';
        }

        return $this->respondWithJson([
            'message' => $message,
        ]);
    }
}
