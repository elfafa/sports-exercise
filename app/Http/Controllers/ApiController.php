<?php

namespace App\Http\Controllers;

use App\Cruds\MatchCrud;
use App\Transformers\MatchTransformer;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    /**
     * Save a match
     *
     * @param SaveRequest $request
     * @param MatchCrud $matchCrud
     * @param MatchTransformer $matchTransformer
     * @return JsonResponse
     *
     * @transformer Stikit\Transformers\ApiDoc\MatchTransformer
     */
    public function save(
        Request $request,
        MatchCrud $matchCrud,
        MatchTransformer $matchTransformer
    ) {
        $matchCrud
            ->setData([
                'competition' => $request->get('competition'),
                'external_id' => $request->get('match_id'),
                'season'      => $request->get('season'),
                'sport'       => $request->get('sport'),
                'team_home'   => $request->get('teams')['home'],
                'team_away'   => $request->get('teams')['away'],
                'created_at'  => $request->get('created_at'),
                'updated_at'  => $request->get('updated_at'),
                'feed_file'   => $request->get('feed_file'),
            ])
        ;
        $match = $matchCrud->findWhere('external_id', $request->get('match_id'));
        if (! $match) {
            // creation case
            $match = $matchCrud->create();
        } else {
            // update case
            $match = $matchCrud->update();
        }

        return $this->respondWithJson(
            $matchTransformer->transformModel($match)
        );
    }
}
